<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCode;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nama' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'ttl' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'nama' => $data['nama'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'tempat_lahir' => $data['tempat_lahir'],
            'ttl' => $data['ttl'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => null, // Tandai email belum diverifikasi
        ]);

        // Kirim email verifikasi
        $user->generateVerificationCode();
        Mail::to($user->email)->send(new VerificationCode($user));

        return $user;
    }

    protected function registered(Request $request, $user)
    {
        // Log the user out after registration
        $this->guard()->logout();
        return redirect('/verify')->with('status', 'Registrasi Sukses. Silahkan login.');
    }
}
