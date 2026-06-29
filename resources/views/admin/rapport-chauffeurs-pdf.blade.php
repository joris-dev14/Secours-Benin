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
        table { width: 100%; border-collapse: collapse; }
        table.data th { background: #0A2540; color: white; padding: 8px; text-align: left; }
        table.data td { padding: 8px; border-bottom: 1px solid #f1f5f9; }
        .rang { font-weight: bold; }
        .footer { margin-top: 30px; font-size: 10px; color: #94a3b8; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 10px; }
    </style>
    @include('partials.pwa-head')
</head>
<body>
    <div class="header">
        <h1>Secours Bénin — Rapport de performance chauffeurs</h1>
        <p>Direction SAMU</p>
    </div>

    <div class="periode">
        <strong>Période :</strong> {{ $dateDebut->format('d/m/Y') }} au {{ $dateFin->format('d/m/Y') }}<br>
        <strong>Généré par :</strong> {{ $admin->nom ?? '—' }} {{ $admin->prenom ?? '' }} le {{ now()->format('d/m/Y à H:i') }}
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>Rang</th>
                <th>Nom</th>
                <th>Matricule</th>
                <th>Missions terminées</th>
                <th>Temps moyen</th>
            </tr>
        </thead>
        <tbody>
            @forelse($performanceChauffeurs as $index => $c)
                <tr>
                    <td class="rang">#{{ $index + 1 }}</td>
                    <td>{{ $c->nom }} {{ $c->prenom }}</td>
                    <td>{{ $c->matricule }}</td>
                    <td>{{ $c->total_missions }}</td>
                    <td>{{ round($c->temps_moyen, 1) }} min</td>
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