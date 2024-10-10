<?php

namespace Harrison\LaravelFileManager\Services;

use Illuminate\Support\Facades\Storage;

class FileManagerService
{
    public function __construct() {}

    /**
     * 移動檔案到不同 driver
     */
    public function moveFile(string $fromDriver, string $toDriver, string $fromPath, string $toPath): bool
    {
        // 檢查檔案是否存在
        if (!Storage::disk($fromDriver)->exists($fromPath)) {
            return false;
        }

        // 取得檔案
        $file = Storage::disk($fromDriver)->get($fromPath);

        // 儲存檔案
        Storage::disk($toDriver)->put($toPath, $file);
    }
}
