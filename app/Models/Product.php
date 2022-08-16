<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'image',
        'description',
        'subcategoryId',
        'status'
    ];
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }
}
