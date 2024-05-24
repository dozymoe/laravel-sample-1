<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'parent_id',
    ];

    /**
     * Get parent company
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo('App\Models\Company', 'parent_id');
    }

    /**
     * Get children companies
     */
    public function children(): HasMany
    {
        return $this->hasMany('App\Models\Company', 'parent_id');
    }
}
