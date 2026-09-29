<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <style>
        @page {
            size: 210mm 297mm;
            margin-top: {{ $layout['margin_v_mm'] ?? 9.75 }}mm;
            margin-bottom: {{ $layout['margin_v_mm'] ?? 9.75 }}mm;
            margin-left: {{ $layout['margin_h_mm'] ?? 17.0 }}mm;
            margin-right: {{ $layout['margin_h_mm'] ?? 17.0 }}mm;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8px;
            color: #0f172a;
            background: #ffffff;
            opacity: 1 !important;
        }
        .grid {
            display: table;
            width: 176mm;
            margin: 0 auto;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .row {
            display: table-row;
            page-break-inside: avoid;
        }
        .cell {
            display: table-cell;
            width: 88mm;
            height: 55.5mm;
            vertical-align: middle;
            text-align: center;
            padding: 1mm 1.5mm;
            position: relative;
            box-sizing: border-box;
        }
        .card-bleed-box {
            width: 85mm;
            height: 53.5mm;
            position: relative;
            box-sizing: border-box;
            background: #ffffff;
            margin: 0 auto;
        }
        .card-inner {
            position: relative;
            width: 85mm;
            height: 53.5mm;
            border: 0.8px solid #1e3a8a;
            border-radius: 1.5mm;
            padding: 1.2mm 1.4mm;
            background: #ffffff;
            overflow: hidden;
            box-sizing: border-box;
            text-align: left;
            margin: 0 auto;
        }
        .card-top {
            display: table;
            width: 100%;
            padding: 1mm 1.4mm;
            border-radius: 1.2mm;
            background: #0f172a;
            color: #ffffff;
            margin-bottom: 0.8mm;
            opacity: 1 !important;
        }
        .brand-logo,
        .brand-text,
        .brand-badge {
            display: table-cell;
            vertical-align: middle;
            opacity: 1 !important;
        }
        .brand-logo { width: 14mm; }
        .brand-text { text-align: left; }
        .brand-badge {
            width: 20mm;
            text-align: right;
        }
        .logo {
            height: 8.5mm;
            max-width: 13mm;
            object-fit: contain;
            background: #ffffff;
            border-radius: 1mm;
            padding: 0.3mm;
        }
        .church-name {
            font-size: 11px;
            font-weight: 900;
            color: #ffffff !important;
            letter-spacing: 0.4px;
            line-height: 1.15;
            opacity: 1 !important;
            text-transform: uppercase;
        }
        .church-supername {
            font-size: 6.8px;
            font-weight: 700;
            color: #ffffff !important;
            letter-spacing: 0.2px;
            line-height: 1.15;
            text-transform: uppercase;
            margin-top: 0.2mm;
            opacity: 1 !important;
        }
        .card-title {
            font-size: 7.6px;
            font-weight: 800;
            color: #fef08a !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.2mm;
            opacity: 1 !important;
        }
        .member-badge {
            display: inline-block;
            font-size: 8.5px;
            font-weight: 800;
            color: #ffffff !important;
            background: #2563eb;
            border-radius: 4mm;
            padding: 0.7mm 1.8mm;
            opacity: 1 !important;
        }
        .card-body {
            display: table;
            width: 100%;
        }
        .photo-col {
            display: table-cell;
            width: 18.5mm;
            vertical-align: top;
        }
        .info-col {
            display: table-cell;
            vertical-align: top;
            padding: 0 1.2mm;
        }
        .qr-col {
            display: table-cell;
            width: 19mm;
            vertical-align: top;
            text-align: center;
        }
        .photo {
            width: 17.5mm;
            height: 20mm;
            object-fit: cover;
            border-radius: 1.5mm;
            border: 0.8px solid #1e3a8a;
            background: #f8fafc;
            opacity: 1 !important;
        }
        .photo-empty {
            width: 17.5mm;
            height: 20mm;
            background: #eff6ff;
            border-radius: 1.5mm;
            border: 0.8px dashed #1e3a8a;
        }
        .name {
            font-size: 11.5px;
            font-weight: 900;
            color: #000000 !important;
            line-height: 1.15;
            text-transform: uppercase;
            opacity: 1 !important;
        }
        .first-name {
            font-size: 10.2px;
            font-weight: 800;
            color: #1d4ed8 !important;
            line-height: 1.15;
            margin-bottom: 0.3mm;
            opacity: 1 !important;
        }
        .meta {
            font-size: 7.8px;
            font-weight: 600;
            color: #0f172a !important;
            line-height: 1.28;
            opacity: 1 !important;
        }
        .line {
            margin-top: 0.2mm;
            padding-bottom: 0.15mm;
            border-bottom: 0.25px solid #cbd5e1;
            opacity: 1 !important;
        }
        .label {
            font-size: 6.8px;
            font-weight: 800;
            color: #1e3a8a !important;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            opacity: 1 !important;
        }
        .code {
            display: inline-block;
            font-size: 8.2px;
            font-weight: 800;
            color: #ffffff !important;
            background: #0f172a;
            border-radius: 4mm;
            padding: 0.5mm 1.5mm;
            margin-top: 0.3mm;
            opacity: 1 !important;
        }
        .qr-col img {
            width: 16.5mm;
            height: 16.5mm;
            border: 0.6px solid #94a3b8;
            border-radius: 1mm;
            background: #ffffff;
            padding: 0.4mm;
            opacity: 1 !important;
        }
        .qr-label {
            font-size: 6.2px;
            font-weight: 800;
            color: #1e3a8a !important;
            margin-top: 0.2mm;
            text-transform: uppercase;
            opacity: 1 !important;
        }
        .programs {
            font-size: 6.8px;
            font-weight: 600;
            color: #1e293b !important;
            text-align: left;
            margin-top: 0.4mm;
            padding: 0.3mm 0.6mm;
            border-radius: 1mm;
            background: #f8fafc;
            border: 0.3px solid #cbd5e1;
            line-height: 1.2;
            opacity: 1 !important;
        }
        .visitor-tag {
            display: inline-block;
            font-size: 6px;
            font-weight: 800;
            color: #1d4ed8 !important;
            border: 0.6px solid #2563eb;
            padding: 0.3mm 1mm;
            border-radius: 0.5mm;
            margin-top: 0.5mm;
            opacity: 1 !important;
        }
        .cut-guide {
            border: 0.25px dashed #cbd5e1;
        }


        /* 1. Apôtre: Royal Purple & Gold */
        .theme-apotre {
            border-color: #6b21a8 !important;
        }
        .theme-apotre .card-top {
            background: #3b0764 !important;
            border-bottom: 0.5px solid #d97706 !important;
        }
        .theme-apotre .member-badge {
            background: #d97706 !important;
            color: #ffffff !important;
        }
        .theme-apotre .label {
            color: #6b21a8 !important;
        }
        .theme-apotre .first-name {
            color: #7e22ce !important;
        }
        .theme-apotre .code {
            background: #3b0764 !important;
            border: 0.3px solid #d97706 !important;
            color: #fcd34d !important;
        }
        .theme-apotre .qr-label {
            color: #6b21a8 !important;
        }
        .theme-apotre .bible-verse {
            color: #581c87 !important;
        }
        .theme-apotre .card-footer {
            border-top-color: #e9d5ff !important;
            color: #6b21a8 !important;
        }
        .theme-apotre .photo { border-color: #6b21a8 !important; }
        .theme-apotre .photo-empty { border-color: #6b21a8 !important; }

        /* 2. Pasteur: Black & Gold */
        .theme-pasteur, .theme-dirigeant {
            border-color: #d97706 !important;
        }
        .theme-pasteur .card-top, .theme-dirigeant .card-top {
            background: #090d16 !important;
            border-bottom: 0.5px solid #d97706 !important;
        }
        .theme-pasteur .member-badge, .theme-dirigeant .member-badge {
            background: #d97706 !important;
            color: #ffffff !important;
        }
        .theme-pasteur .label, .theme-dirigeant .label {
            color: #d97706 !important;
        }
        .theme-pasteur .first-name, .theme-dirigeant .first-name {
            color: #d97706 !important;
        }
        .theme-pasteur .code, .theme-dirigeant .code {
            background: #090d16 !important;
            border: 0.3px solid #d97706 !important;
            color: #fbbf24 !important;
        }
        .theme-pasteur .qr-label, .theme-dirigeant .qr-label {
            color: #d97706 !important;
        }
        .theme-pasteur .bible-verse, .theme-dirigeant .bible-verse {
            color: #b45309 !important;
        }
        .theme-pasteur .card-footer, .theme-dirigeant .card-footer {
            border-top-color: #fde68a !important;
            color: #d97706 !important;
        }
        .theme-pasteur .photo, .theme-dirigeant .photo { border-color: #d97706 !important; }
        .theme-pasteur .photo-empty, .theme-dirigeant .photo-empty { border-color: #d97706 !important; }

        /* 3. Évangéliste: Emerald Green & Gold */
        .theme-evangeliste {
            border-color: #059669 !important;
        }
        .theme-evangeliste .card-top {
            background: #064e3b !important;
            border-bottom: 0.5px solid #fbbf24 !important;
        }
        .theme-evangeliste .member-badge {
            background: #fbbf24 !important;
            color: #064e3b !important;
        }
        .theme-evangeliste .label {
            color: #059669 !important;
        }
        .theme-evangeliste .first-name {
            color: #047857 !important;
        }
        .theme-evangeliste .code {
            background: #064e3b !important;
            border: 0.3px solid #fbbf24 !important;
            color: #fbbf24 !important;
        }
        .theme-evangeliste .qr-label {
            color: #059669 !important;
        }
        .theme-evangeliste .bible-verse {
            color: #065f46 !important;
        }
        .theme-evangeliste .card-footer {
            border-top-color: #a7f3d0 !important;
            color: #059669 !important;
        }
        .theme-evangeliste .photo { border-color: #059669 !important; }
        .theme-evangeliste .photo-empty { border-color: #059669 !important; }

        /* 4. Anciens: Bronze / Amber */
        .theme-ancien {
            border-color: #b45309 !important;
        }
        .theme-ancien .card-top {
            background: #7c2d12 !important;
        }
        .theme-ancien .member-badge {
            background: #b45309 !important;
        }
        .theme-ancien .label {
            color: #b45309 !important;
        }
        .theme-ancien .first-name {
            color: #c2410c !important;
        }
        .theme-ancien .code {
            background: #7c2d12 !important;
        }
        .theme-ancien .qr-label {
            color: #b45309 !important;
        }
        .theme-ancien .bible-verse {
            color: #7c2d12 !important;
        }
        .theme-ancien .card-footer {
            border-top-color: #ffedd5 !important;
            color: #b45309 !important;
        }
        .theme-ancien .photo { border-color: #b45309 !important; }
        .theme-ancien .photo-empty { border-color: #b45309 !important; }

        /* 5. Diacres: Indigo / Lavender */
        .theme-diacre {
            border-color: #4f46e5 !important;
        }
        .theme-diacre .card-top {
            background: #312e81 !important;
        }
        .theme-diacre .member-badge {
            background: #4f46e5 !important;
        }
        .theme-diacre .label {
            color: #4f46e5 !important;
        }
        .theme-diacre .first-name {
            color: #6366f1 !important;
        }
        .theme-diacre .code {
            background: #312e81 !important;
        }
        .theme-diacre .qr-label {
            color: #4f46e5 !important;
        }
        .theme-diacre .bible-verse {
            color: #3730a3 !important;
        }
        .theme-diacre .card-footer {
            border-top-color: #e0e7ff !important;
            color: #4f46e5 !important;
        }
        .theme-diacre .photo { border-color: #4f46e5 !important; }
        .theme-diacre .photo-empty { border-color: #4f46e5 !important; }

        /* 6. Chorale: Sky Blue / Cyan */
        .theme-chorale {
            border-color: #0284c7 !important;
        }
        .theme-chorale .card-top {
            background: #0369a1 !important;
        }
        .theme-chorale .member-badge {
            background: #0284c7 !important;
        }
        .theme-chorale .label {
            color: #0284c7 !important;
        }
        .theme-chorale .first-name {
            color: #38bdf8 !important;
        }
        .theme-chorale .code {
            background: #0369a1 !important;
        }
        .theme-chorale .qr-label {
            color: #0284c7 !important;
        }
        .theme-chorale .bible-verse {
            color: #075985 !important;
        }
        .theme-chorale .card-footer {
            border-top-color: #e0f2fe !important;
            color: #0284c7 !important;
        }
        .theme-chorale .photo { border-color: #0284c7 !important; }
        .theme-chorale .photo-empty { border-color: #0284c7 !important; }

        /* 7. Communication: Crimson / Bordeaux & Ruby */
        .theme-communication {
            border-color: #b91c1c !important;
        }
        .theme-communication .card-top {
            background: #7f1d1d !important;
            border-bottom: 0.5px solid #f87171 !important;
        }
        .theme-communication .member-badge {
            background: #dc2626 !important;
            color: #ffffff !important;
        }
        .theme-communication .label {
            color: #b91c1c !important;
        }
        .theme-communication .first-name {
            color: #dc2626 !important;
        }
        .theme-communication .code {
            background: #7f1d1d !important;
            border: 0.3px solid #f87171 !important;
            color: #fecaca !important;
        }
        .theme-communication .qr-label {
            color: #b91c1c !important;
        }
        .theme-communication .bible-verse {
            color: #991b1b !important;
        }
        .theme-communication .card-footer {
            border-top-color: #fecaca !important;
            color: #b91c1c !important;
        }
        .theme-communication .photo { border-color: #b91c1c !important; }
        .theme-communication .photo-empty { border-color: #b91c1c !important; }

        /* Added fields styling */
        .theme-apotre .church-supername, .theme-dirigeant .church-supername, .theme-pasteur .church-supername {
            color: #fbbf24 !important;
            opacity: 1 !important;
        }
        .theme-communication .church-supername {
            color: #fecaca !important;
            opacity: 1 !important;
        }
        .bible-verse {
            font-size: 7.2px;
            font-weight: 700;
            color: #1e293b !important;
            font-style: italic;
            text-align: center;
            margin-top: 0.3mm;
            line-height: 1.25;
            opacity: 1 !important;
        }
        .card-footer {
            margin-top: 0.3mm;
            padding-top: 0.25mm;
            border-top: 0.3px solid #94a3b8;
            text-align: center;
            font-size: 7px;
            font-weight: 800;
            letter-spacing: 0.2px;
            color: #0f172a !important;
            opacity: 1 !important;
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
                        
                        $isApotre = false;
                        $isPasteur = false;
                        $isDirigeant = false;
                        $isEvangeliste = false;
                        $isAncien = false;
                        $isDiacre = false;
                        $isChorale = false;
                        $isCommunication = false;
                        
                        $isDeptHidden = false;
                        $themeClass = '';
                        $badgeLabel = '';
                        $displayDept = '';
                        
                        if ($card && ($card['type'] ?? '') === 'member') {
                            $normalize = function($str) {
                                if (!$str) return '';
                                $str = mb_strtolower(trim($str), 'UTF-8');
                                $str = str_replace(['é', 'è', 'ê', 'ë', 'É', 'È', 'Ê', 'Ë'], 'e', $str);
                                $str = str_replace(['à', 'â', 'ä', 'À', 'Â', 'Ä'], 'a', $str);
                                $str = str_replace(['ô', 'ö', 'Ô', 'Ö'], 'o', $str);
                                $str = str_replace(['û', 'ü', 'Û', 'Ü'], 'u', $str);
                                $str = str_replace(['ç', 'Ç'], 'c', $str);
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
                                if ($norm === 'apotre') {
                                    $isApotre = true;
                                }
                                if ($norm === 'pasteur') {
                                    $isPasteur = true;
                                }
                                if ($norm === 'dirigeant') {
                                    $isDirigeant = true;
                                }
                                if ($norm === 'evangeliste') {
                                    $isEvangeliste = true;
                                }
                                if ($norm === 'anciens' || $norm === 'ancien') {
                                    $isAncien = true;
                                }
                                if ($norm === 'diacres' || $norm === 'diacre') {
                                    $isDiacre = true;
                                }
                                if ($norm === 'chorale') {
                                    $isChorale = true;
                                }
                                if ($norm === 'communication') {
                                    $isCommunication = true;
                                }
                            }
                            
                            if ($isApotre) {
                                $themeClass = 'theme-apotre';
                                $badgeLabel = 'APÔTRE';
                            } elseif ($isPasteur || $isDirigeant) {
                                $themeClass = 'theme-pasteur';
                                $badgeLabel = $isDirigeant ? 'DIRIGEANT' : 'PASTEUR';
                            } elseif ($isEvangeliste) {
                                $themeClass = 'theme-evangeliste';
                                $badgeLabel = 'ÉVANGÉLISTE';
                            } elseif ($isAncien) {
                                $themeClass = 'theme-ancien';
                                $badgeLabel = 'ANCIEN';
                            } elseif ($isDiacre) {
                                $themeClass = 'theme-diacre';
                                $badgeLabel = 'DIACRE';
                            } elseif ($isChorale) {
                                $themeClass = 'theme-chorale';
                                $badgeLabel = 'CHORALE';
                            } elseif ($isCommunication) {
                                $themeClass = 'theme-communication';
                                $badgeLabel = 'COMMUNICATION';
                            } else {
                                $themeClass = '';
                                $badgeLabel = 'MEMBRE';
                            }
                            
                            $hiddenDepts = ['apotre', 'pasteur', 'dirigeant', 'evangeliste', 'anciens', 'ancien', 'diacres', 'diacre', 'chorale', 'nettoyage', 'protocole', 'communication', "culte d'enfant", 'moniteurs'];
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
                        } elseif ($card && ($card['type'] ?? '') === 'visitor') {
                            $badgeLabel = 'VISITEUR';
                        }
                    @endphp
                    <div class="cell">
                        <div class="card-bleed-box">
                            @if ($card)
                                <div class="card-inner {{ $themeClass }}">
                                <div class="card-top">
                                    <div class="brand-text">
                                        <div class="church-name">EGLISE DU CHRIST</div>
                                        <div class="church-supername">Decret No 71/DF/619 DU 14 DEC 1971</div>
                                        <div class="card-title">Carte d'identification</div>
                                    </div>
                                    <div class="brand-badge">
                                        <span class="member-badge">
                                            {{ $badgeLabel }}
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
                                    FB : Ministère Prophétique de la Foi
                                </div>
                                </div>
                            @endif
                        </div>
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
