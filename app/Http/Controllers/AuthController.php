<?php

// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Login gagal, cek email/password']);
    }


    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        // Default role to 'staff' if not provided
        $role = $request->input('role', 'user');

        // Hanya admin yang bisa membuat akun dengan role admin
        if ($role === 'admin' && Auth::user()->role !== 'admin') {
            return back()->withErrors(['role' => 'You do not have permission to create an admin account.']);
        }

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'role' => $role,
        ]);

        return redirect('/login')->with('success', 'Registration successful. Please log in.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }

        // Login Mobile
        public function mobileLogin(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password salah'
                ], 401);
            }

            // Hanya user dengan role 'user' yang bisa login via mobile
            if ($user->role !== 'user') {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Hanya user biasa yang bisa login via mobile.'
                ], 403);
            }

            $token = $user->createToken('mobile-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'email' => $user->email
                    ]
                ]
            ]);
        }

        // Register Mobile
        public function mobileRegister(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'username' => 'required|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|confirmed'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user' // Default role untuk mobile
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil',
                'data' => $user
            ], 201);
        }

        // Logout Mobile
        public function mobileLogout(Request $request)
        {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil'
            ]);
        }

        // Get User Data
        public function memobile(Request $request)
        {
            return response()->json([
                'success' => true,
                'data' => $request->user()
            ]);
        }
    }

