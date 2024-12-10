<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\File;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.user-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect('/user/login');
    }
    public function editUserSettings($userId)
    {
        $user = User::findOrFail($userId);
        return view('admin.edit_user_settings', compact('user'));
    }

    public function updateUserSettings(Request $request, $userId)
    {
        $request->validate([
            'max_storage' => 'required|numeric|min:0',
            'allowed_file_types' => 'required|string',
        ]);

        $user = User::findOrFail($userId);
        $user->max_storage = $request->input('max_storage');
        $user->allowed_file_types = $request->input('allowed_file_types');
        $user->save();

        return redirect()->route('admin.editUserSettings', $userId)->with('success', 'Settings updated successfully.');
    }
    public function upload(Request $request)
{
    $file = $request->file('file');
    $path = $file->store('files');
    $size = $file->getSize();

    // Simpan detail file termasuk ukuran
    File::create([
        'user_id' => auth()->id(),
        'name' => $file->getClientOriginalName(),
        'path' => $path,
        'size' => $size,
    ]);

    return redirect()->back()->with('success', 'File uploaded successfully.');
}
}
