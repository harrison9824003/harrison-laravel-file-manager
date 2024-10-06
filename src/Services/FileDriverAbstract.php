<?php

namespace Harrison\LaravelFileManager\Services;

use Harrison\LaravelFileManager\Models\ValueObjects\FileInfo;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @property string filesystemDriver 儲存 driver 名稱
 * @property Storage storage 儲存物件
 */
abstract class FileDriverAbstract
{
    private Filesystem $filesystem;

    protected string $filesystemDriver = '';

    public function getFilesystemDriver(): string
    {
        return $this->filesystemDriver;
    }

    public function __construct()
    {
        if (empty($this->filesystemDriver)) {
            throw new \Exception('Filesystem driver is required');
        }
        $this->filesystem = Storage::disk($this->filesystemDriver);
    }

    /**
     * 上傳目錄建立規則
     */
    abstract public function getUploadPath(): string;

    /**
     * 檔案名稱建立規則
     * 不想要自動產生檔案名稱時回傳 null
     */
    abstract public function generateFileName(): ?string;

    /**
     * 檔案 uuid 產生規則
     */
    public function generateUuid(): string
    {
        return Str::uuid();
    }

    /**
     * 上傳檔案
     * @param UploadedFile $file 檔案物件
     * @param string $saveFileName 檔案名稱
     */
    public function uploadFile(UploadedFile $file, ?string $realSaveFileName = null): FileInfo
    {
        $savePath = $this->getDirPath();
        $saveFileName = $realSaveFileName ?? ( $this->generateFileName() ?? $file->getClientOriginalName() );
        $saveFileName .= '.' . $file->extension();

        try {
            // 檔案上傳
            $this->filesystem->putFileAs(
                $savePath,
                $file,
                $saveFileName
            );
        } catch (\Exception $e) {
            // todo custom exception
            throw new \Exception($e->getMessage());
        }

        return FileInfo::createByUploadedFile(
            $file,
            $this->filesystemDriver,
            $saveFileName,
            $savePath
        );
    }

    /**
     * 讀取檔案直接回傳 response
     */
    public function readFile(FileInfo $fileInfo): string
    {
        try {
            return $this->filesystem->get($fileInfo->getSavePath());
        } catch (\Exception $e) {
            // todo custom exception
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 刪除檔案
     */
    public function deleteFile(FileInfo $fileInfo): bool
    {
        try {
            $this->filesystem->delete($fileInfo->getSavePath());
        } catch (\Exception $e) {
            // todo custom exception
            throw new \Exception($e->getMessage());
        }

        return true;
    }

    /**
     * 取得檔案目錄
     */
    public function getDirPath(): string
    {
        // 取得 storage root path
        $dirPath = $this->getUploadPath();

        try {
            // 沒有對應目錄新增目錄
            if ( !$this->filesystem->exists($dirPath) ){
                $this->filesystem->makeDirectory($dirPath);
            }
        } catch (\Exception $e) {
            // todo custom exception
            throw new \Exception($e->getMessage());
        }

        return $dirPath;
    }
}
