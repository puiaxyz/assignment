<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = ['sale_id', 'product_id', 'quantity', 'price_at_sale'];

    // Relationship: Each SaleItem belongs to a Sale
    public function sale()
    {
        return $this->belongsTo(Sales::class);
    }

    // Relationship: Each SaleItem is linked to a Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
