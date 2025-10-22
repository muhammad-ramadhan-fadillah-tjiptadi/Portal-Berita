<?php

namespace App\Exports;

use App\Models\Categorie;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategoriesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Categorie::withCount('posts')->get();
    }

    /**
     * @var Categorie $category
     */
    public function map($category): array
    {
        return [
            $category->id,
            $category->name,
            $category->slug,
            $category->posts_count,
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kategori',
            'Slug',
            'Jumlah Artikel',
        ];
    }
}
