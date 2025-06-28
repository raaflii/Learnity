<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $admin = User::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'first_name' => 'Admin',
            'last_name' => 'User',
            'role_id' => Role::where('name', 'admin')->first()->id,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        UserProfile::factory()->create(['user_id' => $admin->id]);

        // Create Teachers
        $teachers = User::factory(5)->create([
            'role_id' => Role::where('name', 'teacher')->first()->id,
        ]);

        foreach ($teachers as $teacher) {
            UserProfile::factory()->create(['user_id' => $teacher->id]);
        }

        // Create Students
        $students = User::factory(20)->create([
            'role_id' => Role::where('name', 'student')->first()->id,
        ]);

        foreach ($students as $student) {
            UserProfile::factory()->create(['user_id' => $student->id]);
        }
    }
}
