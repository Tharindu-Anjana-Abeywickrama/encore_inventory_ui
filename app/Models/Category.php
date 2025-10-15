<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
     use HasFactory;

    protected $fillable = ['name', 'status'];

    // Relationships
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return $this->status ? 'Active' : 'Inactive';
    }
}
