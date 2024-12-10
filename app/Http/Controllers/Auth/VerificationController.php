<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Mail\VerificationCode;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    public function show()
    {
        return view('auth.verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|numeric',
        ]);

        $user = User::where('verification_code', $request->verification_code)->first();

        if ($user) {
            $user->email_verified_at = now();
            $user->verification_code = null; // Hapus kode setelah verifikasi
            $user->save();

            // Redirect to login page with success message
            return redirect('/login')->with('status', 'Email berhasil diverifikasi. Silahkan login.');
        }

        return back()->withErrors(['verification_code' => 'Kode verifikasi salah.']);
    }

    public function resend()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect('/home');
        }

        Auth::user()->generateVerificationCode();
        Mail::to(Auth::user()->email)->send(new VerificationCode(Auth::user()));

        return back()->with('resent', true);
    }
}
