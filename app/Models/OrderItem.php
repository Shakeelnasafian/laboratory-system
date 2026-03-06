<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'test_id', 'price', 'status'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function result(): HasOne
    {
        return $this->hasOne(Result::class);
    }
}
