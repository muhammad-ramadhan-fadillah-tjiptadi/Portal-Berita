<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| USER CONTROLLER - MANAJEMEN USER & AUTENTIKASI
|--------------------------------------------------------------------------
|
| Controller ini mengatur semua operasi terkait user:
| - Pendaftaran user baru (Register)
| - Login dan logout user
| - Autentikasi user
| - Validasi data user
|
*/

class UserController extends Controller
{
    /**
     * Menampilkan halaman login user
     *
     * @return \Illuminate\View\View
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Mendaftarkan user baru ke sistem
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // VALIDASI DATA PENDAFTARAN USER
        // Laravel Validation memastikan data user valid sebelum disimpan
        $request->validate([
            'first_name' => 'required|min:1',    // Nama depan wajib, minimal 1 karakter
            'last_name' => 'required|min:1',     // Nama belakang wajib, minimal 1 karakter
            'email' => 'required|email:dns',     // Email wajib, valid, dan DNS check
            'password' => 'required|min:8'       // Password wajib, minimal 8 karakter
        ], [
            // Custom error messages dalam bahasa Indonesia
            'first_name.required' => 'First name wajib di isi',
            'first_name.min' => 'First name minimal 1 karakter',
            'last_name.required' => 'Last name wajib di isi',
            'last_name.min' => 'Last name minimal 1 karakter',
            'email.required' => 'Email wajib di isi',
            'email.email' => 'Email tidak valid',
            'email.dns' => 'Email domain tidak valid',
            'password.required' => 'Password wajib di isi',
            'password.min' => 'Password minimal 8 karakter',
        ]);

        // BUAT USER BARU DI DATABASE
        // Menggunakan User::create() untuk mass assignment
        $createData = User::create([
            // Gabungkan first_name dan last_name menjadi full name
            'name' => $request->first_name . ' ' . $request->last_name,

            // Email user (unique constraint di database)
            'email' => $request->email,

            // Hash password untuk security
            // Hash::make() akan mengenkripsi password menggunakan bcrypt
            'password' => Hash::make($request->password),

            // Set role default sebagai 'user'
            // Admin harus di-set manual di database
            'role' => 'user'
        ]);

        // CEK STATUS PENDAFTARAN
        // Cek apakah user berhasil dibuat
        if ($createData) {
            // Jika berhasil, redirect ke login dengan success message
            return redirect()->route('login')->with('success', 'Berhasil membuat akun! Silahkan login!');
        } else {
            // Jika gagal, redirect kembali ke signup dengan error message
            return redirect()->route('signup')->with('failed', 'Gagal membuat akun! Silahkan coba lagi!');
        }
    }

    /**
     * Melakukan autentikasi user (login)
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authentication(Request $request)
    {
        // VALIDASI INPUT LOGIN
        // Pastikan email dan password diisi sebelum proses autentikasi
        $request->validate([
            'email' => 'required',     // Email wajib diisi
            'password' => 'required'   // Password wajib diisi
        ], [
            // Custom error messages
            'email.required' => 'Email Harus Diisi',
            'password.required' => 'Password Harus Diisi'
        ]);

        // AMBIL KREDENSIAL USER
        // only() akan mengambil hanya field yang di-specify (security best practice)
        $data = $request->only(['password', 'email']);

        // PROSES AUTENTIKASI
        // Auth::attempt() akan:
        // 1. Mencari user berdasarkan email
        // 2. Memeriksa password menggunakan Hash::check()
        // 3. Jika berhasil, login user dan create session
        // 4. Jika gagal, return false
        if (Auth::attempt($data)) {
            // JIKA LOGIN BERHASIL
            // Ambil data user yang sedang login
            $user = Auth::user();

            // REDIRECT BERDASARKAN ROLE USER
            // Sistem memiliki 2 role: admin dan user
            if ($user->role == 'admin') {
                // Admin -> redirect ke dashboard admin
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Berhasil Login, Selamat Datang <strong> Admin !</strong>');
            } elseif ($user->role == 'user') {
                // User biasa -> redirect ke halaman utama
                // Personalized welcome message dengan nama user
                $welcomeMessage = 'Berhasil Login, Selamat Datang <strong>' . $user->name . '</strong> !';
                return redirect()->route('home')->with('success', $welcomeMessage);
            }
        } else {
            // JIKA LOGIN GAGAL
            // Kemungkinan penyebab:
            // - Email tidak terdaftar
            // - Password salah
            // - Account di-ban/suspend
            return redirect()->back()
                // Kembali ke halaman login
                ->with('error', 'Gagal! Pastikan Email dan Password Benar')
                // Input old() untuk mengisi form lagi (old helper)
                ->withInput();
        }
    }

    /**
     * Melakukan logout user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home')->with('logout', 'Anda Telah Berhasil Logout! Silahkan Login Kembali Untuk Akses Lengkap');
    }

    /**
     * Display all users for admin management
     */
    public function index()
    {
        $users = User::latest()->withCount('posts')->paginate();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show form for creating new user (admin)
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store new user (admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dibuat!');
    }

    /**
     * Show form for editing user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,user',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui!');
    }

    /**
     * Export users ke Excel
     * Fitur: Download data users dalam format Excel menggunakan Yajra
     */
    public function export()
    {
        return Excel::download(new \App\Exports\UsersExport(), 'users_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    /**
     * Soft delete user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent admin from deleting themselves
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    /**
     * Show trashed users for admin
     */
    public function trash()
    {
        $users = User::onlyTrashed()->latest('deleted_at')->withCount('posts')->paginate(20);
        return view('admin.users.trash', compact('users'));
    }

    /**
     * Restore deleted user
     */
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.users.trash')
            ->with('success', 'User berhasil dikembalikan!');
    }

    /**
     * Force delete user permanently
     */
    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();

        return redirect()->route('admin.users.trash')
            ->with('success', 'User berhasil dihapus permanen!');
    }
}
