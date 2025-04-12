<?php

namespace App\Providers;

use App\Models\{User, Teacher, Config};
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\{Role, Permission};
use App\Observers\{RoleObserver, PermissionObserver, TeacherObserver, ConfigObserver, UserObserver};

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
        Permission::observe(PermissionObserver::class);
        Role::observe(RoleObserver::class);
        Teacher::observe(TeacherObserver::class);
    }
}
