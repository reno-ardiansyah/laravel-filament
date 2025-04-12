<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassRoom extends Model
{
    protected $fillable = [
        'grade',
        'section',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
