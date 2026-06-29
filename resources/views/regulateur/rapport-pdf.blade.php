<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Secours Bénin</title>
    <style>
        body { font-family: Arial, sans-serif; color: #0A2540; font-size: 13px; }
        .header { text-align: center; border-bottom: 3px solid #FF5252; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { font-size: 22px; margin: 0; }
        .header p { color: #666; margin: 5px 0 0; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; }
        .badge-success { background: #d1fae5; color: #059669; }
        .badge-danger { background: #fee2e2; color: #dc2626; }
        .badge-warning { background: #fef3c7; color: #d97706; }
        .kpi-row { display: flex; gap: 15px; margin-bottom: 25px; }
        .kpi-box { flex: 1; background: #f8f9fa; border-left: 4px solid #0A2540; padding: 12px; border-radius: 8px; }
        .kpi-box.red { border-left-color: #FF5252; }
        .kpi-box .value { font-size: 24px; font-weight: bold; }
        .kpi-box .label { font-size: 11px; color: #666; text-transform: uppercase; }
        h2 { font-size: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px; margin-top: 25px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        thead { background: #0A2540; color: white; }
        th { padding: 10px; text-align: left; font-size: 12px; }
        td { padding: 9px 10px; border-bottom: 1px solid #f1f5f9; font-size: 12px; }
        tr:nth-child(even) { background: #f8f9fa; }
        .footer { margin-top: 30px; text-align: center; font-size: 11px; color: #999; border-top: 1px solid #e2e8f0; padding-top: 10px; }
    </style>
    @include('partials.pwa-head')
</head>
<body>

    <div class="header">
<h1><span style="color: #FF5252;">+</span> Secours Bénin — Rapport Mensuel</h1>        <p>Généré le {{ now()->locale('fr')->isoFormat('D MMMM YYYY') }} • Par {{ $regulateur->prenom }} {{ $regulateur->nom }}</p>
    </div>

    <!-- KPIs -->
    <div class="kpi-row">
        <div class="kpi-box">
            <div class="value">{{ $totalAlertesMois }}</div>
            <div class="label">Total alertes (mois)</div>
        </div>
        <div class="kpi-box">
            <div class="value">{{ $tauxTraitement }}%</div>
            <div class="label">Taux de traitement</div>
        </div>
        <div class="kpi-box red">
            <div class="value">{{ $faussesAlertes }}</div>
            <div class="label">Fausses alertes</div>
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

    <!-- Alertes récentes -->
    <h2>10 dernières alertes</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Commune</th>
                <th>Statut</th>
                <th>Citoyen</th>
            </tr>
        </thead>
        <tbody>
            @forelse($alertesRecentes as $alerte)
                <tr>
                    <td>{{ $alerte->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ ucfirst($alerte->commune) }}</td>
                    <td>{{ strtoupper(str_replace('_', ' ', $alerte->statut)) }}</td>
                    <td>{{ $alerte->citoyen->telephone ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="4">Aucune alerte</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Secours Bénin — Rapport confidentiel réservé au personnel SAMU • {{ now()->format('Y') }}
    </div>
@include('partials.pwa-register')
</body>
</html>