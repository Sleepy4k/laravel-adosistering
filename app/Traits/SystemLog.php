<?php

namespace App\Traits;

use App\Enums\ReportLogType;
use Illuminate\Support\Facades\Log;

trait SystemLog
{
    /**
     * Send report to system log
     *
     * @param ReportLogType $type
     * @param string $message
     * @param array $context
     *
     * @return bool
     */
    protected function sendReportLog(ReportLogType $type, string $message, array $context = []): bool
    {
        try {
            match ($type) {
                ReportLogType::DEBUG => Log::debug($message, $context),
                ReportLogType::ERROR => Log::error($message, $context),
                ReportLogType::ALERT => Log::alert($message, $context),
                ReportLogType::INFO => Log::info($message, $context),
                ReportLogType::WARNING => Log::warning($message, $context),
                default => Log::info($message, $context),
            };

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
