<?php

namespace App\Domain\Location\Models;

use Database\Factories\LocationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Domain\Forecast\Models\Forecast;
use App\Domain\User\Models\User;

class Location extends Model
{
    /** @use HasFactory<\Database\Factories\LocationFactory> */
    use HasFactory;

    protected $table = 'locations';

    public $timestamps = false;

    protected $fillable = [
        'city',
        'state',
        'user_id',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Database\Factories\LocationFactory
     */
    public static function newFactory(): LocationFactory
    {
        return LocationFactory::new();
    }

    /**
     * Many-to-Many relationship with the User model.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * One-to-Many relationship with the Forecast model.
     *
     * @return HasMany
     */
    public function forecasts(): HasMany
    {
        return $this->hasMany(Forecast::class);
    }
}
