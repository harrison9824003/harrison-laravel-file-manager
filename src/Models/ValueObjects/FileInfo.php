<?php

namespace Harrison\LaravelFileManager\Models\ValueObjects;

use Harrison\LaravelFileManager\Models\Enums\Extension;
use Harrison\LaravelFileManager\Models\Enums\MimeEnums;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * @property string uuid 檔案 uuid
 * @property string driver 儲存 driver 名稱
 * @property string originalName 檔案原始名稱
 * @property string tempPath 暫存檔案路徑
 * @property MimeEnums mimeType 檔案類型
 * @property Extension extension 檔案副檔名
 * @property string saveFileName 儲存檔案名稱
 * @property string savePath 儲存檔案路徑
 */
class FileInfo
{
    public function __construct(
        private string $uuid,
        private string $driver,
        private string $originalName,
        private string $tempPath,
        private MimeEnums $mimeType,
        private Extension $extension,
        private string $saveFileName = '',
        private string $savePath = '',
    )
    {
    }

    /**
     * 使用 Illuminate\Http\UploadedFile 產生檔案物件
     */
    public static function createByUploadedFile(
        UploadedFile $file,
        string $driver,
        string $saveFileName,
        string $savePath
    ): static
    {
        /**
         * @var Extension $extension
         */
        $extension = Extension::from($file->extension());
        /**
         * @var MimeEnums $mimeType
         */
        $mimeType = MimeEnums::getMimeByExtension($extension);

        return new static(
            uuid: Str::uuid(),
            driver: $driver,
            originalName: $file->getClientOriginalName(),
            tempPath: $file->getRealPath(),
            mimeType: $mimeType,
            extension: $extension,
            saveFileName: $saveFileName,
            savePath: $savePath
        );
    }

    /**
     * 取得檔案 uuid
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * 取得檔案原始名稱
     */
    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    /**
     * 取得暫存檔案路徑
     */
    public function getTempPath(): string
    {
        return $this->tempPath;
    }

    /**
     * 取得檔案類型
     */
    public function getMimeType(): MimeEnums
    {
        return $this->mimeType;
    }

    /**
     * 取得檔案副檔名
     */
    public function getExtension(): Extension
    {
        return $this->extension;
    }

    /**
     * 取得儲存檔案名稱
     */
    public function getSaveFileName(): string
    {
        return $this->saveFileName;
    }

    /**
     * 取得儲存檔案路徑
     */
    public function getSavePath(): string
    {
        return $this->savePath;
    }
}
