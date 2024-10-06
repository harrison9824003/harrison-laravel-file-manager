<?php

namespace Harrison\LaravelFileManager\Services\Image;

use Harrison\LaravelFileManager\Services\FileDriverAbstract;

class ImageExampleService extends FileDriverAbstract
{
    protected string $filesystemDriver = 'local';

    public function getUploadPath(): string
    {
        return '/images';
    }

    public function generateFileName(): ?string
    {
        return $this->generateUuid();
        // return null;
    }
}
