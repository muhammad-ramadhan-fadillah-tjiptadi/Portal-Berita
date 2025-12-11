<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles
{
    /**
     * Mengambil data users untuk export
     */
    public function collection()
    {
        return User::withCount(['posts', 'comments'])
            ->latest()
            ->get()
            ->map(function ($user) {
                return [
                    'ID' => $user->id,
                    'Nama User' => $user->name,
                    'Email' => $user->email,
                    'Role' => ucfirst($user->role),
                    'Jumlah Artikel' => $user->posts_count ?? 0,
                    'Jumlah Komentar' => $user->comments_count ?? 0,
                    'Dibuat' => $user->created_at->format('d-m-Y H:i'),
                    'Diupdate' => $user->updated_at->format('d-m-Y H:i'),
                ];
            });
    }

    /**
     * Header untuk Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama User',
            'Email',
            'Role',
            'Jumlah Artikel',
            'Jumlah Komentar',
            'Dibuat',
            'Diupdate',
        ];
    }

    /**
     * Lebar kolom Excel
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,  // ID
            'B' => 30, // Nama User
            'C' => 35, // Email
            'D' => 15, // Role
            'E' => 20, // Jumlah Artikel
            'F' => 20, // Jumlah Komentar
            'G' => 20, // Dibuat
            'H' => 20, // Diupdate
        ];
    }

    /**
     * Styling untuk Excel
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk header
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '0d6efd'
                    ]
                ],
                'font' => [
                    'color' => [
                        'rgb' => 'FFFFFF'
                    ]
                ]
            ],
            // Border untuk semua data
            'A1:H' . (User::count() + 1) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }
}
