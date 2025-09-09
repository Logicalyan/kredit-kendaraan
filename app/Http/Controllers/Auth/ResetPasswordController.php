<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ResetPasswordController extends Controller
{
    use ApiResponses;
    public function resetPassword(Request $request)
    {
        // Implementasi logika untuk reset password
        $request->validate([
            'email' => 'required|string|email',
            'token' => 'required|string',
            'new_password' => [
                'required',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ]);

        // Cari token di tabel password_reset_tokens
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$passwordReset) {
            return $this->error('Reset Password Failed', 'Invalid email or reset token.', [], 403); // 403 Forbidden
        }

        // Cek apakah token sudah kadaluarsa (misal 60 menit)
        if (now()->diffInMinutes($passwordReset->created_at) > 60) { // Token berlaku 60 menit
            DB::table('password_reset_tokens')->where('email', $request->email)->delete(); // Hapus token kadaluarsa
            return $this->error('Reset Password Failed', 'Reset token has expired.', [], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->error('Reset Password Failed', 'User not found.', [], 404);
        }

        $user->password = Hash::make($request->new_password);

        if ($user->save()) {
            // Hapus token dari tabel password_reset_tokens setelah berhasil reset
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return $this->success(
                null,
                'Password reset successfully. You can now login with your new password.',
                200
            );
        } else {
            return $this->error('Reset Password Failed', 'Failed to reset password.', [], 500);
        }
    }
}
