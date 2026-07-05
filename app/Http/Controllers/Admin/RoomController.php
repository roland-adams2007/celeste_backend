<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $roomTypes = RoomType::with(['amenities', 'rooms'])->latest()->get();

            return response()->json(
                $roomTypes->map(fn(RoomType $roomType) => $this->formatRoomType($roomType))
            );
        }

        return view('pages.rooms.index');
    }

    private function formatRoomType(RoomType $roomType): array
    {
        $imageIds = $roomType->images ?? [];
        $imageUrls = collect($imageIds)
            ->map(fn($id) => api_asset($id))
            ->filter()
            ->values();

        return [
            'id' => $roomType->id,
            'code' => 'ST-' . str_pad((string) $roomType->id, 3, '0', STR_PAD_LEFT),
            'type' => $roomType->type,
            'name' => $roomType->name,
            'category' => $roomType->category,
            'description' => $roomType->description,
            'size' => $roomType->size,
            'capacity' => $roomType->capacity,
            'bed_type' => $roomType->bed_type,
            'view_type' => $roomType->view_type,
            'rate' => $roomType->rate,
            'rate_weekend' => $roomType->rate_weekend,
            'notes' => $roomType->notes,
            'image' => $imageUrls->first(),
            'images' => $imageUrls->values(),
            'image_ids' => array_values($imageIds),
            'amenities' => $roomType->amenities->pluck('slug')->values(),
            'rooms' => $roomType->rooms->map(fn(Room $room) => [
                'id' => $room->id,
                'room_number' => $room->room_number,
                'floor' => $room->floor,
                'status' => $room->status,
            ])->values(),
            'status' => $this->deriveStatus($roomType->rooms),
        ];
    }

    private function deriveStatus($rooms): string
    {
        if ($rooms->isEmpty()) {
            return 'available';
        }

        if ($rooms->contains('status', 'available')) {
            return 'available';
        }

        if ($rooms->every(fn(Room $room) => $room->status === 'occupied')) {
            return 'occupied';
        }

        return $rooms->first()->status;
    }

    public function storeRoomType(Request $request)
    {
        $validated = $request->validate(
            [
                'type' => 'required|in:standard,signature',
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'description' => 'required|string|max:3000',
                'size' => 'required|integer|min:1',
                'capacity' => 'required|integer|min:1',
                'bed_type' => 'required|string|max:255',
                'view_type' => 'required|string|max:255',
                'rate' => 'required|integer|min:0',
                'rate_weekend' => 'required|integer|min:0',
                'images' => 'nullable|array',
                'images.*' => 'integer|exists:uploads,id',
                'amenities' => 'nullable|array',
                'amenities.*' => 'exists:amenities,slug',
                'notes' => 'nullable|string',
            ],
            [
                'type.required' => 'Please select a room type.',
                'type.in' => 'The selected room type is invalid.',

                'name.required' => 'Room type name is required.',
                'name.max' => 'Room type name cannot exceed 255 characters.',

                'category.required' => 'Please provide a room category.',
                'category.max' => 'Room category cannot exceed 255 characters.',

                'description.required' => 'Please provide a room description.',
                'description.max' => 'Room description cannot exceed 3,000 characters.',

                'size.required' => 'Room size is required.',
                'size.integer' => 'Room size must be a whole number.',
                'size.min' => 'Room size must be at least 1.',

                'capacity.required' => 'Room capacity is required.',
                'capacity.integer' => 'Room capacity must be a whole number.',
                'capacity.min' => 'Room capacity must be at least 1.',

                'bed_type.required' => 'Please specify the bed type.',
                'bed_type.max' => 'Bed type cannot exceed 255 characters.',

                'view_type.required' => 'Please specify the room view.',
                'view_type.max' => 'Room view cannot exceed 255 characters.',

                'rate.required' => 'Weekday room rate is required.',
                'rate.integer' => 'Weekday room rate must be a valid amount.',
                'rate.min' => 'Weekday room rate cannot be negative.',

                'rate_weekend.required' => 'Weekend room rate is required.',
                'rate_weekend.integer' => 'Weekend room rate must be a valid amount.',
                'rate_weekend.min' => 'Weekend room rate cannot be negative.',

                'images.array' => 'Images must be provided as a list.',
                'images.*.integer' => 'Each selected image is invalid.',
                'images.*.exists' => 'One or more selected images could not be found.',

                'amenities.array' => 'Amenities must be provided as a list.',
                'amenities.*.exists' => 'One or more selected amenities could not be found.',

                'notes.string' => 'Notes must be valid text.',
            ]
        );

        try {
            $roomType = RoomType::create(
                collect($validated)
                    ->except('amenities')
                    ->toArray()
            );
            $amenityIds = [];
            if (!empty($validated['amenities'])) {
                $amenityIds = Amenity::whereIn('slug', $validated['amenities'])
                    ->pluck('id')
                    ->toArray();
            }

            $roomType->amenities()->sync($amenityIds);

            return response()->json([
                'success' => true,
                'message' => 'Room type created successfully.',
                'id' => $roomType->id,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create room type.',
            ], 500);
        }
    }

    public function storeRoom(Request $request)
    {
        $validated = $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'room_number' => 'required|string|max:255|unique:rooms,room_number',
            'floor' => 'required|string|max:255',
            'status' => 'required|in:available,occupied,reserved,cleaning,maintenance',
        ]);

        try {
            $room = Room::create($validated);

            if ($request->wantsJson()) {
                return response()->json($room, 201);
            }

            return redirect()->route('rooms.index')->with('success', 'Room created successfully.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to create room.'], 500);
            }

            return redirect()->back()->withInput()->with('error', 'Failed to create room.');
        }
    }

    public function roomTypeDetails($id)
    {
        try {
            $roomType = RoomType::with([
                'amenities',
                'rooms',
            ])->find($id);

            if (!$roomType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Room type not found.',
                    'data' => null,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Room type retrieved successfully.',
                'data' => $roomType,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving the room type.',
                'data' => null,
            ], 500);
        }
    }
}