<?php

namespace App\Exports;

use App\Models\SubCategorie;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SubcategoriesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return SubCategorie::with('category')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Sub Kategori',
            'Kategori Induk',
            'Deskripsi'
        ];
    }

    /**
     * @param mixed $subcategory
     *
     * @return array
     */
    public function map($subcategory): array
    {
        static $i = 1;
        return [
            $i++,
            $subcategory->name,
            $subcategory->category->name ?? '-',
            $subcategory->description ?? '-',
        ];
    }
}
