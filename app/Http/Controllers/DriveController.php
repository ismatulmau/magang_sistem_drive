<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

class DriveController extends Controller
{
    public function index()
    {
        // Ambil folder utama dan files yang tidak memiliki parent atau folder_id
        $folders = Folder::whereNull('parent_id')->with('user', 'stars')->get();
        $files = File::whereNull('folder_id')->with('user', 'stars')->get();
    
        return view('pengguna.drive.index', compact('folders', 'files'));
    }

    public function createFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id', // Pastikan parent_id ada di table folders
        ]);

        $folder = new Folder;
        $folder->name = $request->name;
        $folder->parent_id = $request->parent_id; // Menetapkan parent_id ke folder baru
        $folder->user_id = auth()->id(); // Atau id pengguna lain jika perlu
        $folder->save();

        return redirect()->route('drive.folder', ['id' => $folder->parent_id ?? $folder->id])
            ->with('success', 'Folder berhasil dibuat.');
    }

    public function showFolder($id)
    {
        $folder = Folder::findOrFail($id);
        $subFolders = $folder->subFolders; // Ambil sub-folder
        $files = $folder->files; // Ambil file dalam folder

        return view('pengguna.drive.folder', [
            'folder' => $folder,
            'subFolders' => $subFolders,
            'files' => $files,
        ]);
    }

    public function storeFolder(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        Folder::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'user_id' => auth()->id(), 
        ]);

        return redirect()->route('drive.index')->with('success', 'Folder berhasil dibuat.');
    }


    public function storeFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('files');

            File::create([
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'folder_id' => $request->folder_id,
                'user_id' => Auth::id(),
                
            ]);

            return redirect()->back()->with('success', 'File berhasil diunggah');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah file');
    }

    public function uploadFolder()
    {
        return view('pengguna.drive.upload-folder');
    }

    public function storeFolderUploads(Request $request)
    {
        foreach ($request->file('folder_files') as $file) {
            $fileName = $file->getClientOriginalName();
            $file->move(public_path('uploads/folder'), $fileName);

            $uploadedFile = new File();
            $uploadedFile->name = $fileName;
            $uploadedFile->path = 'uploads/folder/' . $fileName;
            $uploadedFile->size = $file->getSize(); // Menyimpan ukuran file dalam byte
            $uploadedFile->user_id = Auth::id();
            $uploadedFile->save();
        }

        return response()->json(['success' => 'Folder berhasil diupload.']);
    }

    public function recentFiles()
    {
        $files = File::where('user_id', Auth::id())->orderBy('updated_at', 'desc')->take(10)->get();
        return view('pengguna.drive.recent-files', compact('files'));
    }

    public function restoreItem($id)
    {
        $item = File::findOrFail($id);
        $item->is_trashed = 0;
        $item->save();

        return response()->json(['success' => 'Item berhasil dipulihkan dari Sampah.']);
    }

    public function storage()
    {
        $totalStorage = 5000; // in MB
        $usedStorage = File::where('user_id', Auth::id())->sum('size'); // in MB
        return view('pengguna.drive.storage', compact('totalStorage', 'usedStorage'));
    }
    public function renameFolder(Request $request, $id)
    {
        $folder = Folder::findOrFail($id);
        $folder->name = $request->input('name');
        $folder->save();

        return redirect()->back()->with('success', 'Folder berhasil diubah namanya.');
    }

    public function renameFile(Request $request, $id)
    {
        $file = File::findOrFail($id);
        $file->name = $request->input('name');
        $file->save();

        return redirect()->route('drive.index')->with('success', 'Nama file berhasil diganti.');
    }

    public function duplicateFolder($id)
    {
        $folder = Folder::findOrFail($id);

        $newFolder = $folder->replicate();
        $newFolder->name = $folder->name . ' (Copy)';
        $newFolder->save();

        foreach ($folder->subfolders as $subfolder) {
            $subfolder->replicate()->fill([
                'parent_id' => $newFolder->id,
            ])->save();
        }

        foreach ($folder->files as $file) {
            $this->duplicateFile($file->id, $newFolder->id);
        }

        return redirect()->back()->with('success', 'Folder berhasil disalin.');
    }

    public function duplicateFile($id, $newFolderId = null)
    {
        $file = File::findOrFail($id);

        $newFile = $file->replicate();
        $newFile->name = $file->name . ' (Copy)';

        if ($newFolderId) {
            $newFile->folder_id = $newFolderId;
        }

        $originalPath = storage_path('app/' . $file->path);
        $newPath = 'files/' . Str::random(40) . '.' . pathinfo($file->path, PATHINFO_EXTENSION);
        \Storage::copy($file->path, $newPath);

        $newFile->path = $newPath;
        $newFile->save();

        if (!$newFolderId) {
            return redirect()->back()->with('success', 'File berhasil disalin.');
        }
    }

    public function downloadFile($id)
    {
        $file = File::find($id);

        if ($file) {
            $filePath = $file->path;
            $fileName = $file->name;

            \Log::info('File Path: ' . $filePath);
            \Log::info('File Exists: ' . Storage::exists($filePath));

            if (Storage::exists($filePath)) {
                return Storage::download($filePath, $fileName);
            } else {
                return redirect()->back()->with('error', 'File tidak ditemukan.');
            }
        } else {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }
    }

    public function downloadFolder($id)
    {
        $folder = Folder::findOrFail($id);
        $zipFileName = $folder->name . '.zip';
        $zipFilePath = storage_path('app/' . $zipFileName);

        $zip = new ZipArchive;
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            $files = File::where('folder_id', $folder->id)->get(); // Ambil file dari folder

            foreach ($files as $file) {
                $filePath = storage_path('app/' . $file->path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $file->name);
                } else {
                    \Log::warning('File not found: ' . $filePath);
                }
            }

            $zip->close();

            if (file_exists($zipFilePath)) {
                return response()->download($zipFilePath)->deleteFileAfterSend(true);
            } else {
                return redirect()->back()->with('error', 'File ZIP tidak ditemukan.');
            }
        } else {
            return redirect()->back()->with('error', 'Tidak dapat meng-zip folder.');
        }
    }

    public function destroyFolder($id)
    {
        
        $folder = Folder::findOrFail($id);

        foreach ($folder->subfolders as $subfolder) {
            $this->destroyFolder($subfolder->id); 
        }

        foreach ($folder->files as $file) {
            $this->destroyFile($file->id); 
        }
        $folder->delete();

        return redirect()->back()->with('success', 'Folder berhasil dihapus.');
    }
    public function destroyFile($id)
    {
        // Temukan file berdasarkan ID
        $file = File::findOrFail($id);

        // Hapus file dari storage
        if (Storage::exists($file->path)) {
            Storage::delete($file->path);
        }

        // Hapus record file dari database
        $file->delete();

        return redirect()->back()->with('success', 'File berhasil dihapus.');
    }

    // Metode untuk menampilkan lokasi file
    public function showLocation($id)
    {
        // Ambil file berdasarkan ID
        $file = File::findOrFail($id);

        // Mengarahkan ke URL folder tempat file berada
        $folderPath = $file->folder_path; // Pastikan folder_path diisi dengan benar
        return redirect()->to(asset('storage/' . $folderPath));
    }

    public function starFile($id)
    {
        $file = File::findOrFail($id);
    
        // Periksa apakah file sudah ada di daftar bintang
        if ($file->stars()->where('user_id', Auth::id())->exists()) {
            return back()->with('info', 'File sudah ada di daftar bintang.');
        }
    
        // Menambahkan file ke daftar bintang
        $file->stars()->attach(Auth::id());
    
        return back()->with('success', 'File berhasil ditambahkan ke bintang.');
    }
    
    public function starFolder($id)
    {
        $folder = Folder::findOrFail($id);
    
        // Periksa apakah folder sudah ada di daftar bintang
        if ($folder->stars()->where('user_id', Auth::id())->exists()) {
            return back()->with('info', 'Folder sudah ada di daftar bintang.');
        }
    
        // Menambahkan folder ke daftar bintang
        $folder->stars()->attach(Auth::id());
    
        return back()->with('success', 'Folder berhasil ditambahkan ke bintang.');
    }
    
    public function showStarredItems()
    {
        $user = Auth::user();
    
        // Mendapatkan file dan folder yang dibintangi
        $starredFiles = $user->starredFiles;
        $starredFolders = $user->starredFolders;
    
        return view('pengguna.drive.starred', compact('starredFiles', 'starredFolders'));
    }
    public function toggleStarred(Request $request, $id, $type)
    {
        if ($type == 'folder') {
            $item = Folder::find($id);
        } else {
            $item = File::find($id);
        }
    
        if ($item) {
            $item->starred = !$item->starred; // Toggle nilai is_starred
            $item->save();
        }
    
        return redirect()->back()->with('status', 'Item berhasil diupdate.');
    }
}
