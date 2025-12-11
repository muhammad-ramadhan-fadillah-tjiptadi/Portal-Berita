<?php

namespace App\Exports;

use App\Models\Tag;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TagsExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles
{
    /**
     * Mengambil data tags untuk export
     */
    public function collection()
    {
        return Tag::withCount('posts')
            ->latest()
            ->get()
            ->map(function ($tag) {
                return [
                    'ID' => $tag->id,
                    'Nama Tag' => $tag->name,
                    'Slug' => $tag->slug,
                    'Jumlah Artikel Yang Memakai Tag' => $tag->posts_count ?? 0,
                    'Dibuat' => $tag->created_at->format('d-m-Y H:i'),
                    'Diupdate' => $tag->updated_at->format('d-m-Y H:i'),
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
            'Nama Tag',
            'Slug',
            'Jumlah Artikel Yang Memakai Tag',
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
            'B' => 25, // Nama Tag
            'C' => 30, // Slug
            'D' => 30, // Jumlah Artikel
            'E' => 20, // Dibuat
            'F' => 20, // Diupdate
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
                        'rgb' => '4F46E5'
                    ]
                ],
                'font' => [
                    'color' => [
                        'rgb' => 'FFFFFF'
                    ]
                ]
            ],
            // Border untuk semua data
            'A1:F' . (Tag::count() + 1) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }
}
