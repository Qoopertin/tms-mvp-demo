<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoadDocument extends Model
{
    use HasFactory;

    const TYPE_POD = 'POD';
    const TYPE_PHOTO = 'PHOTO';
    const TYPE_OTHER = 'OTHER';

    protected $fillable = [
        'load_id',
        'type',
        'filename',
        'path',
        'mime_type',
        'size',
        'uploaded_by',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function load(): BelongsTo
    {
        return $this->belongsTo(Load::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }

    public function getTypeBadgeColorAttribute(): string
    {
        return match($this->type) {
            self::TYPE_POD => 'green',
            self::TYPE_PHOTO => 'blue',
            self::TYPE_OTHER => 'gray',
            default => 'gray',
        };
    }
}
