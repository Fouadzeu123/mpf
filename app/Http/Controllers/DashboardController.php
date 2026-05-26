<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\ActivityLog;
use App\Models\Attendance;
use App\Models\CommunionPreparation;
use App\Models\Member;
use App\Models\PaymentTransaction;
use App\Models\Visitor;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $role = request()->user()->role;
        $isAdmin = $role === UserRole::Admin;

        return Inertia::render('Dashboard', [
            'role' => $role->value,
            'stats' => [
                'members' => in_array($role, [UserRole::Admin, UserRole::Secretaire, UserRole::Ancienne], true) ? Member::count() : 0,
                'visitors' => in_array($role, [UserRole::Admin, UserRole::Secretaire], true) ? Visitor::count() : 0,
                'attendances_today' => $isAdmin ? Attendance::whereDate('scanned_at', today())->count() : 0,
                'communion_today' => $isAdmin ? CommunionPreparation::whereDate('created_at', today())->count() : 0,
            ],
            'recentPayments' => $isAdmin ? PaymentTransaction::with('member')
                ->latest()
                ->limit(5)
                ->get()
                ->map(fn ($p) => [
                    'id' => $p->id,
                    'reference' => $p->reference,
                    'amount' => $p->amount,
                    'status' => $p->status->value,
                    'member_name' => $p->member->full_name,
                    'created_at' => $p->created_at->diffForHumans(),
                ]) : [],
            'recentActivities' => $isAdmin ? ActivityLog::with('user')
                ->latest()
                ->limit(8)
                ->get()
                ->map(fn ($a) => [
                    'id' => $a->id,
                    'action' => $a->action,
                    'user' => $a->user?->name,
                    'created_at' => $a->created_at->diffForHumans(),
                ]) : [],
            'attendanceChart' => $isAdmin ? Attendance::select(DB::raw('DATE(scanned_at) as date'), DB::raw('count(*) as total'))
                ->where('scanned_at', '>=', now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date')
                ->get() : [],
        ]);
    }
}
