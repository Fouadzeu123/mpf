<?php

namespace App\Services;

use App\Models\Member;

class MemberCodeService
{
    public function generate(): string
    {
        $lastId = Member::max('id') ?? 0;
        $number = str_pad((string) ($lastId + 1), 6, '0', STR_PAD_LEFT);

        return "MEM-{$number}";
    }

    public function defaultPassword(string $memberCode): string
    {
        return 'mpf-'.strtolower(str_replace('MEM-', '', $memberCode));
    }
}
