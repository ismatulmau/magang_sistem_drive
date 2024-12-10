<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $fillable = ['name', 'user_id','starred'];

    public function user()
{
    return $this->belongsTo(User::class);
}
    public function subfolders()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    // Relasi ke files
    public function files()
    {
        return $this->hasMany(File::class, 'folder_id');
    }
    public function stars()
    {
        return $this->belongsToMany(User::class, 'folder_stars', 'folder_id', 'user_id')
                    ->withTimestamps();
    }
    
}
