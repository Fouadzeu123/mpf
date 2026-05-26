<?php

namespace App\Console\Commands;

use App\Enums\MemberStatus;
use App\Jobs\SendBibleVerseJob;
use App\Models\Member;
use Illuminate\Console\Command;

class SendBibleVersesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:verses {--all : Send to all active members} {--member-id= : Send to specific member} {--retry : Retry failed verses}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send random Bible verses to members via WhatsApp';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Determine which members to send to
        $members = $this->getMembers();

        if ($members->isEmpty()) {
            $this->warn('No members found to send verses to.');
            return 0;
        }

        $count = 0;
        $bar = $this->output->createProgressBar($members->count());
        $bar->start();

        foreach ($members as $member) {
            if (!$member->phone) {
                $this->line("\nSkipping {$member->first_name} {$member->last_name} - No phone number");
                $bar->advance();
                continue;
            }

            SendBibleVerseJob::dispatch($member);
            $count++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Queued $count Bible verse(s) for sending.");

        return Command::SUCCESS;
    }

    /**
     * Get members to send verses to
     */
    protected function getMembers()
    {
        if ($this->option('member-id')) {
            return Member::where('id', $this->option('member-id'))->get();
        }

        // Default: Send to active members on preparation day
        $query = Member::where('status', MemberStatus::Active);

        if (!$this->option('all')) {
            // Only send to members who haven't received today
            $query->whereDoesntHave('communionPreparations', function ($q) {
                $q->whereDate('created_at', today());
            });
        }

        return $query->get();
    }
}
