<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'membership_id',
        'payment_proof',
        'status',
        'start_date',
        'end_date',
        'amount',
        'transaction_code',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public static function generateTransactionCode()
    {
        // Your transaction code generation logic
        return 'TRX-' . time();
    }
}
