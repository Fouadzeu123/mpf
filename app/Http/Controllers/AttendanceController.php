<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AttendanceController extends Controller
{
    public function index(Request $request): Response
    {
        $attendances = Attendance::with(['member', 'scanner'])
            ->when($request->date, fn ($q, $d) => $q->whereDate('scanned_at', $d))
            ->when($request->service_type, fn ($q, $s) => $q->where('service_type', $s))
            ->latest('scanned_at')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('attendances/Index', [
            'attendances' => $attendances,
            'filters' => $request->only(['date', 'service_type']),
        ]);
    }
}
