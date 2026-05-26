<?php

namespace App\Services;

use App\Models\Visitor;
use Illuminate\Support\Str;

class VisitorService
{
    public function __construct(
        protected QrCodeService $qrCodeService,
        protected ActivityLogService $activityLogService,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Visitor
    {
        $code = 'VIS-'.Str::upper(Str::random(8));

        $visitor = Visitor::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'] ?? null,
            'address_description' => $data['address_description'] ?? null,
            'qr_code' => $code,
            'visit_date' => $data['visit_date'] ?? now()->toDateString(),
        ]);

        $this->activityLogService->log('visitor_created', meta: ['visitor_id' => $visitor->id]);

        return $visitor;
    }
}
