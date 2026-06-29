<?php

namespace App\Exports;

use App\Models\Alerte;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AlertesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Alerte::with('citoyen')->latest()->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Commune',
            'Statut',
            'Latitude',
            'Longitude',
            'Description',
            'Citoyen',
            'Date création',
        ];
    }

    public function map($alerte): array
    {
        return [
            $alerte->id,
            $alerte->commune,
            $alerte->statut,
            $alerte->latitude,
            $alerte->longitude,
            $alerte->description ?? '—',
            $alerte->citoyen->telephone ?? '—',
            $alerte->created_at->format('d/m/Y H:i'),
        ];
    }
}