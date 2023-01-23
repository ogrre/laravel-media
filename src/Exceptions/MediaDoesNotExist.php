<?php

namespace Ogrre\Media\Exceptions;

use InvalidArgumentException;

class MediaDoesNotExist extends InvalidArgumentException
{
    /**
     * @param string $mediaName
     * @return static
     */
    public static function named(string $mediaName): static
    {
        return new static("There is no media named $mediaName.");
    }

    /**
     * @param int $mediaId
     * @return static
     */
    public static function withId(int $mediaId): static
    {
        return new static("There is no media with id $mediaId.");
    }

    /**
     * @param mixed $media
     * @return static
     */
    public static function instanced(mixed $media): static
    {
        return new static("There is no media instanced $media.");
    }
}
