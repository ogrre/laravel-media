<?php

namespace Ogrre\Media\Traits;

use App\Models\MediaRegistrar;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Testing\MimeType;
use Illuminate\Support\Facades\Storage;
use Ogrre\Media\Models\Media;
use Ogrre\Media\Models\MediaFile;

trait HasMedia
{
    /**
     * @param $file
     * @param string|null $media_type
     * @return void
     */
    public function storeMedia($file, ?string $media_type): void
    {
        //TODO erreur et savoir si $mediatype est null utiliser la fonction de media par déaut selon la config

        $media = $this->getStoredMedia($media_type);

        $path = Storage::disk($media->disk)->put($media->name, $file);

//        if($media->mime_type != MimeType::from($path)){
//
//            //TODO gérer les erreurs
//
//            return "mime type pas bpn";
//        }

        $new_media_file = MediaFile::create([
            'file_name' => $file->getClientOriginalName(),
            'path' => Storage::url($path),
            'size' => $file->getSize(),
            'media_type_id' => $media->id
        ]);

        $this->medias()->save($new_media_file);
    }

    /**
     * @param $media_type
     * @return Media
     */
    protected function getStoredMedia($media_type): Media
    {
        if (is_numeric($media_type)) {
            return Media::find($media_type);
        }

        if (is_string($media_type)) {
            return Media::where('name', $media_type)->first();
        }

        //TODO créer l'erreur ou renvoyer un media par défaut créer avec une fonction et qu ise base sur la config1
        return Media::find(1);
    }

    /**
     * @return MorphMany
     */
    public function media_files(): MorphMany
    {
        return $this->morphMany(MediaFile::class, 'model');
    }
}
