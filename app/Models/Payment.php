<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'order_id',
        'amount',
        'payment_status',
        'payment_type',
        'transaction_id',
        'transaction_status',
        'transaction_time',
        'settlement_time',
        'midtrans_response',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_time' => 'datetime',
        'settlement_time' => 'datetime',
        'midtrans_response' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Scopes
    public function scopeSettled($query)
    {
        return $query->where('payment_status', 'settlement');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    // Accessors
    public function getIsSettledAttribute()
    {
        return $this->payment_status === 'settlement';
    }

    public function getIsPendingAttribute()
    {
        return $this->payment_status === 'pending';
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    // Helper methods
    public static function generateOrderId()
    {
        return 'ORDER-' . time() . '-' . rand(1000, 9999);
    }
}