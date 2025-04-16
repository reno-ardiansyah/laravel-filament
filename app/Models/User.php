<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'remember_token',
        'username',
        'phone',
        'address',

        'postal_code',
        'profile_picture',
        'dob',
        'gender',
    ];

    protected static function booted(): void
    {
        static::deleting(function ($user) {
            if (! $user->isForceDeleting()) {
                $user->teacher?->delete(); 
                $user->student?->delete(); 
            }
        });

        static::restoring(function ($user) {
            $user->teacher?->restore();
            $user->student?->restore();
        });

        static::forceDeleted(function ($user) {
            $user->teacher?->forceDelete();
            $user->student?->forceDelete();
        });
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class)->withTrashed();
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class)->withTrashed();
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'created_by');
    }

    public function loggables()
    {
        return $this->morphMany(Logs::class, 'loggable');
    }
}
