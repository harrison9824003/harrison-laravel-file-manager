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
}