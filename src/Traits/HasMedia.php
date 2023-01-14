<?php

namespace Ogrre\Media\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\Testing\MimeType;
use Illuminate\Support\Facades\Storage;
use Ogrre\Media\Exceptions\MediaDoesNotExist;
use Ogrre\Media\MediaRegistrar;
use Ogrre\Media\Models\Media;
use Ogrre\Media\Models\MediaFile;

trait HasMedia
{
    private Media $mediaClass;

    public function getMediaClass()
    {
        if (! isset($this->mediaClass)) {
            $this->mediaClass = app(MediaRegistrar::class)->getMediaClass();
        }

        return $this->mediaClass;
    }

    /**
     * @param array $media_list
     * @return void
     */
    public function assignMedia(array $media_list): void
    {
        $medias = collect($media_list)
            ->flatten()
            ->map(function ($media) {
            return $this->getStoredMedia($media)->id;
        });

        $this->medias()->syncWithoutDetaching($medias);
    }

    /**
     * @param $file
     * @param string|null $media_type
     * @return void
     */
    public function storeMediaFile($file, mixed $media_type): void
    {
        $media = $this->getStoredMedia($media_type);

        //TODO model has media ?

        $path = Storage::disk($media->disk)->put($media->name, $file);

        //TODO file mime_type == media mime_type ?
//        if($media->mime_type != MimeType::from($path)){
//
//            return "mime type pas bpn";
//        }

        $new_media_file = new MediaFile();
        $new_media_file->file_name = $file->getClientOriginalName();
        $new_media_file->path = Storage::url($path);
        $new_media_file->size = $file->getSize();
        $new_media_file->media_type_id = $media->id;

        $this->media_files()->save($new_media_file);
    }

    /**
     * @param mixed $media
     * @return Media
     */
    protected function getStoredMedia(mixed $media): Media
    {
        $mediaClass = $this->getMediaClass();

        if (is_string($media)) {
            return $mediaClass->findByName($media);
        }

        if (is_numeric($media)) {
            return $mediaClass->findById($media);
        }

        if (! $media instanceof Media) {
            throw MediaDoesNotExist::instanced($media);
        }

        return $media;
    }

    public function hasMedia(Media $media_type): void
    {
        // return true if model has media
    }

    /**
     * @return MorphToMany
     */
    public function medias(): MorphToMany
    {
        return $this->morphToMany(Media::class, 'model', 'model_has_medias');
    }

    /**
     * @return MorphMany
     */
    public function media_files(): MorphMany
    {
        return $this->morphMany(MediaFile::class, 'model');
    }
}
