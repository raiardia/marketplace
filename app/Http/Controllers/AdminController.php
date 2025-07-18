<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    // public function sendPasswordResetLink(Request $request)
    // {
    // $request->validate([
    //     'email' => 'required|email|exists:admins,email',
    // ]);

    // $status = Password::broker('admins')->sendPasswordResetLink(
    //     $request->only('email')
    // );

    // return $status === Password::RESET_LINK_SENT
    //     ? back()->with(['status' => __($status)])
    //     : back()->withErrors(['email' => __($status)]);
    // }
    // public function sendPasswordResetLink(Request $request){
    //     return 'send via email';
    // }

}




