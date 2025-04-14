<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'attendable_id',
        'attendable_type',
        'date',
        'status',
        'note',
        'type',
        'created_by',
        'period_id',
        'deleted_by',
    ];
    
    public function attendable(): MorphTo
    {
        return $this->morphTo();
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
