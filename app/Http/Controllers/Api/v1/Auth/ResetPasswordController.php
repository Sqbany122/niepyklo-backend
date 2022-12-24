<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Models\ResetPasswordCode;
use App\Models\User;
use App\Traits\Exception\ExceptionTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    use ExceptionTrait;
    public function __invoke(Request $request)
    {
        try {
            $password_reset_code = ResetPasswordCode::firstWhere('code', $request->reset_code);

            if (!$password_reset_code) {
                throw ValidationException::withMessages([
                    __('messages.password_reset.not_exists')
                ]);
            }

            if ($password_reset_code->created_at > Carbon::now()->addMinutes(10)) {
                throw ValidationException::withMessages([
                    __('messages.password_reset.code_expired')
                ]);
            }

            User::firstWhere('email', '=', $password_reset_code->email)->update([
                'password' => Hash::make($request->password)
            ]);

            $password_reset_code->delete();

            return response()->json([
                'message' => __('messages.password_reset.reset_successful')
            ]);
        } catch (ValidationException $e) {
            return $this->validationException($e->getMessage());
        } catch (\Throwable $th) {
            return $this->serverErrorException();
        }
    }
}
