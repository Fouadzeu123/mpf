<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 12mm 10mm;
            size: a4 portrait;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #0f172a;
            background: #ffffff;
            opacity: 1 !important;
            padding: 5px;
        }
        .header {
            width: 100%;
            margin-bottom: 12px;
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
            font-size: 13px;
            font-weight: 800;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .report-title {
            font-size: 11px;
            font-weight: 700;
            color: #334155;
            margin-top: 3px;
        }
        .report-meta {
            text-align: right;
            font-size: 8.5px;
            color: #475569;
            line-height: 1.3;
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
            font-weight: 800;
            text-transform: uppercase;
            font-size: 8.5px;
            padding: 6px 8px;
            text-align: left;
            border: 0.5px solid #cbd5e1;
            letter-spacing: 0.3px;
        }
        .report-table td {
            padding: 5.5px 8px;
            border: 0.5px solid #e2e8f0;
            font-size: 8.5px;
            color: #0f172a;
            vertical-align: middle;
        }
        .report-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .name-cell {
            font-weight: 800;
            text-transform: uppercase;
            color: #0f172a;
        }
        .phone-cell {
            font-family: monospace;
            font-size: 8.5px;
            color: #1e293b;
            font-weight: 600;
        }
        .time-cell {
            font-weight: 700;
            color: #1e3a8a;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 15px;
            text-align: center;
            font-size: 7.5px;
            color: #64748b;
            border-top: 0.5px solid #e2e8f0;
            padding-top: 3px;
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
                    Date d'exportation : <strong>{{ $dateStr }}</strong><br>
                    Total enregistrements : <strong>{{ count($items) }}</strong>
                </td>
            </tr>
        </table>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 38%;">Nom et Prénom</th>
                <th style="width: 22%;">Numéro de téléphone</th>
                <th style="width: 22%;">Département</th>
                <th style="width: 18%;">Heure d'arrivée</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                @php
                    $fullName = '—';
                    $phone = '—';
                    $department = '—';
                    $arrivalTime = '—';

                    if ($type === 'attendances') {
                        $fullName = $item->member?->full_name ?: '—';
                        $phone = $item->member?->phone ?: '—';
                        $department = $item->member?->department ?: '—';
                        $arrivalTime = $item->scanned_at ? $item->scanned_at->format('H:i') . ($item->scanned_at->isToday() ? '' : ' (' . $item->scanned_at->format('d/m/Y') . ')') : '—';
                    } elseif ($type === 'communion') {
                        $fullName = $item->member?->full_name ?: '—';
                        $phone = $item->member?->phone ?: '—';
                        $department = $item->member?->department ?: '—';
                        $arrivalTime = $item->created_at ? $item->created_at->format('H:i') . ($item->created_at->isToday() ? '' : ' (' . $item->created_at->format('d/m/Y') . ')') : '—';
                    } elseif ($type === 'absents_cultes') {
                        $fullName = $item->full_name ?: '—';
                        $phone = $item->phone ?: '—';
                        $department = $item->department ?: '—';
                        $arrivalTime = 'Absent';
                    } elseif ($type === 'not_prepared_communion') {
                        $fullName = $item->full_name ?: '—';
                        $phone = $item->phone ?: '—';
                        $department = $item->department ?: '—';
                        $arrivalTime = 'Non préparé';
                    } else {
                        // members list
                        $fullName = $item->full_name ?: '—';
                        $phone = $item->phone ?: '—';
                        $department = $item->department ?: '—';
                        $arrivalTime = '—';
                    }
                @endphp
                <tr>
                    <td class="name-cell">{{ $fullName }}</td>
                    <td class="phone-cell">{{ $phone }}</td>
                    <td>{{ $department }}</td>
                    <td class="time-cell">{{ $arrivalTime }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #64748b; padding: 20px; font-style: italic;">
                        Aucune donnée disponible.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        © {{ date('Y') }} Ministère Prophétique de la Foi • Rapport généré au format A4
    </div>
</body>
</html>
