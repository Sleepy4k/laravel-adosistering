<?php

namespace Modules\Notification;

use App\Enums\ReportLogType;
use App\Traits\SystemLog;
use Exception;
use Illuminate\Support\Facades\Http;

class FonnteGateway
{
    use SystemLog;

    /**
     * Base URL for Fonnte API
     *
     * @var string
     */
    public string $base_url;

    /**
     * API Token for Fonnte
     *
     * @var string
     */
    public string $token;

    /**
     * Constructor to initialize base URL and token from config
     */
    public function __construct() {
        $this->base_url = config('fonnte.base_url');
        $this->token = config('fonnte.token');
    }

    /**
     * Ping the Fonnte service
     *
     * @param string|null $recipient
     * @return array
     * @throws Exception
     */
    public function ping(string|null $recipient = null)
    {
        if (empty($recipient)) {
            $recipient = config('fonnte.fallback_recipient');
        }

        return $this->sendMessage($recipient, 'PING');
    }

    /**
     * Send a message via Fonnte
     *
     * @param string $recipient
     * @param string $message
     * @param array $additional_param
     * @return array
     * @throws Exception
     */
    public function sendMessage(string $recipient, string $message, array $additional_param = [])
    {
        if (is_array($recipient) && count($recipient) > 0) {
            $recipient = implode(',', $recipient);
        }

        $param = array_merge($additional_param, [
            'target' => $recipient,
            'message' => $message,
        ]);

        return $this->request('/send', $param);
    }

    /**
     * Make a request to the Fonnte API
     *
     * @param string $endpoint
     * @param array $param
     * @return array
     * @throws Exception
     */
    private function request(string $endpoint, array $param = [])
    {
        $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])
            ->withOptions([
                'verify' => false,
            ])
            ->asForm()
            ->accept('application/json')
            ->post($this->base_url . $endpoint, $param);

        if (!$response->ok()) {
            $logparam = $param;
            $logparam['message'] = isset($param['message']) ? (strlen($param['message']) > 20 ? substr($param['message'], 0, 20) . '...' : $param['message']) : null;
            $this->sendReportLog(ReportLogType::ERROR, "Error response from fonnte with status " . $response->status(), [
                'endpoint' => $this->base_url . $endpoint,
                'request' => $logparam,
                'response' => $response->body(),
                'status' => $response->status(),
            ]);

            throw new Exception("Error when connect to fonnte endpoint. Check log for more information");
        }

        $logparam = $param;
        $logparam['message'] = isset($param['message']) ? (strlen($param['message']) > 20 ? substr($param['message'], 0, 20) . '...' : $param['message']) : null;

        $this->sendReportLog(ReportLogType::INFO, "Successful response from fonnte", [
            'endpoint' => $this->base_url . $endpoint,
            'request' => $logparam,
            'response' => $response->body(),
            'status' => $response->status(),
        ]);

        return json_decode($response->body(), true);
    }
}
