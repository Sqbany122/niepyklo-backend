<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Mail\Auth\SendResetPasswordCodeMail;
use App\Models\ResetPasswordCode;
use App\Traits\Exception\ExceptionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    use ExceptionTrait;
    public function __invoke(Request $request)
    {
        // TODO: Implement validation for email address

        try {
            $code = mt_rand(100000, 999999);

            $code = ResetPasswordCode::create([
                'email' => $request->email,
                'code' => $code
            ]);

            Mail::to($request->email)->send(new SendResetPasswordCodeMail($code->code));

            return response()->json([
                'message' => __('messages.password_reset.code_sent')
            ], 200);
        } catch (\Throwable $th) {
            $this->serverErrorException($th->getMessage());
        }
    }
}
