<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    // Use UUID as primary key if you are using uuid in migration
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', // Make sure 'id' is fillable if you're manually setting UUIDs or using UUID trait
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'is_dismissed',
        'tag',
        'tag_color',
        'priority',
        'link',
        'remind_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'is_dismissed' => 'boolean',
        'remind_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Optional: Override the boot method to generate UUIDs automatically if not using a trait
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid();
        });
    }
}