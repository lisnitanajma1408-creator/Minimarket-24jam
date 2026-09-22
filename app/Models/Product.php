<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'sku', 'image', 'price', 'unit', 'stock', 'created_by'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Status otomatis dari stock (yang kita bahas sebelumnya)
    public function getStatusAttribute()
    {
        if ($this->stock <= 0) return 'Stok Habis';
        if ($this->stock <= 15) return 'Stok Rendah';
        return 'Tersedia';
    }

    // Bonus: biar gampang format harga "Rp45.000" di Blade
    public function getFormattedPriceAttribute()
    {
        return 'Rp' . number_format($this->price, 0, ',', '.');
    }
}