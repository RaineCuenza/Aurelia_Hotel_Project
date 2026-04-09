<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomTypes = [
            [
                'name' => 'Standard',
                'slug' => 'standard',
                'description' => 'Cozy and practical room for short stays.',
                'base_price' => 1500,
            ],
            [
                'name' => 'Deluxe',
                'slug' => 'deluxe',
                'description' => 'Spacious room with upgraded amenities.',
                'base_price' => 2500,
            ],
            [
                'name' => 'Suite',
                'slug' => 'suite',
                'description' => 'Premium suite with lounge area and luxury finishes.',
                'base_price' => 4000,
            ],
        ];

        foreach ($roomTypes as $type) {
            RoomType::updateOrCreate(
                ['slug' => $type['slug']],
                $type
            );
        }
    }
}
