<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverBreadcrumb extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'load_id',
        'user_id',
        'lat',
        'lng',
        'captured_at',
    ];

    protected $casts = [
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
        'captured_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function load(): BelongsTo
    {
        return $this->belongsTo(Load::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
