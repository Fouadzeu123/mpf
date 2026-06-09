<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Member;
use App\Models\Contribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    /**
     * Display a listing of events for admin.
     */
    public function index(): Response
    {
        $events = Event::latest()->get()->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'location' => $event->location,
                'start_date' => $event->start_date->format('Y-m-d H:i'),
                'end_date' => $event->end_date ? $event->end_date->format('Y-m-d H:i') : null,
                'banner_url' => $event->banner_url,
                'total_contributions' => $event->total_contributions,
            ];
        });

        return Inertia::render('events/Index', [
            'events' => $events,
        ]);
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'banner_file' => ['nullable', 'image', 'max:2048'],
        ]);

        $bannerPath = null;
        if ($request->hasFile('banner_file')) {
            $bannerPath = $request->file('banner_file')->store('events', 'public');
        }

        Event::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'location' => $validated['location'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'banner' => $bannerPath,
        ]);

        return redirect()->route('admin-events.index')->with('toast', ['type' => 'success', 'message' => 'Événement créé avec succès.']);
    }

    /**
     * Display details of a specific event (with contributions).
     */
    public function show(Event $event): Response
    {
        $event->load(['contributions.member', 'contributions.recorder']);
        
        $contributions = $event->contributions()
            ->where('payment_status', 'paid')
            ->latest()
            ->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'member_name' => $c->member->full_name,
                'member_code' => $c->member->member_code,
                'amount' => $c->amount,
                'payment_method' => $c->payment_method === 'manual' ? 'Manuel (Espèces)' : 'Mobile Money',
                'recorder_name' => $c->recorder ? $c->recorder->name : null,
                'date' => $c->created_at->format('d/m/Y H:i'),
            ]);

        $members = Member::select('id', 'first_name', 'last_name', 'member_code')
            ->where('status', 'active')
            ->orderBy('last_name')
            ->get()
            ->map(fn ($m) => [
                'id' => $m->id,
                'name' => $m->full_name,
                'code' => $m->member_code,
            ]);

        return Inertia::render('events/Show', [
            'event' => [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'location' => $event->location,
                'start_date' => $event->start_date->format('d/m/Y H:i'),
                'end_date' => $event->end_date ? $event->end_date->format('d/m/Y H:i') : null,
                'banner_url' => $event->banner_url,
                'total_contributions' => $event->total_contributions,
            ],
            'contributions' => $contributions,
            'members' => $members,
        ]);
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'banner_file' => ['nullable', 'image', 'max:2048'],
        ]);

        $bannerPath = $event->banner;

        if ($request->hasFile('banner_file')) {
            if ($event->banner) {
                Storage::disk('public')->delete($event->banner);
            }
            $bannerPath = $request->file('banner_file')->store('events', 'public');
        }

        $event->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'location' => $validated['location'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'banner' => $bannerPath,
        ]);

        return redirect()->route('admin-events.index')->with('toast', ['type' => 'success', 'message' => 'Événement mis à jour avec succès.']);
    }

    /**
     * Remove the specified event.
     */
    public function destroy(Event $event)
    {
        if ($event->banner) {
            Storage::disk('public')->delete($event->banner);
        }
        
        $event->delete();

        return redirect()->route('admin-events.index')->with('toast', ['type' => 'success', 'message' => 'Événement supprimé avec succès.']);
    }

    /**
     * Record a manual contribution.
     */
    public function storeContribution(Request $request, Event $event)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'amount' => ['required', 'integer', 'min:100'],
        ]);

        Contribution::create([
            'event_id' => $event->id,
            'member_id' => $validated['member_id'],
            'amount' => $validated['amount'],
            'payment_status' => 'paid',
            'payment_method' => 'manual',
            'recorded_by' => auth()->id(),
        ]);

        return redirect()->back()->with('toast', ['type' => 'success', 'message' => 'Contribution manuelle enregistrée avec succès.']);
    }
}
