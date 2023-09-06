<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;

use App\Models\Classwork;
use App\Policies\ClassworkPolicy;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Classwork::class=>ClassworkPolicy::class,
        // Classrom::class => ClassworkPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::before(function(User $user , $ability) {
            if($user->super_admin){
                return true;
            }
        });
        // Gate::define('classworks.view', function (User $user, Classwork $classwork) {
        //     $teacher = $user->classrooms()
        //         ->wherePivot('classroom_id', '=', $classwork->classroom_id)
        //         ->wherePivot('role', '=', 'teacher')
        //         ->exists();

        //     $assigned = $user->classworks()
        //         ->wherePivot('classwork_id', '=', $classwork->id)
        //         ->exists();
        //     return ($teacher || $assigned)
        //   ;
        // });
        // Gate::define('classworks.create', function(User $user, Classroom $classroom) {
            // $result = $user->classrooms()
            //         ->withoutGlobalScope(IserClassroomScope::class)
            //         ->wherePivot('classroom_id', '=', $classroom->classroom_id)
            //         ->wherePivot('role', '=', 'teacher')
            //         ->exists();

            //         return $result
            //           ?Response::allow()
            //           :Response::deny('you are not fucken allow to vist this view');

            // });
        // Gate::define('classworks.update', function (User $user, Classwork $classwork) {
                // return $classwork->user_id == $user->id->id && $user->classrooms()
                //     ->wherePivot('classroom_id', '=', $classwork->classroom_id)
                //     ->wherePivot('role', '=', 'teacher')
                //     ->exists();
            // }
        // );

        // Gate::define(
            // 'classworks.delete', function (User $user, Classwork $classwork) {
            //         return $classwork->user_id == $user->id->id && $user->classrooms()
            //         ->wherePivot('classroom_id', '=', $classwork->classroom_id)
            //         ->wherePivot('role', '=', 'teacher')
            //         ->exists();
            // });

        Gate::define('submissions.create', function(User $user ,Classwork $classwork) {
                $teacher = $user->classrooms()
                    ->wherePivot('classroom_id', '=', $classwork->classroom_id)
                    ->wherePivot('role', '=', 'teacher')
                    ->exists();
                    if($teacher){
                        return false;
                    }

                return $user->classworks()
                    ->wherePivot('classwork_id', '=', $classwork->id)
                    ->exists();
                });
    }
}
