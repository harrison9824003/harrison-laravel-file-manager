<?php

namespace Harrison\LaravelFileManager\Services\Image;

use Harrison\LaravelFileManager\Services\FileDriverAbstract;

class FtpExampleService extends FileDriverAbstract
{
    protected string $filesystemDriver = 'ftp';

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
