<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Amenity;
use Illuminate\Support\Str;

class AmenitySeeder extends Seeder
{

    protected array $amenities = [
        ['name' => 'WiFi', 'icon' => 'wifi', 'description' => 'High-speed wireless internet access'],
        ['name' => 'Air Conditioning', 'icon' => 'wind', 'description' => 'Climate control for your comfort'],
        ['name' => 'Minibar', 'icon' => 'wine', 'description' => 'Stocked minibar with premium selections'],
        ['name' => 'Jacuzzi', 'icon' => 'droplets', 'description' => 'Private jacuzzi tub for relaxation'],
        ['name' => 'Kitchenette', 'icon' => 'utensils', 'description' => 'Small kitchen area with basic appliances'],
        ['name' => 'Pool Access', 'icon' => 'waves', 'description' => 'Direct access to the swimming pool'],
        ['name' => 'Butler Service', 'icon' => 'concierge-bell', 'description' => 'Personal butler on call 24/7'],
        ['name' => 'Smart TV', 'icon' => 'tv', 'description' => 'Flat-screen TV with streaming capabilities'],
        ['name' => 'Safe', 'icon' => 'lock', 'description' => 'In-room digital safe for valuables'],
        ['name' => 'Workspace', 'icon' => 'monitor', 'description' => 'Dedicated work desk and chair'],
        ['name' => 'Balcony', 'icon' => 'sun', 'description' => 'Private balcony with outdoor seating'],
        ['name' => 'Garden Access', 'icon' => 'trees', 'description' => 'Direct access to the garden area'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->amenities as $amenity) {
            Amenity::updateOrCreate(
                ['slug' => Str::slug($amenity['name'])],
                [
                    'name' => $amenity['name'],
                    'icon' => $amenity['icon'],
                    'description' => $amenity['description'],
                ]
            );
        }

        $this->command->info('✅ Amenities seeded successfully!');
    }
}
