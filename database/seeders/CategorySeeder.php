<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Nasional',
            'Ekonomi',
            'Olahraga',
            'Teknologi',
            'Hiburan',
            'Politik',
            'Kesehatan',
            'Pendidikan',
        ];

        foreach ($categories as $name) {
            Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }

        $this->command->info(count($categories) . ' kategori berhasil dibuat.');
    }
}