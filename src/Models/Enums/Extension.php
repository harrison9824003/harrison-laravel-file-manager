<?php

namespace Harrison\LaravelFileManager\Models\Enums;

enum Extension: string {
    case JPEG = 'jpeg';
    case JPG = 'jpg';
    case PNG = 'png';
    case GIF = 'gif';
    case WEBP = 'webp';
    case SVG = 'svg';
    case BMP = 'bmp';
    case TIFF = 'tiff';

    /**
     * 檢查是否為圖片類型
     */
    public static function isImage(string $extension): bool
    {
        return in_array($extension, [
            self::JPEG,
            self::JPG,
            self::PNG,
            self::GIF,
            self::WEBP,
            self::SVG,
            self::BMP,
            self::TIFF,
        ]);
    }
}