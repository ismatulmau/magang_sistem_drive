<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['name', 'path', 'user_id', 'size','starred'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function parent()
{
    return $this->belongsTo(Folder::class, 'parent_id');
}
public function folder()
{
    return $this->belongsTo(Folder::class);
}

public function stars()
    {
        return $this->belongsToMany(User::class, 'file_stars', 'file_id', 'user_id')
                    ->withTimestamps();
    }
}
