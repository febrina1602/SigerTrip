<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

class PasswordResetController extends Controller
{
    // Halaman form lupa kata sandi (input email)
    public function showForm()
    {
        return view('auth.passwords.email');
    }

    // Mengirim email reset password
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Kami telah mengirimkan tautan reset kata sandi ke email Anda!')
            : back()->withErrors(['email' => trans($status)]);
    }

    // Halaman form reset password setelah klik link email
     public function resetForm(Request $request)
    {
    return view('auth.passwords.reset', [
        'token' => $request->token,
        'email' => $request->email
    ]);
    }


    // Simpan password baru
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Password berhasil direset!')
            : back()->withErrors(['email' => [__($status)]]);
    }

    // OPSIONAL — SEND OTP
    public function sendVerificationCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $verificationCode = rand(100000, 999999);

        Mail::to($request->email)->send(new VerificationCodeMail($verificationCode));

        session(['verification_code' => $verificationCode]);

        return back()->with('status', 'Kode verifikasi telah dikirimkan ke email Anda.');
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|integer|size:6',
        ]);

        if ($request->verification_code == session('verification_code')) {
            return redirect()->route('password.reset');
        }

        return back()->withErrors(['verification_code' => 'Kode verifikasi salah.']);
    }
}
