<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVisitorRequest;
use App\Models\Visitor;
use App\Services\MemberService;
use App\Services\VisitorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VisitorController extends Controller
{
    public function __construct(
        protected VisitorService $visitorService,
        protected MemberService $memberService,
    ) {}

    public function index(): Response
    {
        return Inertia::render('visitors/Index', [
            'visitors' => Visitor::latest('visit_date')->paginate(15),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('visitors/Create');
    }

    public function store(StoreVisitorRequest $request): RedirectResponse
    {
        $visitor = $this->visitorService->create($request->validated());

        return redirect()->route('visitors.show', $visitor)
            ->with('toast', ['type' => 'success', 'message' => 'Visiteur enregistré.']);
    }

    public function show(Visitor $visitor): Response
    {
        return Inertia::render('visitors/Show', ['visitor' => $visitor]);
    }

    public function convert(Visitor $visitor): RedirectResponse
    {
        $member = $this->memberService->fromVisitor($visitor);

        return redirect()->route('members.show', $member)
            ->with('toast', ['type' => 'success', 'message' => 'Visiteur transformé en membre.']);
    }
}
