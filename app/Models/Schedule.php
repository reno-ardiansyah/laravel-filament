<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $fillable = [
        'subject_id',
        'class_room',
        'period_id',
        'day',
        'start_time',
        'end_time',
        'schedulable_type',
        'schedulable_id'
    ];


    public function schedulable(): MorphTo
    {
        return $this->morphTo();
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'class_room');
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}
