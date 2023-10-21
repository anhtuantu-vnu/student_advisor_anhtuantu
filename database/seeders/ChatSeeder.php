<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [];
        for ($i = 0; $i < 10; $i++) {
            $rooms[] = [
                'uuid' => Str::uuid(),
                'name' => 'test_' . $i
            ];
        }

        Room::insert($rooms);
    }
}
