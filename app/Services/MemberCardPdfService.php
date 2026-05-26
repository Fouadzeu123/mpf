<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Visitor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class MemberCardPdfService
{
    public function __construct(
        protected QrCodeService $qrCodeService,
    ) {}

    /** @param  Collection<int, Member>|array<int, Member>  $members */
    public function downloadMembers(Collection|array $members)
    {
        $cards = $this->buildMemberCards($members);

        return Pdf::loadView('pdf.cards-a4', $this->viewData($cards))
            ->setPaper('a4', 'portrait')
            ->download('cartes-membres-'.now()->format('Y-m-d').'.pdf');
    }

    /** @param  Collection<int, Visitor>|array<int, Visitor>  $visitors */
    public function downloadVisitors(Collection|array $visitors)
    {
        $cards = collect($visitors)->map(fn (Visitor $v) => [
            'type' => 'visitor',
            'full_name' => $v->full_name,
            'phone' => $v->phone,
            'code' => $v->qr_code,
            'qr_data_uri' => $this->qrCodeService->generateDataUri($v->qr_code, 120),
            'visit_date' => $v->visit_date->format('d/m/Y'),
        ]);

        return Pdf::loadView('pdf.cards-a4', $this->viewData($cards))
            ->setPaper('a4', 'portrait')
            ->download('cartes-visiteurs-'.now()->format('Y-m-d').'.pdf');
    }

    public function singleMember(Member $member)
    {
        return $this->downloadMembers(collect([$member]));
    }

    /**
     * @param  Collection<int, Member>|array<int, Member>  $members
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    protected function buildMemberCards(Collection|array $members): Collection
    {
        return collect($members)->map(fn (Member $m) => [
            'type' => 'member',
            'full_name' => $m->full_name,
            'first_name' => $m->first_name,
            'last_name' => $m->last_name,
            'age' => $m->age,
            'gender' => $m->gender,
            'department' => $m->department,
            'address' => $m->address_description,
            'code' => $m->member_code,
            'photo_data_uri' => $this->memberPhotoDataUri($m),
            'qr_data_uri' => $this->qrCodeService->generateDataUri($m->qr_code, 120),
        ]);
    }

    /** @return array<string, mixed> */
    protected function viewData(Collection $cards): array
    {
        return [
            'cards' => $cards,
            'layout' => config('church.a4_print'),
            'churchName' => config('church.name'),
            'programs' => config('church.programs'),
            'logoDataUri' => $this->logoDataUri(),
        ];
    }

    protected function logoDataUri(): string
    {
        $path = public_path(config('church.logo', 'favicon.svg'));

        if (! is_file($path)) {
            return '';
        }

        $mimeType = match (pathinfo($path, PATHINFO_EXTENSION)) {
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            default => 'image/png'
        };

        return 'data:'.$mimeType.';base64,'.base64_encode((string) file_get_contents($path));
    }

    protected function memberPhotoDataUri(Member $member): string
    {
        if (! $member->photo || str_starts_with($member->photo, 'http')) {
            return '';
        }

        $path = Storage::disk('public')->path($member->photo);

        if (! is_file($path)) {
            return '';
        }

        $contents = file_get_contents($path);

        if ($contents === false) {
            return '';
        }

        $mime = mime_content_type($path) ?: 'image/jpeg';

        return 'data:'.$mime.';base64,'.base64_encode($contents);
    }
}
