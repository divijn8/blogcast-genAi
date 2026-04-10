<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;
use Symfony\Component\Process\Process;

class AiAgentService
{
    protected $firecrawl;
    protected $groq;

    public function __construct(FirecrawlService $firecrawl, GroqService $groq)
    {
        $this->firecrawl = $firecrawl;
        $this->groq = $groq;
    }

    public function researchStructures($title, $excerpt) {
        $urls = $this->firecrawl->search("blog post about $title $excerpt", 6);
        $sources = [];
        foreach ($urls as $url) {
            $data = $this->firecrawl->scrape($url);
            if ($data) $sources[] = $data;
            if (count($sources) >= 4) break;
        }
        return ['structures' => $this->analyzeStructures($title, $excerpt, $sources), 'sources' => $sources];
    }

    public function generateContent($title, $excerpt, $structureOutline) {
        $systemMsg = "Act as a professional technical writer. Do not use Markdown.";
        $prompt = $this->getWritingPrompt($title, $excerpt, $structureOutline);
        $content = $this->groq->ask($systemMsg, $prompt, false);
        return $content ?: '<p>Could not generate content. Please try again.</p>';
    }

    private function analyzeStructures($title, $excerpt, $sources) {
        $context = $this->buildContextString($sources);
        $systemMsg = "You are a Content Strategist. Output STRICT JSON only.";
        $prompt = $this->getStructurePrompt($title, $excerpt, $context);
        $response = $this->groq->ask($systemMsg, $prompt, true);
        return $response['structures'] ?? $this->getFallbackStructures();
    }

    private function buildContextString(array $sources): string {
        if (empty($sources)) return "No specific competitors found.";
        $context = "";
        foreach ($sources as $source) {
            preg_match_all('/^(#{1,3}\s.*)$/m', $source['markdown'], $matches);
            $headings = implode("\n", array_slice($matches[0], 30));
            $context .= "Source: {$source['title']}\nStructure:\n{$headings}\n\n";
        }
        return $context;
    }

    private function getFallbackStructures() {
        return [
            ['id' => 1, 'name' => 'The Complete Guide', 'badge' => 'Comprehensive', 'outline' => "1. Introduction\n2. Understanding the Basics\n3. Step-by-Step Implementation\n4. Common Pitfalls\n5. Conclusion"],
            ['id' => 2, 'name' => 'Quick & Actionable', 'badge' => 'Fast Read', 'outline' => "Introduction\nTip 1: Quick Win\nTip 2: The Core Strategy\nTip 3: Automation\nSummary"],
            ['id' => 3, 'name' => 'Deep Dive Analysis', 'badge' => 'Advanced', 'outline' => "Executive Summary\nHistorical Context\nTechnical Architecture\nAdvanced Configuration\nFuture Trends"],
            ['id' => 4, 'name' => 'Listicle Format', 'badge' => 'Popular', 'outline' => "Intro\n1. Best Practice A\n2. Best Practice B\n3. Best Practice C\n4. Best Practice D\nFinal Thoughts"]
        ];
    }

    private function getStructurePrompt($title, $excerpt, $context) {
        return <<<PROMPT
Topic: "{$title}" ({$excerpt})
Competitor patterns found:
{$context}

Task: Create EXACTLY 4 distinct blog outlines.
Each outline must have a "name", "badge" (e.g., "Beginner", "Advanced"), and "outline".

Format:
{
  "structures": [
    { "id": 1, "name": "...", "badge": "...", "outline": "Heading 1\\nHeading 2" },
    { "id": 2, "name": "...", "badge": "...", "outline": "..." },
    { "id": 3, "name": "...", "badge": "...", "outline": "..." },
    { "id": 4, "name": "...", "badge": "...", "outline": "..." }
  ]
}
PROMPT;
    }

    private function getWritingPrompt($title, $excerpt, $outline) {
        return <<<PROMPT
Topic: {$title}
Excerpt: {$excerpt}
Structure:
{$outline}

Write the full blog post in HTML format (for Trix Editor).
Rules:
- Use <h2>, <h3>, <p>, <ul>, <li> tags.
- Do NOT use Markdown.
- Do NOT use <html>, <head> or <body> tags.
- Make it engaging and detailed.
PROMPT;
    }

    public function prompt(string $prompt, bool $json = true)
    {
        return $this->groq->ask("You are a professional podcast producer.", $prompt, $json);
    }

    /**
     * GENERATE AUDIO: Host = Female, Expert/Guest = Male
     */
    // public function synthesizeConversation(array $script)
    // {
    //     $piperDir = storage_path('app/piper');
    //     $piperExe = $piperDir . '\piper.exe';
    //     $tempDir  = storage_path('app/public/temp');

    //     // Check Folder
    //     if (!is_dir($piperDir)) throw new Exception("Piper Folder Missing at: $piperDir");

    //     // --- DEFINE MODELS ---
    //     // These file names must match what you downloaded
    //     $femaleModel = $piperDir . '\en_US-libritts-high.onnx'; // Host (Sarah)
    //     $maleModel   = $piperDir . '\en_US-ryan-medium.onnx';    // Expert (Michael)

