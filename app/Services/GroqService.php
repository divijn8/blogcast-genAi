<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    protected $apiKey;
    protected $primaryModel = 'llama-3.3-70b-versatile';
    protected $backupModel  = 'llama-3.1-8b-instant';

    public function __construct()
    {
        $this->apiKey = env('GROQ_API_KEY');
    }

    /**
     * Send a prompt to Groq. Handles fallbacks and JSON parsing automatically.
     */
    public function ask(string $systemPrompt, string $userPrompt, bool $expectJson = false): array|string|null
    {
        $result = $this->executeApiCall($this->primaryModel, $systemPrompt, $userPrompt, $expectJson);

        if ($result === null) {
            Log::warning("Groq Primary ({$this->primaryModel}) failed. Switching to backup: {$this->backupModel}");
            $result = $this->executeApiCall($this->backupModel, $systemPrompt, $userPrompt, $expectJson);
        }

        return $result ?? ($expectJson ? [] : '');
    }
    /**
     * Low-level API execution
     */
    private function executeApiCall(string $model, string $system, string $user, bool $json): array|string|null
    {
        $url = "https://api.groq.com/openai/v1/chat/completions";

        $body = [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user', 'content' => $user]
            ],
            'temperature' => 0.6,
        ];

        if ($json) {
            $body['response_format'] = ['type' => 'json_object'];
        }

        $response = Http::withToken($this->apiKey)->post($url, $body);

        if ($response->failed()) {
            Log::error("Groq API Error ({$model}): " . $response->body());
            return null;
        }

        $content = $response->json()['choices'][0]['message']['content'] ?? '';

        if ($json) {
            return $this->cleanAndParseJson($content);
        }

        return $content;
    }

    private function cleanAndParseJson(string $rawContent): ?array
    {
        // Extract JSON structure if wrapped in text
        if (preg_match('/\{[\s\S]*\}/', $rawContent, $matches)) {
            $rawContent = $matches[0];
        }

        $data = json_decode($rawContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("Groq JSON Parse Error: " . json_last_error_msg());
            return null;
        }

        return $data;
    }
}
