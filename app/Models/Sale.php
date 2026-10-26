<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sale_number',
        'customer_name',
        'customer_phone',
        'shipping_address',
        'notes',
        'total',
        'payment_method',
        'payment_status',
        'status',
        'cancellation_reason',
        'cancellation_requested_at',
        'cancelled_by',
        'cancelled_at',
        'sold_at',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'sold_at' => 'datetime',
        'cancellation_requested_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cancelledByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isPendingCancellation(): bool
    {
        return $this->status === 'pending_cancellation';
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'qris' => 'QRIS Instant Payment',
            'transfer' => 'Transfer Bank Otomatis',
            'cash' => 'Tunai di Toko (Cash)',
            default => strtoupper($this->payment_method ?? 'TRANSFER')
        };
    }
}
