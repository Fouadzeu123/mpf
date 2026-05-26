<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BibleVerseService
{
    /**
     * Complete OSIS mapping for all 66 books of the Bible
     */
    protected const OSIS_MAP = [
        // Old Testament - 39 books
        'Genèse' => 'GEN', 'Exode' => 'EXO', 'Lévitique' => 'LEV', 'Nombres' => 'NUM',
        'Deutéronome' => 'DEU', 'Josué' => 'JOS', 'Juges' => 'JDG', 'Ruth' => 'RUT',
        'Samuel 1' => '1SA', '1 Samuel' => '1SA', 'Premier Samuel' => '1SA',
        'Samuel 2' => '2SA', '2 Samuel' => '2SA', 'Deuxième Samuel' => '2SA',
        'Rois 1' => '1KI', '1 Rois' => '1KI', 'Premier Rois' => '1KI',
        'Rois 2' => '2KI', '2 Rois' => '2KI', 'Deuxième Rois' => '2KI',
        'Chroniques 1' => '1CH', '1 Chroniques' => '1CH', 'Premier Chroniques' => '1CH',
        'Chroniques 2' => '2CH', '2 Chroniques' => '2CH', 'Deuxième Chroniques' => '2CH',
        'Esdras' => 'EZR', 'Néhémie' => 'NEH', 'Esther' => 'EST',
        'Job' => 'JOB', 'Psaumes' => 'PSA', 'Proverbes' => 'PRO',
        'Ecclésiaste' => 'ECC', 'Cantique' => 'SNG', 'Cantique des Cantiques' => 'SNG',
        'Ésaïe' => 'ISA', 'Isaïe' => 'ISA', 'Jérémie' => 'JER',
        'Lamentations' => 'LAM', 'Ézéchiel' => 'EZK', 'Daniel' => 'DAN',
        'Osée' => 'HOS', 'Joël' => 'JOL', 'Amos' => 'AMO',
        'Abdias' => 'OBA', 'Jonas' => 'JON', 'Michée' => 'MIC',
        'Nahum' => 'NAH', 'Habacuc' => 'HAB', 'Sophonie' => 'ZEP',
        'Aggée' => 'HAG', 'Zacharie' => 'ZEC', 'Malachie' => 'MAL',

        // New Testament - 27 books
        'Matthieu' => 'MAT', 'Marc' => 'MRK', 'Luc' => 'LUK', 'Jean' => 'JHN',
        'Actes' => 'ACT', 'Romains' => 'ROM',
        'Corinthiens 1' => '1CO', '1 Corinthiens' => '1CO', 'Premier Corinthiens' => '1CO',
        'Corinthiens 2' => '2CO', '2 Corinthiens' => '2CO', 'Deuxième Corinthiens' => '2CO',
        'Galates' => 'GAL', 'Éphésiens' => 'EPH', 'Philippiens' => 'PHP',
        'Colossiens' => 'COL',
        'Thessaloniciens 1' => '1TH', '1 Thessaloniciens' => '1TH', 'Premier Thessaloniciens' => '1TH',
        'Thessaloniciens 2' => '2TH', '2 Thessaloniciens' => '2TH', 'Deuxième Thessaloniciens' => '2TH',
        'Timothée 1' => '1TI', '1 Timothée' => '1TI', 'Premier Timothée' => '1TI',
        'Timothée 2' => '2TI', '2 Timothée' => '2TI', 'Deuxième Timothée' => '2TI',
        'Tite' => 'TIT', 'Philémon' => 'PHM', 'Hébreux' => 'HEB',
        'Jacques' => 'JAS',
        'Pierre 1' => '1PE', '1 Pierre' => '1PE', 'Premier Pierre' => '1PE',
        'Pierre 2' => '2PE', '2 Pierre' => '2PE', 'Deuxième Pierre' => '2PE',
        'Jean 1' => '1JN', '1 Jean' => '1JN', 'Premier Jean' => '1JN',
        'Jean 2' => '2JN', '2 Jean' => '2JN', 'Deuxième Jean' => '2JN',
        'Jean 3' => '3JN', '3 Jean' => '3JN', 'Troisième Jean' => '3JN',
        'Jude' => 'JUD', 'Apocalypse' => 'REV',
    ];

    /**
     * Get a random Bible verse
     * @return array{reference: string, text: string, book: string, chapter: int, verse: int}
     */
    public function randomVerse(): array
    {
        $structure = config('bible_structure');
        $book = array_rand($structure);
        $chapters = $structure[$book];
        $chapterIndex = array_rand($chapters);
        $chapter = $chapterIndex + 1;
        $verseCount = $chapters[$chapterIndex];
        $verse = random_int(1, $verseCount);

        $reference = "{$book} {$chapter}:{$verse}";
        $text = $this->fetchVerseText($book, $chapter, $verse) ?? $this->fallbackText($reference);

        return [
            'reference' => $reference,
            'text' => $text,
            'book' => $book,
            'chapter' => $chapter,
            'verse' => $verse,
        ];
    }

    /**
     * Fetch a specific verse by book, chapter, and verse number
     */
    public function fetchVerseByReference(string $book, int $chapter, int $verse): ?array
    {
        $reference = "{$book} {$chapter}:{$verse}";
        $text = $this->fetchVerseText($book, $chapter, $verse) ?? $this->fallbackText($reference);

        if (!$text) {
            return null;
        }

        return [
            'reference' => $reference,
            'text' => $text,
            'book' => $book,
            'chapter' => $chapter,
            'verse' => $verse,
        ];
    }

    /**
     * Fetch verse text from API with caching
     */
    protected function fetchVerseText(string $book, int $chapter, int $verse): ?string
    {
        $apiKey = config('services.bible.api_key');

        if (! $apiKey) {
            return null;
        }

        $cacheKey = "bible_verse:{$book}:{$chapter}:{$verse}";

        // Try cache first (1 week TTL)
        return Cache::remember(
            $cacheKey,
            now()->addWeek(),
            fn() => $this->fetchFromApi($book, $chapter, $verse)
        );
    }

    /**
     * Fetch verse from Bible API
     */
    protected function fetchFromApi(string $book, int $chapter, int $verse): ?string
    {
        $apiKey = config('services.bible.api_key');

        if (! $apiKey) {
            return null;
        }

        try {
            $osis = $this->toOsisId($book, $chapter, $verse);
            $bibleId = config('services.bible.bible_id');
            $baseUrl = config('services.bible.base_url');

            $response = Http::withToken($apiKey)
                ->timeout(10)
                ->get("{$baseUrl}/bibles/{$bibleId}/verses/{$osis}", [
                    'content-type' => 'text',
                    'include-notes' => false,
                    'include-titles' => false,
                ]);

            if ($response->successful()) {
                $data = $response->json('data.content');
                return $data ? strip_tags($data) : null;
            }

            Log::warning('Bible API returned non-200 status', [
                'status' => $response->status(),
                'osis' => $osis,
                'book' => $book,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Bible API error: '.$e->getMessage(), [
                'book' => $book,
                'chapter' => $chapter,
                'verse' => $verse,
            ]);
        }

        return null;
    }

    /**
     * Convert book name to OSIS identifier
     */
    protected function toOsisId(string $book, int $chapter, int $verse): string
    {
        // Check complete map first
        if (isset(self::OSIS_MAP[$book])) {
            $osisCode = self::OSIS_MAP[$book];
        } else {
            // Fallback: take first 3 letters and convert to uppercase
            $osisCode = strtoupper(Str::substr(Str::ascii($book), 0, 3));
        }

        return "{$osisCode}.{$chapter}.{$verse}";
    }

    /**
     * Get fallback text when API is not configured
     */
    protected function fallbackText(string $reference): string
    {
        return "Verset tiré aléatoirement — {$reference}. Configurez BIBLE_API_KEY pour le texte complet.";
    }
}
    }

    protected function fallbackText(string $reference): string
    {
        return "Verset tiré aléatoirement — {$reference}. Configurez BIBLE_API_KEY pour le texte complet.";
    }
}
