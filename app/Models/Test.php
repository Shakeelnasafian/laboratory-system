<?php

namespace App\Models;

use App\Traits\BelongsToLab;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Test extends Model
{
    use BelongsToLab;

    protected $fillable = [
        'lab_id', 'category_id', 'name', 'short_name', 'code',
        'price', 'unit', 'normal_range', 'normal_range_male',
        'normal_range_female', 'description', 'sample_type',
        'turnaround_hours', 'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(TestCategory::class, 'category_id');
    }
}
