<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'gradable_id',
        'gradable_type',
        'subject_id',
        'period_id',
        'class_room_id',
        'date',
        'score',
        'grade',
        'note',
        'created_by',
    ];

    public function gradable()
    {
        return $this->morphTo(); // ke Student atau entitas lain
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
