<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Admin - Secours Bénin</title>
    <style>
        body { font-family: Arial, sans-serif; color: #0A2540; font-size: 13px; }
        .header { text-align: center; border-bottom: 3px solid #FF5252; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { font-size: 22px; margin: 0; }
        .header p { color: #666; margin: 5px 0 0; }
        .kpi-row { display: flex; gap: 15px; margin-bottom: 25px; }
        .kpi-box { flex: 1; background: #f8f9fa; border-left: 4px solid #0A2540; padding: 12px; border-radius: 8px; }
        .kpi-box.red { border-left-color: #FF5252; }
        .kpi-box.green { border-left-color: #10B981; }
        .kpi-box .value { font-size: 24px; font-weight: bold; }
        .kpi-box .label { font-size: 11px; color: #666; text-transform: uppercase; }
        h2 { font-size: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px; margin-top: 25px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        thead { background: #0A2540; color: white; }
        th { padding: 10px; text-align: left; font-size: 12px; }
        td { padding: 9px 10px; border-bottom: 1px solid #f1f5f9; font-size: 12px; }
        tr:nth-child(even) { background: #f8f9fa; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; }
        .excellent { background: #d1fae5; color: #059669; }
        .good { background: #dbeafe; color: #2563eb; }
        .average { background: #fef3c7; color: #d97706; }
        .footer { margin-top: 30px; text-align: center; font-size: 11px; color: #999; border-top: 1px solid #e2e8f0; padding-top: 10px; }
    </style>
    @include('partials.pwa-head')
</head>
<body>

    <div class="header">
        <h1>Secours Bénin — Rapport Administratif</h1>
        <p>Généré le {{ now()->locale('fr')->isoFormat('D MMMM YYYY') }} • Par {{ $admin->nom ?? 'Administrateur' }}</p>
    </div>

    <!-- KPIs -->
    <div class="kpi-row">
        <div class="kpi-box red">
            <div class="value">{{ $totalAlertesMois }}</div>
            <div class="label">Alertes ce mois</div>
        </div>
        <div class="kpi-box green">
            <div class="value">{{ $tauxTraitement }}%</div>
            <div class="label">Taux de traitement</div>
        </div>
    </div>

    <!-- Répartition par commune -->
    <h2>Répartition des alertes par commune</h2>
    <table>
        <thead>
            <tr>
                <th>Commune</th>
                <th>Nombre d'alertes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($parCommune as $ligne)
                <tr>
                    <td>{{ ucfirst($ligne->commune) }}</td>
                    <td>{{ $ligne->total }}</td>
                </tr>
            @empty
                <tr><td colspan="2">Aucune donnée</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Performance centres -->
    <h2>Performance des centres de régulation</h2>
    <table>
        <thead>
            <tr>
                <th>Centre</th>
                <th>Alertes</th>
                <th>Temps moyen</th>
                <th>Taux</th>
                <th>Performance</th>
            </tr>
        </thead>
        <tbody>
            @forelse($performanceCentres as $centre)
                <tr>
                    <td>{{ $centre->centre ?? 'Non défini' }}</td>
                    <td>{{ $centre->total_alertes }}</td>
                    <td>{{ round($centre->temps_moyen, 1) }} min</td>
                    <td>{{ round($centre->taux, 1) }}%</td>
                    <td>
                        @if($centre->taux >= 95)
                            <span class="badge excellent">Excellent</span>
                        @elseif($centre->taux >= 85)
                            <span class="badge good">Bon</span>
                        @else
                            <span class="badge average">Moyen</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">Aucune donnée</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Secours Bénin — Rapport confidentiel réservé à la Direction SAMU • {{ now()->format('Y') }}
    </div>
 @include('partials.pwa-register')


</body>
</html>