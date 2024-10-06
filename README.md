# harrison-laravel-file-manager

## 基本使用繼承 class 實現相關 function
```php
    # 繼承 FileDriverAbstract
    class ImageExampleService extends FileDriverAbstract
    {
        # 設定使用的 driver 對應 config/filesystems.php
        protected string $filesystemDriver = 'local';

        # 設定檔案目錄
        # 上傳目錄 config/filesystems.php 設定子目錄 + getUploadPath() + getSubDir()
        # 使用 getSubDir() 須先使用 setSubDir() 設定子目錄
        public function getUploadPath(): string
        {
            return '/images';
        }

        # 設定自訂檔案名稱
        # 預設取檔名方式，自定檔名 > 使用 generateFileName() > 使用上傳檔案名稱
        public function generateFileName(): ?string
        {
            return $this->generateUuid();
        }
    }
```

## 子目錄設定
```php
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            # 接續『基本使用繼承』設定預設目錄為 /images 下，另外在設定子目錄 /test
            $this->imageExampleService->setSubDir(['test']);
            # 上傳檔案
            $this->imageExampleService->uploadFile($request->file('file'));
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
```