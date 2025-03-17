<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stocks extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'quantity', 'price', 'stock_date'];

    public function products()
    {
        return $this->belongsTo(Products::class);
    }
}
