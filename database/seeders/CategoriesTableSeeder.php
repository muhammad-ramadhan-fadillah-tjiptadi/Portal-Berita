<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Nasional',
                'description' => 'Berita dalam negeri dan isu-isu nasional',
            ],
            [
                'name' => 'Internasional',
                'description' => 'Berita mancanegara dan isu global',
            ],
            [
                'name' => 'Olahraga',
                'description' => 'Berita dan informasi seputar olahraga',
            ],
        ];

        foreach ($categories as $category) {
            Categorie::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
