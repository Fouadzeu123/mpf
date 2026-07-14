<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 10mm;
            size: a4 landscape;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8px;
            color: #1e293b;
            background: #fff;
            padding: 10px;
        }
        .header {
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 8px;
        }
        .header table {
            width: 100%;
            border-collapse: collapse;
        }
        .header td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        .church-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
        }
        .report-title {
            font-size: 12px;
            font-weight: bold;
            color: #475569;
            margin-top: 3px;
        }
        .report-meta {
            text-align: right;
            font-size: 8px;
            color: #64748b;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 5px;
        }
        .report-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7.5px;
            padding: 5px 6px;
            text-align: left;
            border: 0.5px solid #cbd5e1;
        }
        .report-table td {
            padding: 5px 6px;
            border: 0.5px solid #e2e8f0;
            font-size: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .report-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .report-table tr:hover {
            background-color: #f1f5f9;
        }
        .badge {
            display: inline-block;
            padding: 1px 4px;
            font-size: 7px;
            font-weight: bold;
            border-radius: 3px;
            color: #fff;
            background-color: #64748b;
            text-transform: uppercase;
        }
        .badge-active { background-color: #10b981; }
        .badge-inactive { background-color: #ef4444; }
        .badge-paid { background-color: #10b981; }
        .badge-free { background-color: #3b82f6; }
        .badge-remote { background-color: #8b5cf6; }
        .badge-onsite { background-color: #06b6d4; }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 15px;
            text-align: center;
            font-size: 7px;
            color: #94a3b8;
            border-top: 0.5px solid #e2e8f0;
            padding-top: 4px;
        }
    </style>
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="church-title">Ministère Prophétique de la Foi</div>
                    <div class="report-title">{{ $title }}</div>
                </td>
                <td class="report-meta">
                    Date d'exportation : {{ $dateStr }}<br>
                    Nombre total : {{ count($items) }}
                </td>
            </tr>
        </table>
    </div>

    <table class="report-table">
        <thead>
            @if($type === 'members')
                <tr>
                    <th style="width: 10%;">Code</th>
                    <th style="width: 22%;">Nom & Prénom</th>
                    <th style="width: 7%;">Genre</th>
                    <th style="width: 10%;">Date Naiss.</th>
                    <th style="width: 12%;">Téléphone</th>
                    <th style="width: 15%;">Profession</th>
                    <th style="width: 16%;">Département(s)</th>
                    <th style="width: 8%;">Statut</th>
                </tr>
            @elseif($type === 'attendances')
                <tr>
                    <th style="width: 15%;">Scanné le</th>
                    <th style="width: 10%;">Code Membre</th>
                    <th style="width: 25%;">Nom & Prénom</th>
                    <th style="width: 15%;">Type de Culte</th>
                    <th style="width: 15%;">Téléphone</th>
                    <th style="width: 20%;">Département(s)</th>
                </tr>
            @elseif($type === 'communion')
                <tr>
                    <th style="width: 13%;">Date Prép.</th>
                    <th style="width: 10%;">Code</th>
                    <th style="width: 20%;">Nom & Prénom</th>
                    <th style="width: 32%;">Verset</th>
                    <th style="width: 10%;">Mode</th>
                    <th style="width: 15%;">Paiement</th>
                </tr>
            @endif
        </thead>
        <tbody>
            @forelse($items as $item)
                @if($type === 'members')
                    <tr>
                        <td style="font-family: monospace;">{{ $item->member_code }}</td>
                        <td style="font-weight: bold; text-transform: uppercase;">{{ $item->full_name }}</td>
                        <td>{{ $item->gender ?: '-' }}</td>
                        <td>{{ $item->birth_date ? $item->birth_date->format('d/m/Y') : '-' }}</td>
                        <td>{{ $item->phone ?: '-' }}</td>
                        <td>{{ $item->profession ?: '-' }}</td>
                        <td>{{ $item->department ?: '-' }}</td>
                        <td>
                            <span class="badge {{ $item->status?->value === 'active' || $item->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                                {{ $item->status?->value === 'active' || $item->status === 'active' ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                    </tr>
                @elseif($type === 'attendances')
                    <tr>
                        <td>{{ $item->scanned_at ? $item->scanned_at->format('d/m/Y H:i') : '-' }}</td>
                        <td style="font-family: monospace;">{{ $item->member?->member_code }}</td>
                        <td style="font-weight: bold; text-transform: uppercase;">{{ $item->member?->full_name }}</td>
                        <td>{{ $item->service_type ? $item->service_type->label() : '-' }}</td>
                        <td>{{ $item->member?->phone ?: '-' }}</td>
                        <td>{{ $item->member?->department ?: '-' }}</td>
                    </tr>
                @elseif($type === 'communion')
                    <tr>
                        <td>{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}</td>
                        <td style="font-family: monospace;">{{ $item->member?->member_code }}</td>
                        <td style="font-weight: bold; text-transform: uppercase;">{{ $item->member?->full_name }}</td>
                        <td title="{{ $item->verse_text }}">
                            <strong>{{ $item->verse_reference }}</strong> : {{ $item->verse_text }}
                        </td>
                        <td>
                            <span class="badge {{ $item->remote ? 'badge-remote' : 'badge-onsite' }}">
                                {{ $item->remote ? 'À distance' : 'Sur place' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $item->payment_status?->value === 'paid' || $item->payment_status === 'paid' ? 'badge-paid' : 'badge-free' }}">
                                {{ $item->payment_status?->value === 'paid' || $item->payment_status === 'paid' ? 'Payé' : 'Gratuit' }}
                            </span>
                            @if($item->payment_reference)
                                <span style="font-size: 7px; color: #64748b;">({{ $item->payment_reference }})</span>
                            @endif
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #64748b; padding: 15px;">Aucune donnée disponible.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        © {{ date('Y') }} Ministère Prophétique de la Foi • Généré automatiquement par la Plateforme PASDEBOC
    </div>
</body>
</html>
