<?php

namespace Ogrre\Media\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ogrre\Media\Exceptions\FileMimeTypeDoesNotMatch;
use Ogrre\Media\Exceptions\MediaDoesNotExist;

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
    protected $table = 'medias';

    /**
     * @return HasMany
     */
    public function mediaFiles(): HasMany
    {
        return $this->hasMany(MediaFile::class);
    }

    /**
     * @param array $attributes
     * @return Model|Builder
     */
    public static function create(array $attributes = []): Model|Builder
    {
        if ($attributes['mime_type']) {
            if (!is_array($attributes['mime_type'])) {
                $mime_types[0] = $attributes['mime_type'];
            } else {
                $mime_types = $attributes['mime_type'];
            }
        }

        $attributes['mime_type'] = $mime_types ?? config('media.attributes.mime_type');
        $attributes['disk'] = $attributes['disk'] ?? config('media.attributes.disk');

        return static::query()->create($attributes);
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function findByName($name): mixed
    {
        $media_type = Media::where('name', $name)->first();

        if (!$media_type) {
            throw MediaDoesNotExist::named($name);
        }

        return $media_type;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function findById($id): mixed
    {
        $media_type = Media::find($id);

        if (!$media_type) {
            throw MediaDoesNotExist::withId($id);
        }

        return $media_type;
    }

    /**
     * @param $mime_type
     * @return void
     */
    public function checkMimeType($mime_type): void
    {
        if (!collect($this->mime_type)->contains(explode("/", $mime_type)[1])) {
            throw FileMimeTypeDoesNotMatch::match($this->name, $mime_type);
        }
    }
}
