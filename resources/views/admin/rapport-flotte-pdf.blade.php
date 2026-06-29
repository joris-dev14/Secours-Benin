<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; color: #0A2540; font-size: 13px; }
        .header { text-align: center; border-bottom: 3px solid #0A2540; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { font-size: 20px; margin: 0; }
        .header p { color: #64748b; margin: 5px 0 0; }
        .periode { background: #f8f9fa; padding: 10px 15px; border-radius: 8px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.kpi td { width: 50%; text-align: center; padding: 15px; border: 1px solid #e2e8f0; }
        table.kpi .value { font-size: 22px; font-weight: bold; color: #0A2540; }
        table.kpi .label { font-size: 11px; color: #64748b; text-transform: uppercase; }
        table.data th { background: #0A2540; color: white; padding: 8px; text-align: left; }
        table.data td { padding: 8px; border-bottom: 1px solid #f1f5f9; }
        .footer { margin-top: 30px; font-size: 10px; color: #94a3b8; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 10px; }
    </style>
    @include('partials.pwa-head')
</head>
<body>
    <div class="header">
        <h1>Secours Bénin — Rapport de performance flotte</h1>
        <p>Direction SAMU</p>
    </div>

    <div class="periode">
        <strong>Période :</strong> {{ $dateDebut->format('d/m/Y') }} au {{ $dateFin->format('d/m/Y') }}<br>
        <strong>Généré par :</strong> {{ $admin->nom ?? '—' }} {{ $admin->prenom ?? '' }} le {{ now()->format('d/m/Y à H:i') }}
    </div>

    <table class="kpi">
        <tr>
            <td>
                <div class="value">{{ $totalAmbulances }}</div>
                <div class="label">Ambulances enregistrées</div>
            </td>
            <td>
                <div class="value">{{ $ambulancesActives }}</div>
                <div class="label">Ambulances actives sur la période</div>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>Matricule</th>
                <th>Centre</th>
                <th>Missions</th>
                <th>Temps moyen</th>
                <th>Taux de réussite</th>
            </tr>
        </thead>
        <tbody>
            @forelse($performanceFlotte as $a)
                <tr>
                    <td>{{ $a->matricule }}</td>
                    <td>{{ $a->centre ?? '—' }}</td>
                    <td>{{ $a->total_missions }}</td>
                    <td>{{ round($a->temps_moyen, 1) }} min</td>
                    <td>{{ round($a->taux, 1) }}%</td>
                </tr>
            @empty
                <tr><td colspan="5">Aucune donnée pour cette période</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Document généré automatiquement par la plateforme Secours Bénin — Confidentiel
    </div>
     @include('partials.pwa-register')
</body>
</html>