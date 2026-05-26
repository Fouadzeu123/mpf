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
            border: 0.5px solid #d4a017;
            border-radius: 2mm;
            padding: 2mm;
            background: #fffbeb;
            overflow: hidden;
        }
        .card-top {
            display: table;
            width: 100%;
            padding: 1.2mm 1.5mm;
            border-radius: 1.5mm;
            background: #0f172a;
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
            color: #fde68a;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-top: 0.4mm;
        }
        .member-badge {
            display: inline-block;
            font-size: 4.8px;
            font-weight: bold;
            color: #0f172a;
            background: #fbbf24;
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
            border: 0.5px solid #f59e0b;
            background: #f8fafc;
        }
        .photo-empty {
            width: 18mm;
            height: 22mm;
            background: #fef3c7;
            border-radius: 1.4mm;
            border: 0.5px solid #f59e0b;
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
            color: #92400e;
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
            border-bottom: 0.2px solid #fde68a;
        }
        .label {
            font-size: 4.6px;
            font-weight: bold;
            color: #92400e;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .code {
            display: inline-block;
            font-size: 5.6px;
            font-weight: bold;
            color: #ffffff;
            background: #b45309;
            border-radius: 4mm;
            padding: 0.5mm 1.2mm;
            margin-top: 0.8mm;
        }
        .qr-col img {
            width: 18.5mm;
            height: 18.5mm;
            border: 0.3px solid #fde68a;
            border-radius: 1mm;
            background: #ffffff;
            padding: 0.8mm;
        }
        .qr-label {
            font-size: 4.4px;
            font-weight: bold;
            color: #92400e;
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
            background: #ffffff;
            border: 0.25px solid #fde68a;
            line-height: 1.25;
        }
        .visitor-tag {
            display: inline-block;
            font-size: 5px;
            font-weight: bold;
            color: #92400e;
            border: 0.3px solid #b8860b;
            padding: 0.2mm 1mm;
            border-radius: 0.5mm;
            margin-top: 0.5mm;
        }
        .cut-guide {
            border: 0.25px dashed #e2e8f0;
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
                    @endphp
                    <div class="cell cut-guide">
                        @if ($card)
                            <div class="card-inner">
                                <div class="card-top">
                                    @if (!empty($logoDataUri))
                                        <div class="brand-logo"><img src="{{ $logoDataUri }}" class="logo" alt=""></div>
                                    @endif
                                    <div class="brand-text">
                                        <div class="church-name">{{ $churchName }}</div>
                                        <div class="card-title">Carte d'identification</div>
                                    </div>
                                    <div class="brand-badge">
                                        <span class="member-badge">{{ (($card['type'] ?? '') === 'visitor') ? 'VISITEUR' : 'MEMBRE' }}</span>
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
                                                <div class="line"><span class="label">Sexe</span> : {{ $card['gender'] ?: '-' }} &nbsp; <span class="label">Age</span> : {{ $card['age'] ? $card['age'].' ans' : '-' }}</div>
                                                <div class="line"><span class="label">Adresse</span> : {{ $card['address'] ?: '-' }}</div>
                                                <div class="line"><span class="label">Département</span> : {{ $card['department'] ?: '-' }}</div>
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
                                <div class="programs">
                                    @foreach($programs as $program)
                                        {{ $program }}@if(! $loop->last) • @endif
                                    @endforeach
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
