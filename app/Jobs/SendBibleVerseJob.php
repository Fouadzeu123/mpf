<?php

namespace App\Jobs;

use App\Models\CommunionPreparation;
use App\Models\Member;
use App\Services\BibleVerseService;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendBibleVerseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Member $member,
        public ?string $verseReference = null,
    ) {
        $this->onQueue('default');
    }

    /**
     * Execute the job.
     */
    public function handle(
        BibleVerseService $bibleService,
        WhatsAppService $whatsappService,
    ): void
    {
        try {
            // Get or fetch verse
            if ($this->verseReference) {
                $verse = $this->fetchSpecificVerse($bibleService, $this->verseReference);
            } else {
                $verse = $bibleService->randomVerse();
            }

            if (!$verse || !isset($verse['reference'], $verse['text'])) {
                Log::warning('Invalid verse data', [
                    'member_id' => $this->member->id,
                    'verse_reference' => $this->verseReference,
                ]);
                $this->fail(new \Exception('Invalid verse data'));
                return;
            }

            // Send via WhatsApp using the generic sendVerse method
            $sent = $whatsappService->sendVerse(
                $this->member->phone,
                $verse['reference'],
                $verse['text']
            );

            if ($sent) {
                Log::info('Bible verse sent successfully', [
                    'member_id' => $this->member->id,
                    'phone' => $this->member->phone,
                    'verse_reference' => $verse['reference'],
                ]);

                // Update the latest communion preparation as sent
                $this->markAsCommuntionPrepSent($verse['reference']);
            } else {
                Log::warning('Failed to send bible verse', [
                    'member_id' => $this->member->id,
                    'phone' => $this->member->phone,
                ]);
                // Retry up to 3 times
                if ($this->attempts() < 3) {
                    $this->release(delay: 300); // Retry after 5 minutes
                } else {
                    $this->fail(new \Exception('Max attempts reached'));
                }
            }
        } catch (\Throwable $e) {
            Log::error('Error sending bible verse: '.$e->getMessage(), [
                'member_id' => $this->member->id,
                'exception' => $e,
            ]);
            $this->fail($e);
        }
    }

    /**
     * Mark the latest communion preparation as sent
     */
    protected function markAsCommuntionPrepSent(string $verseReference): void
    {
        $preparation = CommunionPreparation::where('member_id', $this->member->id)
            ->where('verse_reference', $verseReference)
            ->latest('created_at')
            ->first();

        if ($preparation) {
            $preparation->update(['whatsapp_sent' => true]);
        }
    }

    /**
     * Fetch a specific verse
     */
    protected function fetchSpecificVerse(BibleVerseService $service, string $reference): ?array
    {
        try {
            // Parse reference like "Jean 3:16"
            preg_match('/^(.+?)\s+(\d+):(\d+)$/', $reference, $matches);

            if (count($matches) === 4) {
                [$_, $book, $chapter, $verse] = $matches;
                return $service->fetchVerseByReference($book, (int) $chapter, (int) $verse);
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to parse verse reference: '.$reference);
        }

        return null;
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SendBibleVerseJob failed permanently', [
            'member_id' => $this->member->id,
            'exception' => $exception->getMessage(),
        ]);
    }
}
