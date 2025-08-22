<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Authenticate extends Controller
{
    use ApiResponses;

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error('Email atau password salah', 401);
        } elseif ($user->status === 'banned') {
            return $this->error('Tidak dapat login, akun anda telah dibanned', 403);
        }

        $user->load('roles:id,name');

        // Device-based token
        $deviceName = $request->header('User-Agent') ?? 'unknown-device';
        $token = $user->createToken(
            $deviceName,
            ['*'],
            Carbon::now()->addDays(7)
        )->plainTextToken;

        return $this->success([
            'user'  => $user,
            'token' => $token,
            'device' => $deviceName
        ], 'Login berhasil');
    }

    public function logout(Request $request)
    {
        // Ambil device dari header (harus sama dengan device saat login)
        $deviceName = $request->header('User-Agent') ?? 'unknown-device';

        // Hapus token spesifik untuk device ini
        $deleted = $request->user()->tokens()
            ->where('name', $deviceName)
            ->delete();

        if ($deleted) {
            return $this->success([], 'Logout berhasil untuk device ini');
        }

        return $this->error('Token device tidak ditemukan atau sudah logout sebelumnya', 400);
    }

    public function user(Request $request)
    {
        $user = $request->user();
        return $this->success([
            'user' => $user,
            'token' => $user->currentAccessToken()
        ], 'User berhasil diambil');
    }
}
