<?php

namespace App\Traits;

use App\Models\Lab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToLab
{
    protected static function bootBelongsToLab(): void
    {
        // Auto-scope queries to current lab
        static::addGlobalScope('lab', function (Builder $builder) {
            if (auth()->check() && auth()->user()->lab_id) {
                $builder->where(
                    (new static)->getTable() . '.lab_id',
                    auth()->user()->lab_id
                );
            }
        });

        // Auto-assign lab_id on create
        static::creating(function ($model) {
            if (auth()->check() && auth()->user()->lab_id && empty($model->lab_id)) {
                $model->lab_id = auth()->user()->lab_id;
            }
        });
    }

    public function lab(): BelongsTo
    {
        return $this->belongsTo(Lab::class);
    }
}
