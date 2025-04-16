<?php

namespace Database\Seeders;

use App\Models\Cohort;
use App\Models\CohortUser;
use App\Models\School;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\UserSchool;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create the default user
        $admin = User::create([
            'last_name'     => 'Admin',
            'first_name'    => 'Admin',
            'email'         => 'admin@codingfactory.com',
            'password'      => Hash::make('123456'),
        ]);

        $teacher = User::create([
            'last_name'     => 'Teacher',
            'first_name'    => 'Teacher',
            'email'         => 'teacher@codingfactory.com',
            'password'      => Hash::make('123456'),
        ]);

        $user = User::create([
            'last_name'     => 'Student',
            'first_name'    => 'Student',
            'email'         => 'student@codingfactory.com',
            'password'      => Hash::make('123456'),
        ]);

        // Create the default school
        $school = School::create([
            'user_id'   => $user->id,
            'name'      => 'Coding Factory',
        ]);

        // Create the admin role
        UserSchool::create([
            'user_id'   => $admin->id,
            'school_id' => $school->id,
            'role'      => 'admin'
        ]);

        // Create the teacher role
        UserSchool::create([
            'user_id'   => $teacher->id,
            'school_id' => $school->id,
            'role'      => 'teacher'
        ]);

        // Create the student role
        UserSchool::create([
            'user_id'   => $user->id,
            'school_id' => $school->id,
            'role'      => 'student'
        ]);

        Cohort::create([
           'school_id'   => '1',
            'name'       => 'B1',
            'description' => 'Coding Factory b1',
            'start_date'=>now(),
            'end_date' => now(),
        ]);

        Cohort::create([
            'school_id'   => '1',
            'name'       => 'B2',
            'description' => 'Coding Factory b2',
            'start_date'=>now(),
            'end_date' => now(),
        ]);

        Cohort::create([
            'school_id'   => '1',
            'name'       => 'B2',
            'description' => 'Coding Factory b2',
            'start_date'=>now(),
            'end_date' => now(),
        ]);

        CohortUser::create([
           'cohort_id'   => '1',
           'user_id'     => $user->id,
        ]);
    }
}
