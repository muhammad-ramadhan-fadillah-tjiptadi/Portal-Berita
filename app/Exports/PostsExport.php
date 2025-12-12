<?php

namespace App\Exports;

use App\Models\Post;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PostsExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles
{
    /**
     * Mengambil data posts untuk export
     */
    public function collection()
    {
        return Post::with(['user', 'category', 'subCategory', 'tags'])
            ->latest()
            ->get()
            ->map(function ($post) {
                // Format tags sebagai string
                $tags = $post->tags->pluck('name')->implode(', ');

                return [
                    'ID' => $post->id,
                    'Judul' => $post->title,
                    'Slug' => $post->slug,
                    'Penulis' => $post->user ? $post->user->name : 'Unknown',
                    'Kategori' => $post->category ? $post->category->name : 'No Category',
                    'Subkategori' => $post->subCategory ? $post->subCategory->name : '-',
                    'Tags' => $tags ?: '-',
                    'Status' => ucfirst($post->status),
                    'Dibuat' => $post->created_at->format('d-m-Y H:i'),
                    'Diupdate' => $post->updated_at->format('d-m-Y H:i'),
                    'Dipublish' => $post->published_at ? $post->published_at->format('d-m-Y H:i') : '-',
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
            'Judul',
            'Slug',
            'Penulis',
            'Kategori',
            'Subkategori',
            'Tags',
            'Status',
            'Dibuat',
            'Diupdate',
            'Dipublish',
        ];
    }

    /**
     * Lebar kolom Excel
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 40,  // Judul
            'C' => 30,  // Slug
            'D' => 25,  // Penulis
            'E' => 20,  // Kategori
            'F' => 20,  // Subkategori
            'G' => 30,  // Tags
            'H' => 15,  // Status
            'I' => 20,  // Dibuat
            'J' => 20,  // Diupdate
            'K' => 20,  // Dipublish
        ];
    }

    /**
     * Styling untuk Excel
     */
    public function styles(Worksheet $sheet)
    {
        $rowCount = Post::count() + 1;

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
            'A1:K' . $rowCount => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
            // Text wrap untuk kolom konten
            'B' => [
                'alignment' => [
                    'wrapText' => true,
                ]
            ],
            'G' => [
                'alignment' => [
                    'wrapText' => true,
                ]
            ]
        ];
    }
}
