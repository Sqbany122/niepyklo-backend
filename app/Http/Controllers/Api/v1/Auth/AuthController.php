<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Interfaces\Auth\AuthInterface;
use App\Models\User;
use App\Traits\Exception\ExceptionTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller implements AuthInterface
{
    use ExceptionTrait;
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            if(!Auth::attempt($request->only(['email', 'password']))){
                throw ValidationException::withMessages([
                    __('messages.invalid_credentials')
                ]);
            }

            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('api-auth-token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'access_token' => $token
            ]);
        } catch (ValidationException $e) {
            return $this->validationException($e->getMessage());
        } catch (\Throwable $th) {
            return $this->serverErrorException();
        }
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $token = $user->createToken('Api-auth-token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'access_token' => $token
            ]);
        } catch (\Throwable $th) {
            return $this->serverErrorException();
        }
    }
}
