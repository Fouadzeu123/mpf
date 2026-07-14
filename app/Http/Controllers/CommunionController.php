<?php

namespace App\Http\Controllers;

use App\Models\CommunionPreparation;
use App\Models\Setting;
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

        $preparationDate = Setting::where('key', 'communion_preparation_date')->value('value');

        return Inertia::render('communion/Index', [
            'preparations' => $preparations,
            'filters' => $request->only(['date']),
            'preparationDate' => $preparationDate,
        ]);
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'preparation_date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        Setting::updateOrCreate(
            ['key' => 'communion_preparation_date'],
            ['value' => $request->preparation_date]
        );

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Date de préparation à la Sainte Cène mise à jour avec succès.',
        ]);
    }
}
