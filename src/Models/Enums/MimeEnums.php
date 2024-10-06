<?php

namespace Harrison\LaravelFileManager\Models\Enums;

enum MimeEnums: string {
    case JPEG = 'image/jpeg';
    case PNG = 'image/png';
    case GIF = 'image/gif';
    case WEBP = 'image/webp';
    case SVG_XML = 'image/svg+xml';
    case BMP = 'image/bmp';
    case TIFF = 'image/tiff';

    /**
     * 使用副檔名取得對應 mime
     */
    public static function getMimeByExtension(Extension $extension): MimeEnums
    {
        return match ($extension) {
            Extension::JPEG => self::JPEG,
            Extension::JPG => self::JPEG,
            Extension::PNG => self::PNG,
            Extension::GIF => self::GIF,
            Extension::WEBP => self::WEBP,
            Extension::SVG => self::SVG_XML,
            Extension::BMP => self::BMP,
            Extension::TIFF => self::TIFF,
            default => '',
        };
    }

    /**
     * 檢查是否為圖片類型
     */
    public static function isImage(string $mime): bool
    {
        return in_array($mime, [
            self::JPEG,
            self::PNG,
            self::GIF,
            self::WEBP,
            self::SVG_XML,
            self::BMP,
            self::TIFF,
        ]);
    }
}