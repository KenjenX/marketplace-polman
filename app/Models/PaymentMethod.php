<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'bank_name',
        'account_number',
        'account_name',
        'instruction',
        'is_active',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}