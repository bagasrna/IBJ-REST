<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserCourse;
use App\Models\Category;
use App\Models\Course;
use App\Models\Admin;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name' => 'Bagas',
            'email' => 'bagasrnfull@gmail.com',
            'password' => bcrypt('Password234#'),
        ]);

        Category::create([
            'name' => 'SEO',
        ]);

        Category::create([
            'name' => 'Cyber Security',
        ]);

        Course::create([
            'title' => 'Implement SEO In Your Web',
            'course_category_id' => 1,
        ]);

        Course::create([
            'title' => 'Defend From DDOS Attack',
            'course_category_id' => 2,
        ]);

        User::create([
            'name' => 'Daffa',
            'email' => 'daffa@gmail.com',
            'password' => bcrypt('Spenesa234#'),
        ]);

        User::create([
            'name' => 'Ihsan',
            'email' => 'ihsan@gmail.com',
            'password' => bcrypt('Spenesa234#'),
        ]);

        UserCourse::create([
            'users_id' => 1,
            'course_id' => 1,
        ]);

        UserCourse::create([
            'users_id' => 1,
            'course_id' => 2,
        ]);

        UserCourse::create([
            'users_id' => 2,
            'course_id' => 1,
        ]);

        UserCourse::create([
            'users_id' => 2,
            'course_id' => 2,
        ]);
    }
}
