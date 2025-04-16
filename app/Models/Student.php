<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'nisn',
        'user_id',
        'teacher_id',
        'entry_year',
        'batch',
        'class_room_id',
        'status',
        'created_by',
    ];

    public function averageGrade()
    {
        return $this->grades()->avg('score');
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function schedules(): MorphMany
    {
        return $this->morphMany(Schedule::class, 'schedulable');
    }

    public function attendances(): MorphMany
    {
        return $this->morphMany(Attendance::class, 'attendable');
    }

    public function grades(): MorphMany
    {
        return $this->morphMany(Grade::class, 'gradable');
    }
}
