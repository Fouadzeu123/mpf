<?php

namespace App\Http\Controllers;

use App\Enums\ScanMode;
use App\Enums\ServiceType;
use App\Enums\UserRole;
use App\Services\ScannerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ScannerController extends Controller
{
    public function __construct(
        protected ScannerService $scannerService,
    ) {}

    public function index(Request $request): Response
    {
        $allowedModes = $this->allowedModes($request->user()->role);
        $requestedMode = $request->get('mode', ScanMode::Attendance->value);
        $mode = in_array($requestedMode, $allowedModes, true)
            ? $requestedMode
            : $allowedModes[0];

        return Inertia::render('scanner/Index', [
            'mode' => $mode,
            'serviceType' => $request->get('service', ServiceType::CulteDimanche->value),
            'serviceTypes' => collect(ServiceType::cases())->map(fn ($s) => [
                'value' => $s->value,
                'label' => $s->label(),
            ]),
            'modes' => $this->modeOptions($allowedModes),
        ]);
    }

    public function scan(Request $request): JsonResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
            'mode' => ['required', 'in:profile,attendance,communion'],
            'service_type' => ['nullable', 'string'],
        ]);

        $mode = ScanMode::from($request->mode);

        if (! in_array($mode->value, $this->allowedModes($request->user()->role), true)) {
            abort(403, 'Mode scanner non autorisé pour votre rôle.');
        }

        $serviceType = $request->service_type
            ? ServiceType::from($request->service_type)
            : ServiceType::CulteDimanche;

        $result = $this->scannerService->process(
            $request->code,
            $mode,
            $serviceType,
        );

        return response()->json($result);
    }

    /** @return list<string> */
    protected function allowedModes(UserRole $role): array
    {
        return match ($role) {
            UserRole::Protocole => [ScanMode::Attendance->value],
            UserRole::Ancienne => [
                ScanMode::Profile->value,
                ScanMode::Attendance->value,
                ScanMode::Communion->value,
            ],
            default => [
                ScanMode::Profile->value,
                ScanMode::Attendance->value,
                ScanMode::Communion->value,
            ],
        };
    }

    /**
     * @param  list<string>  $allowedModes
     * @return list<array{value: string, label: string}>
     */
    protected function modeOptions(array $allowedModes): array
    {
        $labels = [
            ScanMode::Profile->value => 'Profil',
            ScanMode::Attendance->value => 'Présence',
            ScanMode::Communion->value => 'Sainte Cène',
        ];

        return collect($allowedModes)
            ->map(fn (string $mode) => ['value' => $mode, 'label' => $labels[$mode]])
            ->values()
            ->all();
    }
}
