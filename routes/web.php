<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriveController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/home', [HomeController::class, 'index'])->middleware('verified');

Route::get('/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::post('/verify', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/verification/resend', [VerificationController::class, 'resend'])->name('verification.resend');

// Route::get('/drive', [DashboardController::class, 'index'])->name('dashboard.index')->middleware('verified');

Route::get('/drive', [DriveController::class, 'index'])->name('drive.index');

Route::post('/drive/upload-file', [DriveController::class, 'uploadFile'])->name('drive.upload-file');

Route::post('/drive/folder', [DriveController::class, 'createFolder'])->name('drive.createFolder');
Route::post('/drive/folder/store', [DriveController::class, 'storeFolder'])->name('drive.folder.store');

Route::get('/drive/upload-file', [DriveController::class, 'uploadFile'])->name('drive.uploadFile')->middleware('check.user.storage');
Route::post('/drive/file/store', [DriveController::class, 'storeFile'])->name('drive.file.store');

Route::get('/drive/upload-folder', [DriveController::class, 'uploadFolder'])->name('drive.uploadFolder');
Route::post('/drive/store-folder-uploads', [DriveController::class, 'storeFolderUploads'])->name('drive.storeFolderUploads');

Route::get('/drive/recent', [DriveController::class, 'recentFiles'])->name('drive.recentFiles');

// Route::get('/drive/starred', [DriveController::class, 'starredItems'])->name('drive.starredItems');
// Route::post('/drive/star-item/{id}', [DriveController::class, 'starItem'])->name('drive.starItem');
Route::post('/star/file/{id}', [DriveController::class, 'starFile'])->name('star.file');
Route::post('/star/folder/{id}', [DriveController::class, 'starFolder'])->name('star.folder');
Route::get('/starred-items', [DriveController::class, 'showStarredItems'])->name('starred.items');
Route::post('/drive/toggle-starred/{id}/{type}', [DriveController::class, 'toggleStarred'])->name('drive.toggleStarred');

Route::get('/drive/trash', [DriveController::class, 'trash'])->name('drive.trash');
Route::delete('/drive/folder/{id}', [DriveController::class, 'destroyFolder'])->name('drive.folder.destroy');
Route::delete('/drive/file/{id}', [DriveController::class, 'destroyFile'])->name('drive.file.destroy');

Route::post('/drive/restore-item/{id}', [DriveController::class, 'restoreItem'])->name('drive.restoreItem');

Route::get('/drive/storage', [DriveController::class, 'storage'])->name('drive.storage');
Route::get('drive/folder/{id}', [DriveController::class, 'showFolder'])->name('drive.folder');

Route::get('/download/file/{id}', [DriveController::class, 'downloadFile'])->name('drive.file.download');
Route::get('/download/folder/{id}', [DriveController::class, 'downloadFolder'])->name('drive.folder.download');

Route::get('/file/{id}/location', [DriveController::class, 'showLocation'])->name('drive.file.showLocation');

Route::put('/drive/folder/{id}/rename', [DriveController::class, 'renameFolder'])->name('drive.folder.rename');
Route::put('/drive/file/{id}/rename', [DriveController::class, 'renameFile'])->name('drive.file.rename');

// Route untuk Buat Salinan Folder
Route::post('/drive/folder/{id}/duplicate', [DriveController::class, 'duplicateFolder'])->name('drive.folder.duplicate');

// Route untuk Buat Salinan File
Route::post('/drive/file/{id}/duplicate', [DriveController::class, 'duplicateFile'])->name('drive.file.duplicate');

Route::prefix('admin')->group(function () {
    Route::get('/users/{user}/settings', [AdminController::class, 'editUserSettings'])->name('admin.editUserSettings');
    Route::put('/users/{user}/settings', [AdminController::class, 'updateUserSettings'])->name('admin.updateUserSettings');
});