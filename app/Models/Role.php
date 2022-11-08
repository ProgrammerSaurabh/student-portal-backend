<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Role extends Model
{
    protected $fillable = [
        'name',
    ];

    public const USER = 1;
    public const ADMIN = 2;

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
