<?php

namespace Ogrre\Media;

use Ogrre\Media\Models\Media;

class MediaRegistrar
{
    protected string $mediaClass;

    public function __construct(){

        $this->mediaClass = config('media.models.media');
    }

    /**
     * @return Media
     */
    public function getMediaClass(): Media
    {
        return app($this->mediaClass);
    }
}
