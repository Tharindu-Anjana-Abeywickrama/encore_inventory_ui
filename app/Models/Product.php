<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'sku', 'status'];

    // Relationships
    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

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
