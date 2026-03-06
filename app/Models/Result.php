<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    protected $fillable = [
        'order_item_id', 'entered_by', 'value', 'unit',
        'normal_range', 'is_abnormal', 'flag', 'remarks',
        'is_verified', 'verified_by', 'verified_at',
    ];

    protected $casts = [
        'is_abnormal' => 'boolean',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function enteredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entered_by');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
