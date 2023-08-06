<?php

namespace App\Http\Controllers;

use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
        //dd('ok');
        $request->validate([
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed'],
            'otp' => ['nullable', 'numeric', 'digits:6'],
        ]);

        $reset = PasswordReset::verifyToken($request->token);
      
        if (! $reset) {
            return "Invalid token or expired token";
            
        }

        $user = User::whereEmail($reset->email)->first();

        if (! $user) {
            return "User not found";
        }

        if ($user->is_locked) {
            return "This action cannot be initiated for a locked account.";
        }

        if (Hash::check($request->password, $user->password)) {
            return "Sorry you can't use your old password";
        }

        $user->update(['password' => Hash::make($request->password)]);

        return "Password reset successfully";
    }

    /**
     * Verifies Password Reset Token.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyPasswordToken(string $token)
    {
        $passwordReset = PasswordReset::verifyToken($token);

        if (! $passwordReset) {
            return $this->badRequestResponse('Invalid token or expired token');
        }

        if (! $user = User::firstWhere(['email' => $passwordReset->email])) {
            return $this->notFoundResponse('User not found');
        }

        if ($user->is_locked) {
            return $this->forbiddenResponse('This action cannot be initiated for a locked account.');
        }

        return $this->okResponse(
            'Token verified successfully.',
            ['requires_2fa' => $this->isGoogleAuthEnabled($user)]
        );
    }
}
