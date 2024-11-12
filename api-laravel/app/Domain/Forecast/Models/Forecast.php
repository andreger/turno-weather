<?php

namespace App\Domain\Forecast\Models;

use App\Domain\Location\Models\Location;
use Database\Factories\ForecastFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Forecast extends Model
{

    /** @use HasFactory<\Database\Factories\ForecastFactory> */
    use HasFactory;
    public $timestamps = false;

    protected $table = 'forecasts';

    protected $fillable = [
        'temperature',
        'description',
        'icon',
        'condition_at',
        'location_id',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Database\Factories\ForecastFactory
     */
    public static function newFactory(): ForecastFactory
    {
        return ForecastFactory::new();
    }

    /**
     * Get the location associated with the forecast.
     *
     * @return BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
