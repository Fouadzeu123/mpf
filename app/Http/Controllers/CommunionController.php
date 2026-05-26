<?php

namespace App\Http\Controllers;

use App\Models\CommunionPreparation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CommunionController extends Controller
{
    public function index(Request $request): Response
    {
        $preparations = CommunionPreparation::with('member')
            ->when($request->date, fn ($q, $d) => $q->whereDate('created_at', $d))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('communion/Index', [
            'preparations' => $preparations,
            'filters' => $request->only(['date']),
        ]);
    }
}
