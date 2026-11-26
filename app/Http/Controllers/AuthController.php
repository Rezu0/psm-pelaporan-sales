<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // ---------- LOGIN ----------

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate(['email' => 'required|email', 'password' => 'required']);

        if (! Auth::attempt($data)) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password salah.']);
        }

        return $this->masuk($request, Auth::user());
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ---------- REGISTER (FORM) ----------

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'contact'  => 'required|string|max:20',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ], [
            'email.unique' => 'Email ini sudah terdaftar. Silakan login.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        return $this->buat($data);
    }

    // ---------- GOOGLE ----------

    public function googleRedirect(Request $request)
    {
        session(['google_mode' => $request->query('mode', 'login')]);

        return Socialite::driver('google')->redirect();
    }

    public function googleCallback(Request $request)
    {
        try {
            $g = Socialite::driver('google')->user();
        } catch (\Throwable) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Login dengan Google gagal. Silakan coba lagi.']);
        }

        $mode = session()->pull('google_mode', 'login');
        $user = User::firstWhere('email', $g->getEmail());

        if ($user && $mode === 'register') {
            return redirect()->route('register')
                ->withErrors(['email' => 'Email ini sudah terdaftar. Silakan login.']);
        }

        if (! $user) {
            session(['google_pending' => ['name' => $g->getName(), 'email' => $g->getEmail()]]);

            return $mode === 'register'
                ? redirect()->route('register.google')
                : redirect()->route('login')->with('popup', 'belum_terdaftar');
        }

        // Akun terdaftar: cek aktif dulu, baru masuk
        if (! $user->is_active) {
            return redirect()->route('login')->with('popup', 'belum_aktif');
        }

        Auth::login($user);

        return $this->masuk($request, $user);
    }

    // ---------- REGISTER (GOOGLE) ----------

    public function showRegisterGoogle()
    {
        $pending = session('google_pending');

        return $pending ? view('auth.register-google', compact('pending')) : redirect()->route('register');
    }

    public function registerGoogle(Request $request)
    {
        $pending = session('google_pending');

        if (! $pending || User::where('email', $pending['email'])->exists()) {
            return redirect()->route('register');
        }

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'contact'  => 'required|string|max:20',
            'password' => 'nullable|string|min:8',
        ], ['password.min' => 'Password minimal 8 karakter.']);

        session()->forget('google_pending');

        return $this->buat([
            'name'     => $data['name'],
            'contact'  => $data['contact'],
            'email'    => $pending['email'],
            // kosong -> diisi acak, login form baru bisa setelah password diatur
            'password' => $data['password'] ?: Str::random(40),
        ]);
    }

    // ---------- BANTUAN ----------

    private function buat(array $data)
    {
        $data['password'] = Hash::make($data['password']);

        User::create($data + [
            'uuid'      => Str::uuid(),
            'role'      => 'sales',
            'is_active' => false,
        ]);

        return redirect()->route('login')->with('popup', 'berhasil');
    }

    private function masuk(Request $request, User $user)
    {
        if (! $user->is_active) {
            Auth::logout();

            return redirect()->route('login')->with('popup', 'belum_aktif');
        }

        $request->session()->regenerate();

        return redirect()->route(in_array($user->role, ['admin', 'owner']) ? 'dashboard' : 'laporan.create');
    }
}