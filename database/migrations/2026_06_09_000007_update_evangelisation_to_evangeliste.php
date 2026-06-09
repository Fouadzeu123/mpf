<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Member;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Replace 'Évangélisation' (and variations) with 'Évangéliste' in members.department
        $members = Member::whereNotNull('department')->get();
        foreach ($members as $member) {
            $depts = explode(',', $member->department);
            $changed = false;
            foreach ($depts as $k => $dept) {
                $trimmed = trim($dept);
                if (strcasecmp($trimmed, 'Évangélisation') === 0 || strcasecmp($trimmed, 'Evangelisation') === 0) {
                    $depts[$k] = 'Évangéliste';
                    $changed = true;
                }
            }
            if ($changed) {
                $member->department = implode(', ', array_map('trim', $depts));
                $member->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $members = Member::whereNotNull('department')->get();
        foreach ($members as $member) {
            $depts = explode(',', $member->department);
            $changed = false;
            foreach ($depts as $k => $dept) {
                $trimmed = trim($dept);
                if (strcasecmp($trimmed, 'Évangéliste') === 0 || strcasecmp($trimmed, 'Evangeliste') === 0) {
                    $depts[$k] = 'Évangélisation';
                    $changed = true;
                }
            }
            if ($changed) {
                $member->department = implode(', ', array_map('trim', $depts));
                $member->save();
            }
        }
    }
};
