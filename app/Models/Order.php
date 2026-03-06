<?php

namespace App\Models;

use App\Traits\BelongsToLab;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use BelongsToLab;

    protected $fillable = [
        'lab_id', 'patient_id', 'created_by', 'order_number',
        'status', 'is_urgent', 'total_amount', 'discount',
        'net_amount', 'referred_by', 'notes', 'collected_at', 'completed_at',
    ];

    protected $casts = [
        'is_urgent' => 'boolean',
        'total_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'collected_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    const STATUSES = [
        'pending'          => 'Pending',
        'sample_collected' => 'Sample Collected',
        'processing'       => 'Processing',
        'completed'        => 'Completed',
        'cancelled'        => 'Cancelled',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $date = now()->format('Ymd');
                $count = static::withoutGlobalScope('lab')
                    ->where('lab_id', $order->lab_id)
                    ->whereDate('created_at', today())
                    ->count() + 1;
                $order->order_number = 'ORD-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
