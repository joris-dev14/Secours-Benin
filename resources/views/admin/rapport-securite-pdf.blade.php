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
        table.kpi td { width: 20%; text-align: center; padding: 12px 6px; border: 1px solid #e2e8f0; }
        table.kpi .value { font-size: 18px; font-weight: bold; color: #0A2540; }
        table.kpi .label { font-size: 10px; color: #64748b; text-transform: uppercase; }
        table.data th { background: #0A2540; color: white; padding: 8px; text-align: left; }
        table.data td { padding: 8px; border-bottom: 1px solid #f1f5f9; }
        .footer { margin-top: 30px; font-size: 10px; color: #94a3b8; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 10px; }
    </style>
    @include('partials.pwa-head')
</head>
<body>
    <div class="header">
        <h1>Secours Bénin — Rapport sécurité et modération</h1>
        <p>Direction SAMU</p>
    </div>

    <div class="periode">
        <strong>Période :</strong> {{ $dateDebut->format('d/m/Y') }} au {{ $dateFin->format('d/m/Y') }}<br>
        <strong>Généré par :</strong> {{ $admin->nom ?? '—' }} {{ $admin->prenom ?? '' }} le {{ now()->format('d/m/Y à H:i') }}
    </div>

    <table class="kpi">
        <tr>
            <td>
                <div class="value">{{ $totalSignalements }}</div>
                <div class="label">Signalements</div>
            </td>
            <td>
                <div class="value">{{ $enAttente }}</div>
                <div class="label">En attente</div>
            </td>
            <td>
                <div class="value">{{ $fausses }}</div>
                <div class="label">Fausses alertes</div>
            </td>
            <td>
                <div class="value">{{ $classees }}</div>
                <div class="label">Classées légitimes</div>
            </td>
            <td>
                <div class="value">{{ $comptesBloques }}</div>
                <div class="label">Comptes bloqués (total)</div>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>Date</th>
                <th>Commune</th>
                <th>Motif</th>
                <th>Régulateur</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($listeSignalements as $s)
                <tr>
                    <td>{{ $s->created_at->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($s->alerte->commune ?? '—') }}</td>
                    <td>{{ $s->motif }}</td>
                    <td>{{ $s->regulateur->nom ?? '—' }} {{ $s->regulateur->prenom ?? '' }}</td>
                    <td>{{ $s->statut == 'traite' ? 'Bloqué' : ($s->statut == 'classe' ? 'Classée' : 'En attente') }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Aucun signalement pour cette période</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Document généré automatiquement par la plateforme Secours Bénin — Confidentiel
    </div>
     @include('partials.pwa-register')
</body>
</html>