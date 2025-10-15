<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
     use HasFactory;

    protected $fillable = ['product_id', 'name', 'color', 'size', 'price', 'quantity', 'status'];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
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
