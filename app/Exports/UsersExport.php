<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::select('id', 'prenom', 'nom', 'email', 'is_admin', 'created_at')
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Prénom',
            'Nom',
            'Email',
            'Rôle',
            'Inscrit le',
            'Dernière connexion',
        ];
    }

    /**
     * @param mixed $user
     *
     * @return array
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->prenom,
            $user->nom,
            $user->email,
            $user->is_admin ? 'Administrateur' : 'Utilisateur',
            $user->created_at->format('d/m/Y H:i'),
            $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Jamais',
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style de la première ligne (en-têtes)
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'f8f9fa']
                ]
            ],
        ];
    }
}
