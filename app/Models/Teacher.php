<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Teacher extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'nip',
        'type',
        'user_id',
    ];

    protected static function booted(): void
    {
        static::deleting(function ($teacher) {
            if (! $teacher->isForceDeleting()) {
                $teacher->user?->delete();
            }
        });

        static::restoring(function ($teacher) {
            $teacher->user?->restore(); 
        });

        static::forceDeleted(function ($teacher) {
            $teacher->user?->forceDelete(); 
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class)->withPivot('grade')->withTimestamps();
    }

    public function schedules(): MorphMany
    {
        return $this->morphMany(Schedule::class, 'schedulable');
    }

    public function attendances(): MorphMany
    {
        return $this->morphMany(Attendance::class, 'attendable');
    }

    
}
