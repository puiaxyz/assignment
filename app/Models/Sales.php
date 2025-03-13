<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Sales extends Model
{
    use HasFactory;
    protected $fillable = ['total_amount', 'discount', 'final_amount', 'payment_method']; //payment khi cash emo upi chauh dah phot
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
