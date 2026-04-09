<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\JWTGuard; // <-- Tambahkan ini untuk VS Code

class AuthController extends Controller
{
    /**
     * Helper untuk memberitahu VS Code bahwa kita menggunakan JWTGuard
     * * @return JWTGuard
     */
    protected function guard(): JWTGuard
    {
        return auth('api');
    }

    // Registrasi User Baru
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'age' => 'required|numeric',
            'weight' => 'required|numeric',
            'height' => 'required|numeric',
            'class_room' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'age' => $request->age,
            'weight' => $request->weight,
            'height' => $request->height,
            'class_room' => $request->class_room,
        ]);

        // Gunakan $this->guard() sebagai ganti auth('api')
        $token = $this->guard()->login($user);

        return $this->respondWithToken($token, 'Registrasi berhasil');
    }

    // Login User / Admin
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = $this->guard()->attempt($credentials)) {
            return response()->json(['error' => 'Email atau password salah'], 401);
        }

        return $this->respondWithToken($token, 'Login berhasil');
    }

    // Lupa Password
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Email tidak ditemukan'], 404);
        }

        return response()->json(['message' => 'Instruksi reset kata sandi telah dikirim ke email Anda.']);
    }

    // Mendapatkan Profil User yang sedang Login
    public function me()
    {
        return response()->json($this->guard()->user());
    }

    // Logout
    public function logout()
    {
        $this->guard()->logout();
        return response()->json(['message' => 'Berhasil logout']);
    }

    // Format Response Token JSON
    protected function respondWithToken($token, $message = 'Sukses')
    {
        return response()->json([
            'message' => $message,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
            'user' => $this->guard()->user() 
        ]);
    }
}