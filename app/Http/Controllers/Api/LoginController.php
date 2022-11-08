<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ForgotPasswordRequest;
use App\Http\Requests\Api\ForgotPasswordVerifyRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Mail\Api\ForgotPasswordMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        ['email' => $email, 'password' => $password] = $request->validated();

        $user = User::where(
            ['email' => $email]
        )
            ->first();

        if (is_null($user)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        if (!Hash::check($password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken("auth_token")->plainTextToken;

        return response()
            ->json([
                'message' => 'Logged in successfully',
                'token' => $token,
                'user' => $this->userDetails($user)
            ]);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        ['email' => $email] = $request->validated();

        $user = User::where(
            ['email' => $email]
        )
            ->first();

        if (is_null($user)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = Str::random(60);

        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now()
        ]);

        $url = __(config('frontend.forgot-password'), [
            'token' => $token,
            'email' => urlencode($email)
        ]);

        Mail::to($email)
            ->send(new ForgotPasswordMail($url));

        return response()
            ->json([
                'message' => "A password reset link has been sent to your $email. Thank you!",
            ]);
    }

    public function forgotPasswordVerify(ForgotPasswordVerifyRequest $request): JsonResponse
    {
        ['email' => $email, 'token' => $token, 'password' => $password] = $request->validated();

        $user = User::where(
            ['email' => $email]
        )
            ->first();

        if (is_null($user)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $passwordReset = DB::table('password_resets')
            ->where([
                'email' => $email,
                'token' => $token
            ]);

        if (is_null($passwordReset->first())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user->update([
            'password' => bcrypt($password)
        ]);

        $passwordReset->delete();

        return response()
            ->json([
                'message' => "Password reset successfully!",
            ]);
    }

    public function profile(): JsonResponse
    {
        return response()
            ->json([
                'user' => $this->userDetails(request()->user())
            ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()
            ->json([
                'message' => "Logged out successfully!",
            ]);
    }

    private function userDetails(User $user): array
    {
        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
        ];
    }
}
