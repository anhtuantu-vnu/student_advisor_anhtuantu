<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [];
        for($i = 0; $i < 100; $i++) {
            $users[] = [
                'first_name' => 'A' . $i,
                'last_name' => 'Nguyen Van',
                'uuid' => Str::uuid(),
                'role' => 'student',
                'unique_id' => Str::uuid(),
                'email' => 'jos.anhtuan99@gmail.com' . $i,
                'password' => Hash::make('123456')
            ];
        }
        User::insert($users);
    }
}
