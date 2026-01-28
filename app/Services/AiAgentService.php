<?php

namespace App\Services;

class AiAgentService
{
    protected $firecrawl;
    protected $groq;

    public function __construct(FirecrawlService $firecrawl, GroqService $groq)
    {
        $this->firecrawl = $firecrawl;
        $this->groq = $groq;
    }

    /**
     * MAIN ENTRY POINT: Research & Analyze
     */
    public function researchStructures($title, $excerpt)
    {
        $urls = $this->firecrawl->search("blog post about $title $excerpt", 6);

        $sources = [];
        foreach ($urls as $url) {
            $data = $this->firecrawl->scrape($url);
            if ($data) {
                $sources[] = $data;
            }
            if (count($sources) >= 4) break; // Limit sources
        }

        $structures = $this->analyzeStructures($title, $excerpt, $sources);

        return [
            'structures' => $structures,
            'sources'    => $sources
        ];
    }

    /**
     * Generate final HTML content
     */
    public function generateContent($title, $excerpt, $structureOutline)
    {
        $systemMsg = "Act as a professional technical writer. Do not use Markdown.";
        $prompt = $this->getWritingPrompt($title, $excerpt, $structureOutline);

        $content = $this->groq->ask($systemMsg, $prompt, false);

        return $content ?: '<p>Could not generate content. Please try again.</p>';
    }

    private function analyzeStructures($title, $excerpt, $sources)
    {
        $context = $this->buildContextString($sources);

        $systemMsg = "You are a Content Strategist. Output STRICT JSON only.";
        $prompt = $this->getStructurePrompt($title, $excerpt, $context);

        $response = $this->groq->ask($systemMsg, $prompt, true);

        if (empty($response) || empty($response['structures'])) {
            return $this->getFallbackStructures();
        }

        return $response['structures'];
    }

    private function buildContextString(array $sources): string
    {
        if (empty($sources)) return "No specific competitors found.";

        $context = "";
        foreach ($sources as $source) {
            preg_match_all('/^(#{1,3}\s.*)$/m', $source['markdown'], $matches);
            $headings = implode("\n", array_slice($matches[0], 0, 30));

            $context .= "Source: {$source['title']}\nStructure:\n{$headings}\n\n";
        }
        return $context;
    }

    private function getFallbackStructures()
    {
        return [
            ['id' => 1, 'name' => 'The Complete Guide', 'badge' => 'Comprehensive', 'outline' => "1. Introduction\n2. Understanding the Basics\n3. Step-by-Step Implementation\n4. Common Pitfalls\n5. Conclusion"],
            ['id' => 2, 'name' => 'Quick & Actionable', 'badge' => 'Fast Read', 'outline' => "Introduction\nTip 1: Quick Win\nTip 2: The Core Strategy\nTip 3: Automation\nSummary"],
            ['id' => 3, 'name' => 'Deep Dive Analysis', 'badge' => 'Advanced', 'outline' => "Executive Summary\nHistorical Context\nTechnical Architecture\nAdvanced Configuration\nFuture Trends"],
            ['id' => 4, 'name' => 'Listicle Format', 'badge' => 'Popular', 'outline' => "Intro\n1. Best Practice A\n2. Best Practice B\n3. Best Practice C\n4. Best Practice D\nFinal Thoughts"]
        ];
    }

    private function getStructurePrompt($title, $excerpt, $context)
    {
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

    private function getWritingPrompt($title, $excerpt, $outline)
    {
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
}
