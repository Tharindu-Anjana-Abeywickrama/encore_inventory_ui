<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_path',
        'file_type',
        'file_name',
        'fileable_id',
        'fileable_type',
    ];

    // Polymorphic relation
    public function fileable()
    {
        return $this->morphTo();
    }

    // Accessor to return full URL
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}
