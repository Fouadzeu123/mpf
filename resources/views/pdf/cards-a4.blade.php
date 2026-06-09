<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: {{ $layout['margin_mm'] }}mm; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 7px;
            color: #1e293b;
            background: #fff;
        }
        .grid {
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: {{ $layout['gap_mm'] }}mm;
        }
        .row { display: table-row; }
        .cell {
            display: table-cell;
            width: {{ $layout['card_width_mm'] }}mm;
            height: {{ $layout['card_height_mm'] }}mm;
            vertical-align: top;
            padding: 1.8mm;
        }
        .card-inner {
            position: relative;
            height: 100%;
            border: 0.7px solid #1e3a8a;
            border-radius: 2mm;
            padding: 2mm;
            background: #ffffff;
            overflow: hidden;
        }
        .card-top {
            display: table;
            width: 100%;
            padding: 1.2mm 1.5mm;
            border-radius: 1.5mm;
            background: #172554;
            color: #ffffff;
            margin-bottom: 1.6mm;
        }
        .brand-logo,
        .brand-text,
        .brand-badge {
            display: table-cell;
            vertical-align: middle;
        }
        .brand-logo { width: 18mm; }
        .brand-text { text-align: left; }
        .brand-badge {
            width: 19mm;
            text-align: right;
        }
        .logo {
            height: 12mm;
            max-width: 16mm;
            object-fit: contain;
            background: #ffffff;
            border-radius: 1mm;
            padding: 0.3mm;
        }
        .church-name {
            font-size: 6.7px;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 0.3px;
            line-height: 1.1;
        }
        .card-title {
            font-size: 4.8px;
            color: #93c5fd;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-top: 0.4mm;
        }
        .member-badge {
            display: inline-block;
            font-size: 4.8px;
            font-weight: bold;
            color: #ffffff;
            background: #2563eb;
            border-radius: 6mm;
            padding: 0.7mm 1.4mm;
        }
        .card-body {
            display: table;
            width: 100%;
        }
        .photo-col {
            display: table-cell;
            width: 20mm;
            vertical-align: top;
        }
        .info-col {
            display: table-cell;
            vertical-align: top;
            padding: 0 1.8mm;
        }
        .qr-col {
            display: table-cell;
            width: 20mm;
            vertical-align: top;
            text-align: center;
        }
        .photo {
            width: 18mm;
            height: 22mm;
            object-fit: cover;
            border-radius: 1.4mm;
            border: 0.7px solid #1e3a8a;
            background: #f8fafc;
        }
        .photo-empty {
            width: 18mm;
            height: 22mm;
            background: #eff6ff;
            border-radius: 1.4mm;
            border: 0.7px dashed #1e3a8a;
        }
        .name {
            font-size: 8.2px;
            font-weight: bold;
            color: #0f172a;
            line-height: 1.2;
            text-transform: uppercase;
        }
        .first-name {
            font-size: 7.4px;
            font-weight: bold;
            color: #1d4ed8;
            line-height: 1.2;
            margin-bottom: 0.8mm;
        }
        .meta {
            font-size: 5.7px;
            color: #334155;
            line-height: 1.25;
        }
        .line {
            margin-top: 0.5mm;
            padding-bottom: 0.3mm;
            border-bottom: 0.2px solid #cbd5e1;
        }
        .label {
            font-size: 4.6px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .code {
            display: inline-block;
            font-size: 5.6px;
            font-weight: bold;
            color: #ffffff;
            background: #172554;
            border-radius: 4mm;
            padding: 0.5mm 1.2mm;
            margin-top: 0.8mm;
        }
        .qr-col img {
            width: 18.5mm;
            height: 18.5mm;
            border: 0.5px solid #cbd5e1;
            border-radius: 1mm;
            background: #ffffff;
            padding: 0.8mm;
        }
        .qr-label {
            font-size: 4.4px;
            font-weight: bold;
            color: #1e3a8a;
            margin-top: 0.7mm;
            text-transform: uppercase;
        }
        .programs {
            font-size: 4.8px;
            color: #475569;
            text-align: left;
            margin-top: 1.1mm;
            padding: 0.8mm 1mm;
            border-radius: 1mm;
            background: #f8fafc;
            border: 0.25px solid #cbd5e1;
            line-height: 1.25;
        }
        .visitor-tag {
            display: inline-block;
            font-size: 5px;
            font-weight: bold;
            color: #1d4ed8;
            border: 0.5px solid #2563eb;
            padding: 0.2mm 1mm;
            border-radius: 0.5mm;
            margin-top: 0.5mm;
        }
        .cut-guide {
            border: 0.25px dashed #e2e8f0;
        }

        /* Special theme: Anciens (Emerald Green) */
        .theme-ancien {
            border-color: #047857 !important;
        }
        .theme-ancien .card-top {
            background: #064e3b !important;
        }
        .theme-ancien .member-badge {
            background: #059669 !important;
        }
        .theme-ancien .label {
            color: #047857 !important;
        }
        .theme-ancien .first-name {
            color: #059669 !important;
        }
        .theme-ancien .code {
            background: #064e3b !important;
        }
        .theme-ancien .qr-label {
            color: #047857 !important;
        }

        /* Special theme: Dirigeant (Black & Gold Premium) */
        .theme-dirigeant {
            border-color: #d97706 !important;
        }
        .theme-dirigeant .card-top {
            background: #090d16 !important;
            border-bottom: 0.5px solid #d97706 !important;
        }
        .theme-dirigeant .member-badge {
            background: #d97706 !important;
            color: #ffffff !important;
        }
        .theme-dirigeant .label {
            color: #d97706 !important;
        }
        .theme-dirigeant .first-name {
            color: #d97706 !important;
        }
        .theme-dirigeant .code {
            background: #090d16 !important;
            border: 0.3px solid #d97706 !important;
            color: #fbbf24 !important;
        }
        .theme-dirigeant .qr-label {
            color: #d97706 !important;
        }

        /* Added fields styling */
        .church-supername {
            font-size: 5.2px;
            font-weight: normal;
            color: #93c5fd;
            letter-spacing: 0.4px;
            line-height: 1;
            text-transform: uppercase;
            margin-bottom: 0.2mm;
        }
        .theme-dirigeant .church-supername {
            color: #fbbf24;
        }
        .bible-verse {
            font-size: 4.8px;
            color: #475569;
            font-style: italic;
            text-align: center;
            margin-top: 1mm;
            line-height: 1.25;
        }
        .theme-ancien .bible-verse {
            color: #065f46;
        }
        .theme-dirigeant .bible-verse {
            color: #b45309;
        }
        .card-footer {
            margin-top: 1mm;
            padding-top: 0.6mm;
            border-top: 0.2px solid #cbd5e1;
            text-align: center;
            font-size: 4.5px;
            font-weight: bold;
            color: #64748b;
        }
        .theme-ancien .card-footer {
            border-top-color: #a7f3d0;
            color: #047857;
        }
        .theme-dirigeant .card-footer {
            border-top-color: #fde68a;
            color: #d97706;
        }
    </style>
</head>
<body>
@php
    $cols = $layout['columns'];
    $rows = $layout['rows'];
    $perPage = $cols * $rows;
    $chunks = $cards->chunk($perPage);
    $programsText = implode(' • ', $programs);
@endphp
@foreach($chunks as $pageIndex => $pageCards)
    <div class="grid">
        @for ($r = 0; $r < $rows; $r++)
            <div class="row">
                @for ($c = 0; $c < $cols; $c++)
                    @php
                        $card = $pageCards->values()->get($r * $cols + $c);
                        
                        $isAncien = false;
                        $isDirigeant = false;
                        $isDeptHidden = false;
                        $themeClass = '';
                        $displayDept = '';
                        
                        if ($card && ($card['type'] ?? '') === 'member') {
                            $normalize = function($str) {
                                if (!$str) return '';
                                $str = strtolower(trim($str));
                                $str = str_replace(['é', 'è', 'ê', 'ë'], 'e', $str);
                                $str = str_replace(['à', 'â', 'ä'], 'a', $str);
                                $str = str_replace(['ô', 'ö'], 'o', $str);
                                $str = str_replace(['û', 'ü'], 'u', $str);
                                $str = str_replace(['ç'], 'c', $str);
                                return $str;
                            };
                            
                            $rawDepts = explode(',', $card['department'] ?? '');
                            $memberDepts = [];
                            foreach ($rawDepts as $rd) {
                                $trimmed = trim($rd);
                                if ($trimmed !== '') {
                                    $memberDepts[] = $trimmed;
                                }
                            }
                            
                            foreach ($memberDepts as $md) {
                                $norm = $normalize($md);
                                if ($norm === 'anciens' || $norm === 'ancien') {
                                    $isAncien = true;
                                }
                                if ($norm === 'pasteur' || $norm === 'dirigeant') {
                                    $isDirigeant = true;
                                }
                            }
                            
                            if ($isAncien) {
                                $themeClass = 'theme-ancien';
                            } elseif ($isDirigeant) {
                                $themeClass = 'theme-dirigeant';
                            }
                            
                            $hiddenDepts = ['anciens', 'evangelisation', 'nettoyage', 'chorale', 'pasteur', 'protocole', 'communication', "culte d'enfant", 'diacres', 'moniteurs'];
                            $hiddenDeptsNorm = array_map($normalize, $hiddenDepts);
                            
                            $visibleDepts = [];
                            foreach ($memberDepts as $md) {
                                $norm = $normalize($md);
                                if (!in_array($norm, $hiddenDeptsNorm, true)) {
                                    $visibleDepts[] = $md;
                                }
                            }
                            
                            if (!empty($visibleDepts)) {
                                $displayDept = implode(', ', $visibleDepts);
                            } else {
                                $isDeptHidden = true;
                            }
                        }
                    @endphp
                    <div class="cell cut-guide">
                        @if ($card)
                            <div class="card-inner {{ $themeClass }}">
                                <div class="card-top">
                                    @if (!empty($logoDataUri))
                                        <div class="brand-logo"><img src="{{ $logoDataUri }}" class="logo" alt=""></div>
                                    @endif
                                    <div class="brand-text">
                                        <div class="church-supername">(Eglise du Christ)</div>
                                        <div class="church-name">{{ $churchName }}</div>
                                        <div class="card-title">Carte d'identification</div>
                                    </div>
                                    <div class="brand-badge">
                                        <span class="member-badge">
                                            @if ($isDirigeant)
                                                DIRIGEANT
                                            @elseif (($card['type'] ?? '') === 'visitor')
                                                VISITEUR
                                            @else
                                                MEMBRE
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="photo-col">
                                        @if (!empty($card['photo_data_uri']))
                                            <img src="{{ $card['photo_data_uri'] }}" class="photo" alt="">
                                        @else
                                            <div class="photo-empty"></div>
                                        @endif
                                    </div>
                                    <div class="info-col">
                                        <div class="name">{{ $card['last_name'] ?? $card['full_name'] }}</div>
                                        @if (!empty($card['first_name']))
                                            <div class="first-name">{{ $card['first_name'] }}</div>
                                        @endif
                                        <div class="meta">
                                            @if (($card['type'] ?? '') === 'member')
                                                <div class="line"><span class="label">Sexe</span> : {{ $card['gender'] ?: '-' }} &nbsp; <span class="label">Naissance</span> : {{ $card['birth_date'] ?: '-' }}</div>
                                                <div class="line"><span class="label">Adresse</span> : {{ $card['address'] ?: '-' }}</div>
                                                <div class="line"><span class="label">Téléphone</span> : {{ $card['phone'] ?: '-' }}</div>
                                                @if (!$isDeptHidden && !empty($displayDept))
                                                    <div class="line"><span class="label">Département</span> : {{ $displayDept }}</div>
                                                @endif
                                            @else
                                                @if (!empty($card['phone']))
                                                    {{ $card['phone'] }}<br>
                                                @endif
                                                Visite {{ $card['visit_date'] ?? '' }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="qr-col">
                                        <img src="{{ $card['qr_data_uri'] }}" alt="QR">
                                        <div class="qr-label">Scanner</div>
                                        <div class="code">{{ $card['code'] }}</div>
                                    </div>
                                </div>
                                <div class="bible-verse">
                                    « Mon peuple est détruit, parce qu'il lui manque la connaissance. » — Osée 4:6
                                </div>
                                <div class="programs">
                                    @foreach($programs as $program)
                                        {{ $program }}@if(! $loop->last) • @endif
                                    @endforeach
                                </div>
                                <div class="card-footer">
                                    FB : Ministère Prophétique de la Foi • Tél Église : 675028538
                                </div>
                            </div>
                        @endif
                    </div>
                @endfor
            </div>
        @endfor
    </div>
    @if (! $loop->last)
        <div style="page-break-after: always;"></div>
    @endif
@endforeach
</body>
</html>
