<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Visitor;
use App\Services\MemberCardPdfService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PrintController extends Controller
{
    public function __construct(
        protected MemberCardPdfService $pdfService,
    ) {}

    public function index(): Response
    {
        return Inertia::render('print/Index', [
            'members' => Member::orderBy('last_name')->get(['id', 'first_name', 'last_name', 'member_code']),
            'visitors' => Visitor::where('visit_date', '>=', now()->subDays(30))
                ->orderBy('visit_date', 'desc')
                ->get(['id', 'first_name', 'last_name', 'qr_code', 'visit_date']),
        ]);
    }

    public function members(Request $request)
    {
        $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer', 'exists:members,id']]);

        $members = Member::whereIn('id', $request->ids)->get();

        return $this->pdfService->downloadMembers($members);
    }

    public function visitors(Request $request)
    {
        $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer', 'exists:visitors,id']]);

        $visitors = Visitor::whereIn('id', $request->ids)->get();

        return $this->pdfService->downloadVisitors($visitors);
    }
}
