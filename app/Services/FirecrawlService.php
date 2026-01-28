<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirecrawlService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.firecrawl.dev/v0';

    public function __construct()
    {
        $this->apiKey = env('FIRECRAWL_API_KEY');
    }

    /**
     * Search the web and return a list of URLs.
     */
    public function search(string $query, int $limit = 5): array
    {
        $response = Http::withToken($this->apiKey)
            ->post("{$this->baseUrl}/search", [
                'query'       => $query,
                'limit'       => $limit,
                'pageOptions' => ['fetchPageContent' => false],
            ]);

        if ($response->failed()) {
            Log::error("Firecrawl Search Failed: " . $response->body());
            return [];
        }

        $data = $response->json();
        return array_column($data['data'] ?? [], 'url');
    }

    /**
     * Scrape a single URL and return markdown.
     */
    public function scrape(string $url): ?array
    {
        // Validation: Skip non-text files
        if (preg_match('/\.(pdf|xml|json|jpg|png|mp4)$/i', $url)) {
            return null;
        }

        try {
            $response = Http::withToken($this->apiKey)
                ->post("{$this->baseUrl}/scrape", [
                    'url' => $url,
                    'pageOptions' => ['onlyMainContent' => true]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $markdown = $data['data']['markdown'] ?? '';

                // Only return if content is substantial
                if (strlen($markdown) > 500) {
                    return [
                        'url'      => $url,
                        'title'    => $data['data']['metadata']['title'] ?? parse_url($url, PHP_URL_HOST),
                        'domain'   => parse_url($url, PHP_URL_HOST),
                        'markdown' => mb_substr($markdown, 0, 8000) // Limit size
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning("Firecrawl Scrape Exception for {$url}: " . $e->getMessage());
        }

        return null;
    }
}
