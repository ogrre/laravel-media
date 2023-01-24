<?php

namespace Ogrre\Media\Exceptions;

use InvalidArgumentException;

class ModelAlreadyHasMediaFile extends InvalidArgumentException
{
    /**
     * @param string $mediaFileName
     * @return static
     */
    public static function named(string $mediaFileName): static
    {
        return new static("This model already has a file named $mediaFileName.");
    }
}
