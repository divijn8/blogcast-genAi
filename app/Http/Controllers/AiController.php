<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AiAgentService;
use Exception;

class AiController extends Controller
{
    protected AiAgentService $aiService;

    public function __construct(AiAgentService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * STEP 1
     * Analyze title & excerpt and return article structures
     */
    public function analyze(Request $request)
    {
    // dd($request);
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
            return response()->json([
                'error' => 'Analysis failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * STEP 2
     * Generate full article from selected structure
     */
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

            return response()->json([
                'body' => $body
            ]);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Generation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function analyzePodcast(Request $request)
    {
        $request->validate(['topic' => 'required', 'speakers' => 'required|integer']);

        $prompt = "You are a podcast producer. Create 3 distinct concepts for a {$request->speakers}-person podcast episode about: '{$request->topic}'.

        Return a JSON array(options) with 3 objects. Each object must have:
        - 'title': A catchy title.
        - 'tone': e.g., 'Humorous', 'Serious Debate', 'Beginner Guide'.
        - 'structure_outline': A brief description of the flow.
        - 'difficulty': 'Beginner', 'Intermediate', or 'Expert'.

        Output ONLY valid JSON.";

        $result = $this->aiService->prompt($prompt);
        return response()->json([
            'options' => $result['options']
        ]);
    }

    public function generatePodcastScript(Request $request)
    {
        $request->validate(['title' => 'required', 'structure_outline' => 'required', 'speakers' => 'required']);

        $prompt = "Write a full podcast script for '{$request->title}'.
        Structure: {$request->structure_outline}.
        Format: A {$request->speakers}-person conversation.
        Language can be english or hinglish.

        IMPORTANT: Return a JSON array(script) of objects.
        Each object must be: { 'speaker': 'Host' | 'Guest' | 'Expert', 'text': 'The spoken text' }.
        Make it sound natural, include fillers like 'Hmm', 'Exactly', 'Wow'.";

        $script = $this->aiService->prompt($prompt);

        return response()->json($script);
    }

    public function generatePodcastAudio(Request $request)
    {
        $request->validate(['script' => 'required|array']);

        $audioUrl = $this->aiService->synthesizeConversation(
            $request->script
        );

        return response()->json([
            'audio_url' => $audioUrl
        ]);
    }
}
