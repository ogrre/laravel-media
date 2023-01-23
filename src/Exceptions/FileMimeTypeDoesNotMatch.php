<?php

namespace Ogrre\Media\Exceptions;

use InvalidArgumentException;

class FileMimeTypeDoesNotMatch extends InvalidArgumentException
{
    /**
     * @param string $media_name
     * @param mixed $mime_type
     * @return static
     */
    public static function match(string $media_name, mixed $mime_type): static
    {
        return new static("The media $media_name mime type does not match with this mime type : $mime_type.");
    }
}
