<?php

namespace Modules\Storage;

use App\Enums\ReportLogType;
use App\Enums\UploadFileType;
use App\Traits\SystemLog;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class FileManager
{
    use SystemLog;

    /**
     * Set path root when unkown file.
     *
     * @var string
     */
    private static string $unkownPath = 'unkown';

    /**
     * Set path for storage when upload file.
     *
     * @param UploadFileType $type
     *
     * @return string
     */
    private function storageDisk(UploadFileType $type = UploadFileType::IMAGE)
    {
        try {
            $types = UploadFileType::toArray();
            $path = in_array($type->value, $types) ? $type->value : self::$unkownPath;
            return rtrim($path, '/') . '/';
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            throw $th;
        }
    }

    /**
     * Transform name file
     *
     * @param UploadFileType $type
     * @param string $file
     *
     * @return string
     */
    private function transformName(UploadFileType $type, $file)
    {
        try {
            $types = UploadFileType::toArray();
            $baseUrl = request()->getSchemeAndHttpHost() . '/storage';
            $path = in_array($type->value, $types) ? $type->value : self::$unkownPath;

            return $baseUrl . '/' . $path . '/' . $file;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            throw $th;
        }
    }

    /**
     * Parse image name
     *
     * @param string $file
     *
     * @return string
     */
    private function parseImage($file)
    {
        try {
            $parsedUrl = parse_url($file);
            return basename($parsedUrl['path'] ?? '');
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            throw $th;
        }
    }

    /**
     * Save file in storage app
     *
     * @param UploadFileType $type
     * @param UploadedFile $file
     *
     * @return string
     */
    private function putFile(UploadFileType $type, $file)
    {
        try {
            $user = auth('web')->user();
            $fileExtension = $file->getClientOriginalExtension();
            $clientCode = $user ? $user->id . '_' . $user->created_at->format('dmY') : rand(1, 999) . '_' . date('His');
            $fileName = preg_replace('/\s+/', '_', uniqid() . '_' . date('dmY') . '_' . $clientCode . '.' . $fileExtension);

            if ($this->checkFile($type, $fileName, true)) {
                return $this->putFile($type, $file);
            }

            $directory = $this->storageDisk($type);

            if (config('filesystems.default') === 'public') {
                $path = rtrim($directory, '/');
                $segments = explode('/', $path);
                $currentPath = '';

                foreach ($segments as $segment) {
                    $currentPath = ltrim($currentPath . '/' . $segment, '/');

                    if (!Storage::exists($currentPath)) {
                        Storage::makeDirectory($currentPath);
                    }

                    $indexFilePath = $currentPath . '/index.html';

                    if (!Storage::exists($indexFilePath)) {
                        Storage::put($indexFilePath, Storage::disk('public')->get('index.html'));
                    }
                }
            } else {
                if (!Storage::exists(rtrim($directory, '/'))) {
                    Storage::makeDirectory(rtrim($directory, '/'));
                }
            }

            if (in_array($fileExtension, ['jpg', 'jpeg', 'webp'])) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file)->toWebp(5);
                $fileName = pathinfo($fileName, PATHINFO_FILENAME) . '.webp';
                $storagePath = Storage::disk(config('filesystems.default'))->path($directory . $fileName);
                $image->save($storagePath);
            } else {
                $file->storeAs($this->storageDisk($type), $fileName);
            }

            return $this->transformName($type, $fileName);
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            throw $th;
        }
    }

    /**
     * Delete file in storage app
     *
     * @param UploadFileType $type
     * @param string $file
     *
     * @return bool
     */
    public function deleteFile(UploadFileType $type, $file)
    {
        try {
            if (!$this->checkFile($type, $file)) return false;

            $parsedFile = $this->parseImage($file);
            Storage::delete($this->storageDisk($type) . $parsedFile);

            return true;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            throw $th;
        }
    }

    /**
     * Check file in storage app
     *
     * @param UploadFileType $type
     * @param string $file
     * @param bool $save
     *
     * @return bool
     */
    public function checkFile(UploadFileType $type, $file, $save = false)
    {
        try {
            $parsedFile = $save ? $file : $this->parseImage($file);

            return Storage::exists($this->storageDisk($type) . $parsedFile);
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            throw $th;
        }
    }

    /**
     * Save single file to storage app
     *
     * @param UploadFileType $type
     * @param UploadedFile $file
     *
     * @return string|null
     */
    public function saveSingleFile(UploadFileType $type, $file): string|null
    {
        try {
            if (is_null($file)) return null;

            return $this->putFile($type, $file);
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            throw $th;
        }
    }

    /**
     * Update old file with the new one
     *
     * @param UploadFileType $type
     * @param UploadedFile $file
     * @param string $old_file
     *
     * @return string|null
     */
    public function updateSingleFile(UploadFileType $type, $file, $old_file): string|null
    {
        try {
            if (is_null($file)) return null;

            if (!$this->checkFile($type, $old_file)) {
                return $this->putFile($type, $file);
            }

            $this->deleteFile($type, $old_file);

            return $this->updateSingleFile($type, $file, $old_file);
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            throw $th;
        }
    }

    /**
     * Get file size in storage app
     *
     * @param UploadFileType $type
     * @param string $file
     *
     * @return int|null
     */
    public function getFileSize(UploadFileType $type, $file): int|null
    {
        try {
            if (!$this->checkFile($type, $file)) return null;

            $parsedFile = $this->parseImage($file);

            return Storage::size($this->storageDisk($type) . $parsedFile);
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            throw $th;
        }
    }

    /**
     * Get file type in storage app
     *
     * @param UploadFileType $type
     * @param string $file
     *
     * @return string|null
     */
    public function getFileType(UploadFileType $type, $file): string|null
    {
        try {
            if (!$this->checkFile($type, $file)) return null;

            $parsedFile = $this->parseImage($file);

            return Storage::mimeType($this->storageDisk($type) . $parsedFile);
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            throw $th;
        }
    }

    /**
     * Get file path in storage app
     *
     * @param UploadFileType $type
     * @param string $file
     *
     * @return string|null
     */
    public function getFilePath(UploadFileType $type, $file): string|null
    {
        try {
            if (!$this->checkFile($type, $file)) return null;

            $parsedFile = $this->parseImage($file);

            return $this->storageDisk($type) . $parsedFile;
        } catch (\Throwable $th) {
            $this->sendReportLog(ReportLogType::ERROR, $th->getMessage());
            throw $th;
        }
    }
}