    //     // Fallback: If exact files are missing, grab whatever ONNX files are there
    //     $allOnnx = glob($piperDir . '\*.onnx');
    //     if(empty($allOnnx)) throw new Exception("No .onnx voice models found in piper folder.");

    //     if (!file_exists($femaleModel)) $femaleModel = $allOnnx[0];
    //     if (!file_exists($maleModel))   $maleModel = isset($allOnnx[1]) ? $allOnnx[1] : $allOnnx[0];

    //     // Create Temp Dir
    //     if (!file_exists($tempDir)) mkdir($tempDir, 0777, true);

    //     $tempFiles = [];
    //     $errors = [];

    //     foreach ($script as $index => $line) {
    //         $text = trim(str_replace(['"', "'", "\n", "\r"], ' ', $line['text']));
    //         if(empty($text)) continue;

    //         // --- VOICE SELECTION LOGIC ---
    //         $speaker = strtolower($line['speaker'] ?? 'host');

    //         // If speaker is "Host", use Female. Anyone else (Guest/Expert), use Male.
    //         $currentModel = ($speaker === 'host') ? $femaleModel : $maleModel;

    //         $outputFile = $tempDir . "\segment_{$index}.wav";

    //         try {
    //             $process = new Process([
    //                 $piperExe,
    //                 '--model', $currentModel,
    //                 '--output_file', $outputFile
    //             ]);

    //             $process->setInput($text);
    //             $process->setTimeout(60);
    //             $process->run();

    //             if ($process->isSuccessful() && file_exists($outputFile) && filesize($outputFile) > 0) {
    //                 $tempFiles[] = $outputFile;
    //             } else {
    //                 $errors[] = "Line $index Failed: " . $process->getErrorOutput();
    //             }
    //         } catch (Exception $e) {
    //             $errors[] = "Process Exception: " . $e->getMessage();
    //         }
    //     }

    //     if (empty($tempFiles)) {
    //         $firstError = !empty($errors) ? $errors[0] : "Unknown error.";
    //         throw new Exception("Audio Generation Failed. Details: " . $firstError);
    //     }

    //     // Merge
    //     $finalWavData = $this->mergeWavFiles($tempFiles);
    //     $finalFileName = 'podcast_' . time() . '.wav';

    //     // Save to public storage
    //     Storage::disk('public')->put("temp/$finalFileName", $finalWavData);

    //     // Cleanup segments
    //     foreach ($tempFiles as $f) @unlink($f);

    //     return asset("storage/temp/$finalFileName");
    // }

    private function mergeWavFiles(array $files)
    {
        if (empty($files)) return null;

        $dataLength = 0;
        $audioData = '';
        $firstHeader = '';

        foreach ($files as $i => $file) {
            $contents = file_get_contents($file);
            if (strlen($contents) < 44) continue;

            $header = substr($contents, 0, 44);
            $body = substr($contents, 44);

            if ($i === 0) $firstHeader = $header;

            $audioData .= $body;
            $dataLength += strlen($body);
        }

        if (!$firstHeader) return null;

        $newHeader = $firstHeader;
        $newHeader = substr_replace($newHeader, pack('V', $dataLength), 40, 4);
        $newHeader = substr_replace($newHeader, pack('V', 36 + $dataLength), 4, 4);

        return $newHeader . $audioData;
    }

    public function synthesizeConversation(array $script)
    {
        // 1. Setup - Time aur Memory badhao taaki crash na ho
        ini_set('memory_limit', '512M');
        set_time_limit(0); // Unlimited time for this request

        $client = new \GuzzleHttp\Client(['timeout' => 60]);
        $apiKey = env('ELEVENLABS_API_KEY');

        // Voice IDs (Dashboard se match kar lena)
        $voices = [
            'Host'   => 'st8o4LADtfxckX2PH08x',
            'Guest'  => 'aSFxChEgBmCyExpaDqHd',
            'Expert' => 's0oIsoSJ9raiUm7DJNzW'
        ];

        $combinedBinary = '';
        $timestamp = time();

        try {
            foreach ($script as $index => $line) {
                $voiceId = $voices[$line['speaker']] ?? $voices['Host'];
                $text = trim($line['text']);

                if (empty($text)) continue;

                $response = $client->post("https://api.elevenlabs.io/v1/text-to-speech/{$voiceId}", [
                    'headers' => [
                        'xi-api-key'   => $apiKey,
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'text'           => $text,
                        'model_id'       => 'eleven_multilingual_v2',
                        'voice_settings' => ['stability' => 0.5, 'similarity_boost' => 0.75]
                    ]
                ]);

                if ($response->getStatusCode() === 200) {
                    $combinedBinary .= $response->getBody()->getContents();
                } else {
                    Log::error("ElevenLabs Line $index failed with status: " . $response->getStatusCode());
                }
            }

            if (empty($combinedBinary)) {
                throw new Exception("No audio data was generated from ElevenLabs.");
            }

            // 4. Final Save - Poori merged audio ko ek hi file mein save karo
            $finalFileName = "podcast_full_{$timestamp}.mp3";
            Storage::disk('public')->put("temp/{$finalFileName}", $combinedBinary);

            return asset("storage/temp/{$finalFileName}");

        } catch (Exception $e) {
            Log::error("Audio Synthesis Critical Failure: " . $e->getMessage());
            throw $e;
        }
    }
}
