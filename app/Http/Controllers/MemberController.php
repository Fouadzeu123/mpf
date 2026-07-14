<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Enums\ServiceType;
use App\Enums\UserRole;
use App\Models\Member;
use App\Services\MemberCardPdfService;
use App\Services\MemberService;
use App\Services\QrCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MemberController extends Controller
{
    public function __construct(
        protected MemberService $memberService,
        protected QrCodeService $qrCodeService,
        protected MemberCardPdfService $pdfService,
    ) {}

    public function index(Request $request): Response
    {
        $members = Member::query()
            ->when($request->search, function ($q) use ($request) {
                $s = $request->search;
                $q->where(function ($q) use ($s) {
                    $q->where('first_name', 'like', "%{$s}%")
                        ->orWhere('last_name', 'like', "%{$s}%")
                        ->orWhere('member_code', 'like', "%{$s}%")
                        ->orWhere('phone', 'like', "%{$s}%");
                });
            })
            ->when($request->department, fn ($q) => $q->where('department', 'like', "%{$request->department}%"))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->gender, fn ($q) => $q->where('gender', $request->gender))
            ->when($request->profession, fn ($q) => $q->where('profession', $request->profession))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $departments = Member::pluck('department')
            ->flatMap(fn ($d) => explode(',', $d ?? ''))
            ->map(fn ($d) => trim($d))
            ->filter()
            ->unique()
            ->values()
            ->all();

        return Inertia::render('members/Index', [
            'members' => $members,
            'filters' => $request->only(['search', 'department', 'status', 'gender', 'profession']),
            'departments' => $departments,
            'professions' => Member::whereNotNull('profession')->where('profession', '<>', '')->distinct()->pluck('profession')->values(),
            'genders' => Member::whereNotNull('gender')->where('gender', '<>', '')->distinct()->pluck('gender')->values(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('members/Create');
    }

    public function store(StoreMemberRequest $request): RedirectResponse
    {
        $member = $this->memberService->create(
            $request->validated(),
            $request->file('photo'),
        );

        return redirect()->route('members.show', $member)
            ->with('toast', ['type' => 'success', 'message' => 'Membre enregistré avec succès.']);
    }

    public function show(Member $member): Response
    {
        $canViewSpiritualTracking = in_array(request()->user()->role, [UserRole::Admin, UserRole::Ancienne], true);

        $member->load([
            'attendances' => fn ($q) => $canViewSpiritualTracking ? $q->latest('scanned_at')->limit(20) : $q->whereRaw('1 = 0'),
            'communionPreparations' => fn ($q) => $canViewSpiritualTracking ? $q->latest()->limit(20) : $q->whereRaw('1 = 0'),
            'contributions.event',
        ]);

        $sundayAttendances = collect();
        $communionPreparations = collect();
        $contributions = collect();

        if ($canViewSpiritualTracking) {
            $monthStart = now()->startOfMonth();
            $monthEnd = now()->endOfMonth();
            $sundayAttendances = $member->attendances()
                ->where('service_type', ServiceType::CulteDimanche)
                ->whereBetween('scanned_at', [$monthStart, $monthEnd])
                ->orderBy('scanned_at')
                ->get()
                ->map(fn ($attendance) => [
                    'date' => $attendance->scanned_at->format('d/m/Y'),
                    'time' => $attendance->scanned_at->format('H:i'),
                    'service_type' => $attendance->service_type->label(),
                ]);

            $communionPreparations = $member->communionPreparations()
                ->latest()
                ->limit(20)
                ->get()
                ->map(fn ($preparation) => [
                    'date' => $preparation->created_at->format('d/m/Y'),
                    'time' => $preparation->created_at->format('H:i'),
                    'verse_reference' => $preparation->verse_reference,
                    'payment_status' => $preparation->payment_status?->value,
                    'payment_reference' => $preparation->payment_reference,
                    'remote' => $preparation->remote,
                ]);

            $contributions = $member->contributions()
                ->where('payment_status', 'paid')
                ->latest()
                ->get()
                ->map(fn ($c) => [
                    'id' => $c->id,
                    'event_title' => $c->event->title,
                    'amount' => $c->amount,
                    'payment_method' => $c->payment_method === 'manual' ? 'Manuel (Espèces)' : 'Mobile Money',
                    'date' => $c->created_at->format('d/m/Y H:i'),
                ]);
        }

        return Inertia::render('members/Show', [
            'member' => $member,
            'canViewSpiritualTracking' => $canViewSpiritualTracking,
            'sundayAttendancesCount' => $sundayAttendances->count(),
            'sundayAttendances' => $sundayAttendances,
            'communionPreparations' => $communionPreparations,
            'contributions' => $contributions,
            'totalContributions' => $contributions->sum('amount'),
            'currentMonthLabel' => now()->translatedFormat('F Y'),
            'qrSvg' => $this->qrCodeService->generateSvg($member->qr_code),
            'programs' => config('church.programs'),
            'googleMapsKey' => config('church.google_maps_api_key'),
            'routeUrl' => $member->latitude && $member->longitude
                ? "https://www.google.com/maps/dir/?api=1&destination={$member->latitude},{$member->longitude}"
                : null,
        ]);
    }

    public function edit(Member $member): Response
    {
        return Inertia::render('members/Edit', ['member' => $member]);
    }

    public function update(UpdateMemberRequest $request, Member $member): RedirectResponse
    {
        $this->memberService->update($member, $request->validated(), $request->file('photo'));

        return redirect()->route('members.show', $member)
            ->with('toast', ['type' => 'success', 'message' => 'Membre mis à jour.']);
    }

    public function destroy(Member $member): RedirectResponse
    {
        $member->delete();

        return redirect()->route('members.index')
            ->with('toast', ['type' => 'success', 'message' => 'Membre supprimé.']);
    }

    public function card(Member $member)
    {
        return $this->pdfService->singleMember($member);
    }

    public function updateGps(Request $request, Member $member): RedirectResponse
    {
        $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $this->memberService->updateLocation(
            $member,
            (float) $request->latitude,
            (float) $request->longitude,
        );

        return back()->with('toast', ['type' => 'success', 'message' => 'Localisation enregistrée.']);
    }

    public function uploadPhoto(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'photo' => ['required', 'image', 'max:10240'],
        ]);

        $path = $request->file('photo')->store('members', 'public');

        return response()->json([
            'path' => $path,
            'url' => asset('storage/' . $path),
        ]);
    }

    public function reorderCodes(Request $request): RedirectResponse
    {
        $priority = [
            'apotre' => 1,
            'pasteur' => 2,
            'dirigeant' => 2,
            'evangeliste' => 3,
            'anciens' => 4,
            'ancien' => 4,
            'diacres' => 5,
            'diacre' => 5,
            'chorale' => 6,
        ];

        $normalize = function($str) {
            if (!$str) return '';
            $str = strtolower(trim($str));
            $str = str_replace(['é', 'è', 'ê', 'ë'], 'e', $str);
            $str = str_replace(['à', 'â', 'ä'], 'a', $str);
            $str = str_replace(['ô', 'ö'], 'o', $str);
            $str = str_replace(['û', 'ü'], 'u', $str);
            $str = str_replace(['ç'], 'c', $str);
            return $str;
        };

        $members = Member::all();

        $sorted = $members->sort(function ($a, $b) use ($priority, $normalize) {
            $deptsA = array_filter(array_map('trim', explode(',', $a->department ?? '')));
            $pA = 7;
            foreach ($deptsA as $d) {
                $norm = $normalize($d);
                if (isset($priority[$norm])) {
                    $pA = min($pA, $priority[$norm]);
                }
            }

            $deptsB = array_filter(array_map('trim', explode(',', $b->department ?? '')));
            $pB = 7;
            foreach ($deptsB as $d) {
                $norm = $normalize($d);
                if (isset($priority[$norm])) {
                    $pB = min($pB, $priority[$norm]);
                }
            }

            if ($pA !== $pB) {
                return $pA <=> $pB;
            }

            $hasDeptA = !empty($deptsA) ? 1 : 0;
            $hasDeptB = !empty($deptsB) ? 1 : 0;
            if ($hasDeptA !== $hasDeptB) {
                return $hasDeptB <=> $hasDeptA;
            }

            $lastNameComp = strcasecmp($a->last_name ?? '', $b->last_name ?? '');
            if ($lastNameComp !== 0) {
                return $lastNameComp;
            }

            return strcasecmp($a->first_name ?? '', $b->first_name ?? '');
        })->values();

        \Illuminate\Support\Facades\DB::transaction(function () use ($sorted) {
            // First pass: assign temp codes to avoid unique constraint collisions
            foreach ($sorted as $member) {
                $temp = 'TEMP-' . $member->id . '-' . uniqid();
                $member->update([
                    'member_code' => $temp,
                    'qr_code' => $temp,
                ]);
            }

            // Second pass: assign final sequential codes
            foreach ($sorted as $i => $member) {
                $num = str_pad((string) ($i + 1), 6, '0', STR_PAD_LEFT);
                $newCode = "MEM-{$num}";
                $member->update([
                    'member_code' => $newCode,
                    'qr_code' => $newCode,
                ]);
            }
        });

        return redirect()->route('members.index')
            ->with('toast', ['type' => 'success', 'message' => 'Les codes membres ont été reclassés avec succès.']);
    }
}
