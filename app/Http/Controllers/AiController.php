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
}
