<?php

namespace Ogrre\Media\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Media extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'name',
        'mime_type',
        'disk',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'mime_type' => 'array'
    ];

    /**
     * @var string
     */
    protected $table = 'media_types';

    /**
     * @return HasMany
     */
    public function media_files(): HasMany
    {
        return $this->hasMany(MediaFile::class);
    }

    /**
     * @param array $attributes
     * @return Model|Builder
     */
    public static function create(array $attributes = []): Model|Builder
    {
        //TODO: exception error name not null

        $attributes['mime_type'] = $attributes['mime_type'] ?? config('media.mime_type');
        $attributes['disk'] = $attributes['disk'] ?? config('media.disk');

        return static::query()->create($attributes);
    }
}
