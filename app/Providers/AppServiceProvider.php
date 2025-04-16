<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\{Role, Permission};
use App\Models\{User, Subject, Teacher, Config, ClassRoom, Schedule, Student,  Period, TeacherSubject};
use App\Observers\{RoleObserver, ClassRoomObserver,StudentObserver , ScheduleObserver,  SubjectObserver, PermissionObserver, TeacherObserver, ConfigObserver, PeriodObserver, UserObserver, TeacherSubjectObserver};

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
        Subject::observe(SubjectObserver::class);
        // TeacherSubject::observe(TeacherSubjectObserver::class);
        Student::observe(StudentObserver::class);
        Schedule::observe(ScheduleObserver::class);
    }
}
