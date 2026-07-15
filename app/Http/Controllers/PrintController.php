<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Visitor;
use App\Models\Attendance;
use App\Models\CommunionPreparation;
use App\Services\MemberCardPdfService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
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

    public function printMembersList()
    {
        $members = Member::orderBy('last_name')->orderBy('first_name')->get();
        $title = "Liste des Membres";
        $dateStr = now()->format('d/m/Y');

        $pdf = Pdf::loadView('pdf.list-report', [
            'type' => 'members',
            'title' => $title,
            'dateStr' => $dateStr,
            'items' => $members,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('liste-membres-'.now()->format('Y-m-d').'.pdf');
    }

    public function printMonthlyAttendances(Request $request)
    {
        $monthInput = $request->get('month', now()->format('Y-m'));
        $parsed = Carbon::parse($monthInput.'-01');
        $start = $parsed->copy()->startOfMonth();
        $end = $parsed->copy()->endOfMonth();

        $attendances = Attendance::with('member')
            ->whereBetween('scanned_at', [$start, $end])
            ->orderBy('scanned_at', 'asc')
            ->get();

        $title = "Liste des Présences - " . $parsed->translatedFormat('F Y');
        $dateStr = now()->format('d/m/Y');

        $pdf = Pdf::loadView('pdf.list-report', [
            'type' => 'attendances',
            'title' => $title,
            'dateStr' => $dateStr,
            'items' => $attendances,
            'month' => $parsed->translatedFormat('F Y'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('presences-'.$monthInput.'-'.now()->format('Y-m-d').'.pdf');
    }

    public function printCommunionPrepared(Request $request)
    {
        $dateInput = $request->get('date');
        $monthInput = $request->get('month', now()->format('Y-m'));

        $query = CommunionPreparation::with('member');

        if ($dateInput) {
            $parsedDate = Carbon::parse($dateInput);
            $query->whereDate('created_at', $parsedDate);
            $title = "Préparations Sainte Cène du " . $parsedDate->format('d/m/Y');
        } else {
            $parsedMonth = Carbon::parse($monthInput.'-01');
            $query->whereBetween('created_at', [$parsedMonth->copy()->startOfMonth(), $parsedMonth->copy()->endOfMonth()]);
            $title = "Préparations Sainte Cène - " . $parsedMonth->translatedFormat('F Y');
        }

        $preparations = $query->orderBy('created_at', 'asc')->get();
        $dateStr = now()->format('d/m/Y');

        $pdf = Pdf::loadView('pdf.list-report', [
            'type' => 'communion',
            'title' => $title,
            'dateStr' => $dateStr,
            'items' => $preparations,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('preparations-sainte-cene-'.now()->format('Y-m-d').'.pdf');
    }

    public function printAbsentsCulte(Request $request)
    {
        $dateInput = $request->get('date');
        $monthInput = $request->get('month', now()->format('Y-m'));

        $query = Member::query()->where('status', 'active');

        if ($dateInput) {
            $parsedDate = Carbon::parse($dateInput);
            $query->whereNotExists(function ($q) use ($parsedDate) {
                $q->selectRaw(1)
                    ->from('attendances')
                    ->whereColumn('attendances.member_id', 'members.id')
                    ->whereDate('attendances.scanned_at', $parsedDate);
            });
            $title = "Absents au Culte du " . $parsedDate->format('d/m/Y');
        } else {
            $parsedMonth = Carbon::parse($monthInput.'-01');
            $start = $parsedMonth->copy()->startOfMonth();
            $end = $parsedMonth->copy()->endOfMonth();
            $query->whereNotExists(function ($q) use ($start, $end) {
                $q->selectRaw(1)
                    ->from('attendances')
                    ->whereColumn('attendances.member_id', 'members.id')
                    ->whereBetween('attendances.scanned_at', [$start, $end]);
            });
            $title = "Absents au Culte - " . $parsedMonth->translatedFormat('F Y');
        }

        $members = $query->orderBy('last_name')->orderBy('first_name')->get();
        $dateStr = now()->format('d/m/Y');

        $pdf = Pdf::loadView('pdf.list-report', [
            'type' => 'absents_cultes',
            'title' => $title,
            'dateStr' => $dateStr,
            'items' => $members,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('absents-cultes-'.now()->format('Y-m-d').'.pdf');
    }

    public function printNotPreparedCommunion(Request $request)
    {
        $dateInput = $request->get('date');
        $monthInput = $request->get('month', now()->format('Y-m'));

        $query = Member::query()->where('status', 'active');

        if ($dateInput) {
            $parsedDate = Carbon::parse($dateInput);
            $query->whereNotExists(function ($q) use ($parsedDate) {
                $q->selectRaw(1)
                    ->from('communion_preparations')
                    ->whereColumn('communion_preparations.member_id', 'members.id')
                    ->whereDate('communion_preparations.created_at', $parsedDate);
            });
            $title = "Membres sans Préparation Sainte Cène du " . $parsedDate->format('d/m/Y');
        } else {
            $parsedMonth = Carbon::parse($monthInput.'-01');
            $start = $parsedMonth->copy()->startOfMonth();
            $end = $parsedMonth->copy()->endOfMonth();
            $query->whereNotExists(function ($q) use ($start, $end) {
                $q->selectRaw(1)
                    ->from('communion_preparations')
                    ->whereColumn('communion_preparations.member_id', 'members.id')
                    ->whereBetween('communion_preparations.created_at', [$start, $end]);
            });
            $title = "Membres sans Préparation Sainte Cène - " . $parsedMonth->translatedFormat('F Y');
        }

        $members = $query->orderBy('last_name')->orderBy('first_name')->get();
        $dateStr = now()->format('d/m/Y');

        $pdf = Pdf::loadView('pdf.list-report', [
            'type' => 'not_prepared_communion',
            'title' => $title,
            'dateStr' => $dateStr,
            'items' => $members,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('non-prepares-sainte-cene-'.now()->format('Y-m-d').'.pdf');
    }
}
