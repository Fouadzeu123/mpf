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
            'phone' => $m->phone,
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
        $logo = config('church.logo', 'favicon.svg');

        // If the logo is favicon.svg and the lightweight PNG alternative exists,
        // use the PNG for PDF rendering to avoid Dompdf hanging/timing out on the 2.3MB SVG.
        if ($logo === 'favicon.svg' && is_file(public_path('favicon-96x96.png'))) {
            $logo = 'favicon-96x96.png';
        }

        $path = public_path($logo);

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

        // Try to dynamically resize large profile pictures if the GD library is available
        // to prevent Dompdf from throwing memory/time limit exceptions on multi-megabyte uploads.
        if (extension_loaded('gd')) {
            try {
                $info = getimagesize($path);
                if ($info) {
                    [$width, $height, $type] = $info;
                    // If image is wider than 300px, resample it to 300px width
                    if ($width > 300) {
                        $newWidth = 300;
                        $newHeight = (int) (($height / $width) * $newWidth);

                        $src = match ($type) {
                            IMAGETYPE_JPEG => imagecreatefromjpeg($path),
                            IMAGETYPE_PNG => imagecreatefrompng($path),
                            IMAGETYPE_GIF => imagecreatefromgif($path),
                            IMAGETYPE_WEBP => imagecreatefromwebp($path),
                            default => null
                        };

                        if ($src) {
                            $dst = imagecreatetruecolor($newWidth, $newHeight);

                            // Setup alpha transparency for PNGs
                            if ($type === IMAGETYPE_PNG) {
                                imagealphablending($dst, false);
                                imagesavealpha($dst, true);
                                $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
                                imagefilledrectangle($dst, 0, 0, $newWidth, $newHeight, $transparent);
                            }

                            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                            ob_start();
                            if ($type === IMAGETYPE_PNG) {
                                imagepng($dst, null, 6); // Compress PNG
                                $contents = ob_get_clean();
                                $mime = 'image/png';
                            } else {
                                imagejpeg($dst, null, 75); // Convert to JPEG with 75% quality for small footprint
                                $contents = ob_get_clean();
                                $mime = 'image/jpeg';
                            }

                            imagedestroy($src);
                            imagedestroy($dst);

                            if ($contents !== false) {
                                return 'data:'.$mime.';base64,'.base64_encode($contents);
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Fall back to original file if GD processing fails
            }
        }

        $contents = file_get_contents($path);

        if ($contents === false) {
            return '';
        }

        $mime = mime_content_type($path) ?: 'image/jpeg';

        return 'data:'.$mime.';base64,'.base64_encode($contents);
    }
}
