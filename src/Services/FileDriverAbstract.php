<?php

namespace Harrison\LaravelFileManager\Services;

use Harrison\LaravelFileManager\Models\ValueObjects\FileInfo;
use Illuminate\Contracts\Filesystem\Filesystem;
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

    protected string $subDir = '';

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
     * @param array $folder 子目錄名稱
     */
    abstract public function getUploadPath(): string;

    /**
     * 檔案名稱建立規則
     * 不想要自動產生檔案名稱時回傳 null
     */
    abstract public function generateFileName(): ?string;

    /**
     * 設定子目錄規則
     */
    public function setSubDir(array $subDir = []): void
    {
        $this->subDir = implode('/', $subDir);
    }

    /**
     * 取回子目錄
     */
    public function getSubDir(): string 
    {
        return $this->subDir;
    }

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
        // 取得上傳檔案 root path
        $dirPath = $this->getUploadPath();

        // 加上子目錄
        if (!empty($this->getSubDir())) {
            $dirPath .= '/' . $this->getSubDir();
        }

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
