<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Assignment extends Pivot
{
    public $table = 'assignments';

    protected $fillable = [
        'subject_id',
        'user_id',
        'score',
    ];

    protected $casts = [
        'score' => 'integer'
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
