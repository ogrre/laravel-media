<?php

namespace Ogrre\Media\Exceptions;

use InvalidArgumentException;
use Ogrre\Media\Models\Media;

class MediaDoesNotAssignedToThisModel extends InvalidArgumentException
{
    /**
     * @param Media $media
     * @return static
     */
    public static function check(Media $media): static
    {
        return new static("$media->name is not assigned to this model");
    }
}
