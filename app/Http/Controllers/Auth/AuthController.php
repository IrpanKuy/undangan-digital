<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Laravel\Socialite\Socialite;
use Illuminate\Support\Str;

use function Symfony\Component\Translation\t;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLogin()
    {
        return Inertia::render('auth/login');
    }

    public function redirectToGoogleRegister()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try{
        $user = Socialite::driver('google')->user();

        $avatarPath = null; // Default null jika tidak ada foto atau gagal download

        if ($user->avatar) {
            try {
                // 1. Ambil data mentah gambar dari URL Google
                $fileContents = file_get_contents($user->avatar);

                // 2. Buat nama file unik (menggunakan ID Google agar tidak ganda kalau dia login lagi)
            $avatarName = 'avatars/google_' . $user->id . '.jpg';

            // 3. Simpan ke folder storage/app/public/avatars
            Storage::disk('public')->put($avatarName, $fileContents);

            // 4. Set path untuk disimpan ke database
            $avatarPath = $avatarName;
        } catch (\Exception $e) {
            // Jika Google menolak atau koneksi gagal, biarkan avatarPath tetap null
            $avatarPath = null;
        }
        }
        $newUser = User::updateOrCreate([
            'email' => $user->email,
        ], [
            'name' => $user->name,
            'password' => Hash::make(Str::random(12)), // password acak
            'provider' => 'google',
            'provider_id' => $user->id,
            'langganan_id' => 1,
            'profile_path' => $avatarPath,
        ]);

        Auth::login($newUser);

        return redirect()->route('admin.kategori-undangan.index');

    }catch (\Exception $e){
        return dd($e->getMessage());
    }
    }
    /** 
     * Proses login user.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.kategori-undangan.index'));
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ]);
    }

    /**
     * Tampilkan halaman register.
     */
    public function showRegister()
    {
        return Inertia::render('auth/register');
    }

    /**
     * Proses registrasi user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'no_hp' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', 'min:8'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'no_hp.required' => 'Nomor WhatsApp wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default role
        ]);

        Auth::login($user);

        return redirect()->route('admin.kategori-undangan.index');
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Redirect ke Google untuk Login.
     */
    public function redirectToGoogle()
    {
        // Socialite implementation would go here
        return redirect()->back()->with('error', 'Fitur login Google sedang dalam tahap pengembangan.');
    }
}
