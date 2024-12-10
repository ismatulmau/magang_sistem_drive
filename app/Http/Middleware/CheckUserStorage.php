<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\File; // Model file Anda

class CheckUserStorage
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $fileSize = $request->file('file')->getSize();
        $fileType = $request->file('file')->getClientOriginalExtension();

        $allowedFileTypes = explode(',', $user->allowed_file_types);
        $currentStorage = File::where('user_id', $user->id)->sum('size'); // Hitung total penyimpanan yang digunakan

        if (!in_array($fileType, $allowedFileTypes)) {
            return redirect()->back()->withErrors(['file' => 'File type not allowed.']);
        }

        if ($currentStorage + $fileSize > $user->max_storage) {
            return redirect()->back()->withErrors(['file' => 'You have exceeded your storage limit.']);
        }

        return $next($request);
    }
}
