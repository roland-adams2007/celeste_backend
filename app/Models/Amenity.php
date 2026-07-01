<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
    ];

    /**
     * Get the room types that have this amenity.
     */
    public function roomTypes()
    {
        return $this->belongsToMany(RoomType::class, 'amenity_room_type', 'amenity_id', 'room_type_id');
    }

    /**
     * Get the room types (rooms) that have this amenity.
     * Alias for roomTypes() for better readability.
     */
    public function rooms()
    {
        return $this->roomTypes();
    }

    /**
     * Scope a query to only include amenities with a specific icon.
     */
    public function scopeWithIcon($query, $icon)
    {
        return $query->where('icon', $icon);
    }

    /**
     * Get the amenity name formatted for display.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name;
    }
}
