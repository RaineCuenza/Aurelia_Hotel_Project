<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $map = [
            'standard' => [
                ['room_number' => '101', 'max_guests' => 2],
                ['room_number' => '102', 'max_guests' => 2],
                ['room_number' => '103', 'max_guests' => 2],
            ],
            'deluxe' => [
                ['room_number' => '201', 'max_guests' => 3],
                ['room_number' => '202', 'max_guests' => 3],
                ['room_number' => '203', 'max_guests' => 3],
            ],
            'suite' => [
                ['room_number' => '301', 'max_guests' => 5],
                ['room_number' => '302', 'max_guests' => 5],
                ['room_number' => '303', 'max_guests' => 5],
            ],
        ];

        foreach ($map as $slug => $rooms) {
            $roomType = RoomType::where('slug', $slug)->first();

            if (! $roomType) {
                continue;
            }

            foreach ($rooms as $roomData) {
                Room::updateOrCreate(
                    ['room_number' => $roomData['room_number']],
                    [
                        'room_type_id' => $roomType->id,
                        'max_guests' => $roomData['max_guests'],
                        'status' => 'available',
                    ]
                );
            }
        }
    }
}
