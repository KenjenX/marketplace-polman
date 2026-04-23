<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'receipt_file',
        'validation_status',
        'admin_note',
        'uploaded_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}