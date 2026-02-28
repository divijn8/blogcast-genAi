<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AiAgentService;
use Exception;
use Illuminate\Support\Facades\Log;

class AiController extends Controller
{
    protected AiAgentService $aiService;

    public function __construct(AiAgentService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function analyze(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|min:3',
            'excerpt' => 'required|string|min:3',
        ]);

        try {
            $result = $this->aiService->researchStructures(
                $request->title,
                $request->excerpt
            );
            return response()->json($result);
        } catch (Exception $e) {
            return response()->json(['error' => 'Analysis failed: ' . $e->getMessage()], 500);
        }
    }

    public function generate(Request $request)
    {
        $request->validate([
            'title'             => 'required|string',
            'excerpt'           => 'required|string',
            'structure_outline' => 'required|string',
        ]);

        try {
            $body = $this->aiService->generateContent(
                $request->title,
                $request->excerpt,
                $request->structure_outline
            );
            return response()->json(['body' => $body]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Generation failed: ' . $e->getMessage()], 500);
        }
    }

    public function analyzePodcast(Request $request)
    {
        $request->validate(['topic' => 'required', 'speakers' => 'required']);

        $prompt = "You are a podcast producer. Create 3 distinct concepts for a {$request->speakers}-person podcast episode about: '{$request->topic}'.

        Return a JSON array(options) with 3 objects. Each object must have:
        - 'title': A catchy title.
        - 'tone': e.g., 'Humorous', 'Serious Debate', 'Beginner Guide'.
        - 'structure_outline': A brief description of the flow.
        - 'difficulty': 'Beginner', 'Intermediate', or 'Expert'.

        Output ONLY valid JSON.";

        try {
            $result = $this->aiService->prompt($prompt);

            $options = [];
            if (isset($result['options']) && is_array($result['options'])) {
                $options = $result['options'];
            } elseif (isset($result[0]) && is_array($result[0])) {
                $options = $result;
            } elseif (isset($result['concepts'])) {
                $options = $result['concepts'];
            }

            return response()->json([
                'options' => $options
            ]);

        } catch (Exception $e) {
            Log::error("Podcast Analyze Error: " . $e->getMessage());
            return response()->json(['options' => []]);
        }
    }

    /**
     * UPDATED: Strict Gender Prompts
     */
    public function generatePodcastScript(Request $request)
    {
        $request->validate(['title' => 'required', 'structure_outline' => 'required', 'speakers' => 'required']);

        // Explicitly define personas to match our Voice Models
        $prompt = "Write a full podcast script for '{$request->title}'.
        Structure: {$request->structure_outline}.

        CRITICAL RULES FOR CHARACTERS:
        1. The HOST is a FEMALE named 'Sarah'. (She leads the show).
        2. The EXPERT/GUEST is a MALE named 'Michael'. (He provides technical details).

        Format: A conversation between Sarah (Host) and Michael (Expert).
        Language: English (or Hinglish if appropriate for the topic).

        IMPORTANT: Return a JSON array(script) of objects.
        Each object must be: { 'speaker': 'Host' | 'Expert', 'text': 'The spoken text' }.

        - When 'speaker' is 'Host', the text must sound like Sarah (Female).
        - When 'speaker' is 'Expert', the text must sound like Michael (Male).
        - Make them address each other by name (Sarah and Michael).
        - Include fillers like 'Hmm', 'Exactly', 'Wow'.";

        try {
            $result = $this->aiService->prompt($prompt);

            $script = [];
            if (isset($result['script']) && is_array($result['script'])) {
                $script = $result['script'];
            } elseif (isset($result[0]) && is_array($result[0])) {
                $script = $result;
            } elseif (isset($result['conversation'])) {
                $script = $result['conversation'];
            }

            return response()->json(['script' => $script]);

        } catch (Exception $e) {
            Log::error("Podcast Script Error: " . $e->getMessage());
            return response()->json(['script' => []]);
        }
    }

    public function generatePodcastAudio(Request $request)
    {
        $request->validate(['script' => 'required|array']);

        try {
            $audioUrl = $this->aiService->synthesizeConversation($request->script);

            if (!$audioUrl) {
                throw new Exception("Generated URL is empty.");
            }

            return response()->json(['audio_url' => $audioUrl]);

        } catch (Exception $e) {
            Log::error("Audio Generation Controller Error: " . $e->getMessage());
            return response()->json([
                'error' => 'Audio generation failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
