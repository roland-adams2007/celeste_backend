<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    public function list()
    {
        $amenities = Amenity::latest()
            ->get()
            ->map(fn($amenity) => [
                'slug' => $amenity->slug,
                'icon' => $amenity->icon,
                'name' => $amenity->name,
                'description' => $amenity->description
            ]);

        return response()->json($amenities);
    }
}
