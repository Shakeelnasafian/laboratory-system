<?php

namespace App\Models;

use App\Traits\BelongsToLab;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestCategory extends Model
{
    use BelongsToLab;

    protected $fillable = ['lab_id', 'name', 'description', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function tests(): HasMany
    {
        return $this->hasMany(Test::class, 'category_id');
    }
}
