<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class MemberAuthController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('member/Login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'member_code' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $member = Member::where('member_code', $request->member_code)->first();

        if (! $member || ! $member->checkPassword($request->password)) {
            return back()->withErrors([
                'member_code' => 'Identifiant ou mot de passe incorrect.',
            ]);
        }

        Auth::guard('member')->login($member, $request->boolean('remember'));

        return redirect()->route('member.portal');
    }

    public function destroy(): RedirectResponse
    {
        Auth::guard('member')->logout();

        return redirect()->route('member.login');
    }
}
