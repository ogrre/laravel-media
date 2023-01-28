<?php

namespace Ogrre\Media\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\Testing\MimeType;
use Illuminate\Support\Facades\Storage;
use Ogrre\Media\Exceptions\MediaDoesNotExist;
use Ogrre\Media\Exceptions\MediaDoesNotAssignedToThisModel;
use Ogrre\Media\Exceptions\ModelAlreadyHasMediaFile;
use Ogrre\Media\MediaRegistrar;
use Ogrre\Media\Models\Media;
use Ogrre\Media\Models\MediaFile;

trait HasMedia
{
    private Media $mediaClass;

    private function getMediaClass()
    {
        if (!isset($this->mediaClass)) {
            $this->mediaClass = app(MediaRegistrar::class)->getMediaClass();
        }

        return $this->mediaClass;
    }

    /**
     * @param Media $media
     * @return void
     */
    public function hasMedia(Media $media): void
    {
        if(!$this->medias->contains($media)){
            throw MediaDoesNotAssignedToThisModel::assigned($media);
        }
    }

    /**
     * @return MorphToMany
     */
    public function medias(): MorphToMany
    {
        return $this->morphToMany(Media::class, 'model', 'model_has_medias');
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

        if (!$media instanceof Media) {
            throw MediaDoesNotExist::instanced($media);
        }

        return $media;
    }

    /**
     * @return MorphMany
     */
    private function mediaFiles(): MorphMany
    {
        return $this->morphMany(MediaFile::class, 'model');
    }

    /**
     * @param $file
     * @param string|null $media_ref
     * @return void
     */
    public function addMediaFile($file, mixed $media_ref): void
    {
        $media = $this->getStoredMedia($media_ref);

        $this->hasMedia($media);

        if($mediaFile = $this->getMediaFile($media)){
            throw ModelAlreadyHasMediaFile::named($mediaFile->file_name);
        }

        $this->storeMediaFile($file, $media);
    }

    /**
     * @param mixed $media_ref
     * @return Model|MorphMany|null
     */
    public function getMediaFile(mixed $media_ref): Model|MorphMany|null
    {
        $media = $this->getStoredMedia($media_ref);

        $this->hasMedia($media);

        return $this->mediaFiles()
            ->where('media_id', $media->id)
            ->first();
    }

    /**
     * @param $file
     * @param Media $media
     * @return void
     */
    private function storeMediaFile($file, Media $media): void
    {
        $media->checkMimeType($file->getClientMimeType());

        $upload_file = Storage::disk($media->disk)->put($media->name, $file);

        $this->mediaFiles()->save(MediaFile::create([
            'name' => $file->getClientOriginalName(),
            'storage' => $upload_file,
            'url' => Storage::url($upload_file),
            'size' => $file->getSize(),
            'media_id' => $media->id
        ]));
    }

    /**
     * @param $file
     * @param mixed $media_ref
     * @return void
     */
    public function updateMediaFile($file, mixed $media_ref): void
    {
        $media = $this->getStoredMedia($media_ref);

        $media->checkMimeType($file->getClientMimeType());

        $this->deleteMediaFile($media);

        $this->storeMediaFile($file, $media);
    }

    /**
     * @param mixed $media_ref
     * @return void
     */
    public function deleteMediaFile(mixed $media_ref): void
    {
        $media = $this->getStoredMedia($media_ref);

        $mediaFile = $this->getMediaFile($media);

        Storage::disk($media->disk)->delete($mediaFile->storage);

        $mediaFile->delete();
    }
}
