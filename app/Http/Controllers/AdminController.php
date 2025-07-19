<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Constants\constGuards;
use App\Constants\constDefaults;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    public function loginHandler(Request $request)
    {
        // Menentukan apakah input login berupa email atau username
        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Validasi input sesuai dengan tipe field
        if ($fieldType === 'email') {
            $request->validate([
                'login_id' => 'required|email|exists:admins,email',
                'password' => 'required|min:5|max:45',
            ], [
                'login_id.required' => 'Email is required',
                'login_id.email' => 'Please enter a valid email address',
                'login_id.exists' => 'This email does not exist in our records',
                'password.required' => 'Password is required',
                'password.min' => 'Password must be at least 5 characters',
                'password.max' => 'Password must not exceed 45 characters',
            ]);
        } else {
            $request->validate([
                'login_id' => 'required|exists:admins,username',
                'password' => 'required|min:5|max:45',
            ], [
                'login_id.required' => 'Username is required',
                'login_id.exists' => 'This username does not exist in our records',
                'password.required' => 'Password is required',
                'password.min' => 'Password must be at least 5 characters',
                'password.max' => 'Password must not exceed 45 characters',
            ]);
        }

        // Menyusun kredensial untuk proses login
        $credentials = [
            $fieldType => $request->login_id,
            'password' => $request->password,
        ];

        // Proses otentikasi menggunakan guard 'admin'
        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.home');
        } else {
            // Gagal login, kirim flash message dan redirect
            return redirect()->route('admin.login')
                ->withInput($request->only('login_id'))
                ->with('fail', 'Invalid credentials. Please try again.');
        }
    }
    public function logoutHandler(Request $request)
    {
        Auth::guard('admin')->logout();
        session()->flash('fail', 'You are logged out!');
        return redirect()->route('admin.login')->with('success', 'You have been logged out successfully.');
    }
        public function forgotPassword()
    {
        return view('back.pages.admin.auth.forgot-password');
    }

    public function sendPasswordResetLink(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:admins,email',
    ], [
        'email.required' => 'Email is required',
        'email.email' => 'Please enter a valid email address',
        'email.exists' => 'This email does not exist in our records',
    ]);

    // Ambil data admin berdasarkan email
    $admin = Admin::where('email', $request->email)->first();

    // Cek jika admin tidak ditemukan (sebagai perlindungan tambahan)
    if (!$admin) {
        return redirect()->route('admin.forgot-password')
            ->with('fail', 'No admin found with the provided email.');
    }

    $token = base64_encode(Str::random(64));

    // Ganti ini dari string menjadi konstanta (tanpa tanda kutip)
    $guard = constGuards::ADMIN;

    $oldToken = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->where('guard', $guard)
        ->first();

    if ($oldToken) {
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('guard', $guard)
            ->update([
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);
    } else {
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'guard' => $guard,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);
    }

    $actionLink = route('admin.password-reset', ['token' => $token, 'email' => $request->email]);

    $data = [
        'actionLink' => $actionLink,
        'email' => $request->email,
        'admin' => $admin, // Tambahkan ini
    ];

    $mail_body = view('email-templates.admin-forgot-email-template', $data)->render();

    $mailConfig = [
        'mail_from_email' => env('MAIL_FROM_ADDRESS'),
        'mail_from_name' => env('MAIL_FROM_NAME'),
        'mail_recipient_email' => $admin->email,
        'mail_recipient_name' => $admin->name,
        'mail_subject' => 'Password Reset Request',
        'mail_body' => $mail_body,
    ];

    if (sendEmail($mailConfig)) {
        return redirect()->route('admin.forgot-password')->with('success', 'Password reset link sent to your email.');
    } else {
        return redirect()->route('admin.forgot-password')->with('fail', 'Failed to send password reset link. Please try again later.');
    }
}





}