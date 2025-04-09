<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        Schema::defaultStringLength(191);

        Blade::if('admin', function () {
            $user = Auth::user();
            return $user && $user->school()?->pivot->role === 'admin';
        });

        Blade::if('teacher', function () {
            $user = Auth::user();
            return $user && $user->school()?->pivot->role === 'teacher';
        });

        Blade::if('student', function () {
            $user = Auth::user();
            return $user && $user->school()?->pivot->role === 'student';
        });
    }
}
