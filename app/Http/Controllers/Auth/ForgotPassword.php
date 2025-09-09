<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordRequestMail;
use Illuminate\Support\Facades\DB;

class ForgotPassword extends Controller
{
    use ApiResponses;

    public function resetPasswordRequest(Request $request)
    {
        // Implementasi logika untuk permintaan reset password

        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->error('Reset Password Failed', 'User not found.', [], 404);
        }

        $code = rand(100000, 999999);
        $user->otp_code = $code;
        $user->otp_expires_at = now()->addMinutes(5);

        if ($user->save()) {
            $emailData = array(
                'heading' => 'Reset Password',
                'name' => $user->name,
                'email' => $user->email,
                'code' => $user->otp_code,
            );

            Mail::to($emailData['email'])->send(new ResetPasswordRequestMail($emailData));
            return $this->success(
                null,
                'Password reset code sent successfully. Please check your email.',
                200
            );
        } else {
            return $this->error('Reset Password Failed', 'Failed to send reset password code.', [], 500);
        }
    }

    public function verifyOTP(Request $request)
    {
        // Implementasi logika untuk verifikasi OTP

        $request->validate([
            'email' => 'required|string|email',
            'code' => 'required|integer',
        ]);

        $user = User::where('email', $request->email)->where('otp_code', $request->code)->first();

        if (!$user) {
            return $this->error('OTP Verification Failed', 'Invalid email or OTP code.', [], 401);
        }

        if ($user->otp_expires_at < now()) {
            // Hapus OTP setelah kadaluarsa atau digunakan
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->save();
            return $this->error('OTP Verification Failed', 'OTP has expired.', [], 400);
        }

        // OTP valid. Hapus OTP dari tabel users karena sudah digunakan.
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        // Generate token reset password baru dan simpan di password_reset_tokens
        $token = Str::random(60); // Token acak 60 karakter

        // Hapus token lama untuk email ini jika ada
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        return $this->success(
            ['email' => $user->email, 'token' => $token], // <-- Kirim email dan token ke frontend
            'OTP verified successfully. You can now reset your password.',
            200
        );
    }
}
