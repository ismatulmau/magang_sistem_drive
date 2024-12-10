<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable 
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'ttl',
        'email',
        'email_verified_at',
        'password',
        'verification_code',
    ];

    // Tambahkan metode untuk menghasilkan kode verifikasi
    public function generateVerificationCode()
    {
        $this->verification_code = rand(100000, 999999);
        $this->save();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function starredFolders()
{
    return $this->belongsToMany(Folder::class, 'folder_stars', 'user_id', 'folder_id')->withTimestamps();
}
public function starredFiles()
    {
        return $this->belongsToMany(File::class, 'file_stars', 'user_id', 'file_id')
                    ->withTimestamps();
    }

    
}
