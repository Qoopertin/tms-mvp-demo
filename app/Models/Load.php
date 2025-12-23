<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Load extends Model
{
    use HasFactory;

    const STATUS_CREATED = 'created';
    const STATUS_ASSIGNED = 'assigned';
    const STATUS_IN_TRANSIT = 'in_transit';
    const STATUS_DELIVERED = 'delivered';

    protected $fillable = [
        'reference_no',
        'pickup_address',
        'pickup_lat',
        'pickup_lng',
        'delivery_address',
        'delivery_lat',
        'delivery_lng',
        'status',
        'assigned_driver_id',
    ];

    protected $casts = [
        'pickup_lat' => 'decimal:7',
        'pickup_lng' => 'decimal:7',
        'delivery_lat' => 'decimal:7',
        'delivery_lng' => 'decimal:7',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_driver_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(LoadDocument::class);
    }

    public function breadcrumbs(): HasMany
    {
        return $this->hasMany(DriverBreadcrumb::class);
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_CREATED => 'gray',
            self::STATUS_ASSIGNED => 'blue',
            self::STATUS_IN_TRANSIT => 'yellow',
            self::STATUS_DELIVERED => 'green',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_CREATED => 'Created',
            self::STATUS_ASSIGNED => 'Assigned',
            self::STATUS_IN_TRANSIT => 'In Transit',
            self::STATUS_DELIVERED => 'Delivered',
            default => 'Unknown',
        };
    }
}
