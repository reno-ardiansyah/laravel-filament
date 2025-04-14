<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'period_id',
    ];
    
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class)->withPivot('grade')->withTimestamps();
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}
