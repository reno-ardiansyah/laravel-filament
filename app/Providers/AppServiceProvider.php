<?php

namespace App\Providers;

use App\Models\ClassRoom;
use App\Observers\ClassRoomObserver;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\{Role, Permission};
use App\Models\{User, Teacher, Config, Period};
use App\Observers\{RoleObserver, PermissionObserver, TeacherObserver, ConfigObserver, PeriodObserver, UserObserver};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Config::observe(ConfigObserver::class);
        ClassRoom::observe(ClassRoomObserver::class);
        Permission::observe(PermissionObserver::class);
        Role::observe(RoleObserver::class);
        Teacher::observe(TeacherObserver::class);
        Period::observe(PeriodObserver::class);
    }
}
