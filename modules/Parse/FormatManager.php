<?php

namespace Modules\Parse;

use Illuminate\Support\Carbon;

class FormatManager
{
    /**
     * The size units.
     *
     * @var array
     */
    protected static array $sizeUnits = ['B', 'KB', 'MB', 'GB', 'TB'];

    /**
     * The image file extensions.
     *
     * @var array
     */
    protected static array $imageExtensions = [
        'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'tiff', 'ico',
    ];

    /**
     * The file extensions.
     *
     * @var array
     */
    protected static array $fileExtensions = [
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'odt', 'ods', 'odp', 'rtf', 'txt', 'csv',
        'zip', 'rar', '7z', 'tar', 'gz',
    ];

    /**
     * Format the file size.
     *
     * @param int|float $bytes
     * @param int $precision
     *
     * @return string
     */
    public function formatFileSize(int|float $bytes, int $precision = 2): string
    {
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count(static::$sizeUnits) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision).' '.static::$sizeUnits[$pow];
    }

    /**
     * Format the number.
     *
     * @param int|float $number
     * @param int $precision
     *
     * @return string
     */
    public function formatNumber(int|float $number, int $precision = 2): string
    {
        return number_format($number, $precision);
    }

    /**
     * Format the date.
     *
     * @param string $date
     * @param string $format
     *
     * @return string
     */
    public function formatDate(string $date, string $format = 'd-m-Y'): string
    {
        return Carbon::parse($date)->format($format);
    }

    /**
     * Get the file upload types.
     *
     * @return array
     */
    public function getFileUploadTypes(): array
    {
        return [
            'image' => 'Image',
            'file' => 'File',
        ];
    }

    /**
     * Get the file extensions.
     *
     * @return array
     */
    public function getFileExtensions(): array
    {
        $fileTypes = array_merge(
            static::$imageExtensions,
            static::$fileExtensions
        );
        $content = array_map('strtoupper', $fileTypes);
        return array_combine($fileTypes, $content);
    }

    /**
     * Get the image file extensions.
     *
     * @return array
     */
    public function getImageExtensions(): array
    {
        return array_combine(
            static::$imageExtensions,
            array_map('strtoupper', static::$imageExtensions)
        );
    }

    /**
     * Get the server's maximum upload size in bytes.
     *
     * @return int
     */
    public function getServerMaxUploadSize(): int
    {
        $serverMaxUploadSize = ini_get('upload_max_filesize');
        $serverMaxPostSize = ini_get('post_max_size');
        $serverThreshold = max(
            $serverMaxUploadSize,
            $serverMaxPostSize
        );
        return (int) preg_replace('/[^0-9]/', '', $serverThreshold) * 1024;
    }

    /**
     * Get the maximum upload size options.
     *
     * @param int $serverThreshold
     * @param int $step
     *
     * @return array
     */
    public function uploadSizeOptions(int $serverThreshold, int $step = 1024): array
    {
        $maxSizeOptions = [];
        foreach (range(1024, $serverThreshold, $step) as $size) {
            $maxSizeOptions[$size] = $size . ' KB';
        }

        return $maxSizeOptions;
    }
}
