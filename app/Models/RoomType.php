<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;



class RoomType extends Model
{
    use HasFactory, HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'name',
        'slug',
        'category',
        'description',
        'size',
        'capacity',
        'bed_type',      // e.g., "King", "Queen"
        'view_type',     // e.g., "City view", "Pool view"
        'rate',          // regular night rate
        'rate_weekend',  // weekend rate
        'images',        // JSON array of image URLs
        'notes',         // internal notes
    ];

    // Optional: Add casts for JSON fields
    protected $casts = [
        'images' => 'array',
        'rate' => 'integer',
        'rate_weekend' => 'integer',
        'size' => 'integer',
        'capacity' => 'integer',
    ];

    /**
     * Get the amenities for this room type.
     */
    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'amenity_room_type', 'room_type_id', 'amenity_id');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Check if this is a signature suite (one-of-a-kind).
     */
    public function isSignature(): bool
    {
        return $this->type === 'signature';
    }

    /**
     * Check if this is a standard suite (multiple units).
     */
    public function isStandard(): bool
    {
        return $this->type === 'standard';
    }

    /**
     * Check if rooms of this type are available.
     */
    public function isAvailable(): bool
    {
        if ($this->isSignature()) {
            return $this->status === 'available';
        }

        return $this->units_avail > 0 && $this->status === 'available';
    }

    /**
     * Get the formatted rate.
     */
    public function getFormattedRateAttribute(): string
    {
        return '₦' . number_format($this->rate);
    }

    /**
     * Get the formatted weekend rate.
     */
    public function getFormattedWeekendRateAttribute(): string
    {
        return '₦' . number_format($this->rate_wknd);
    }

    /**
     * Get the occupancy rate (for standard types only).
     */
    public function getOccupancyRateAttribute(): ?float
    {
        if ($this->isSignature() || !$this->units || $this->units === 0) {
            return null;
        }

        $occupied = $this->units - ($this->units_avail ?? 0);
        return ($occupied / $this->units) * 100;
    }

    /**
     * Get the total number of rooms of this type.
     */
    public function getTotalUnitsAttribute(): int
    {
        return $this->units ?? 0;
    }

    /**
     * Get the number of occupied rooms.
     */
    public function getOccupiedUnitsAttribute(): int
    {
        if ($this->isSignature()) {
            return $this->status === 'occupied' ? 1 : 0;
        }

        return ($this->units ?? 0) - ($this->units_avail ?? 0);
    }

    /**
     * Scope a query to only include available room types.
     */
    public function scopeAvailable($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'available')
                ->where(function ($sub) {
                    $sub->where('type', 'signature')
                        ->orWhere('units_avail', '>', 0);
                });
        });
    }

    /**
     * Scope a query to only include signature suites.
     */
    public function scopeSignature($query)
    {
        return $query->where('type', 'signature');
    }

    /**
     * Scope a query to only include standard room types.
     */
    public function scopeStandard($query)
    {
        return $query->where('type', 'standard');
    }

    /**
     * Get all amenities as a comma-separated string.
     */
    public function getAmenitiesListAttribute(): string
    {
        return $this->amenities->pluck('name')->implode(', ');
    }

    /**
     * Check if this room type has a specific amenity.
     */
    public function hasAmenity(string $amenityName): bool
    {
        return $this->amenities->contains('name', $amenityName);
    }


    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
