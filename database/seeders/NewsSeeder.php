<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $users = User::all();

        if ($categories->isEmpty() || $users->isEmpty()) {
            $this->command->error('Pastikan sudah ada minimal 1 kategori dan 1 user sebelum menjalankan seeder ini.');
            return;
        }

        $faker = \Faker\Factory::create('id_ID');

        for ($i = 1; $i <= 50; $i++) {
            $title = rtrim($faker->sentence(rand(6, 12)), '.');
            $publishedAt = $faker->dateTimeBetween('-30 days', 'now');

            $thumbnailPath = $this->downloadRandomImage($i);

            News::create([
                'user_id'          => $users->random()->id,
                'category_id'      => $categories->random()->id,
                'title'            => $title,
                'slug'             => Str::slug($title) . '-' . Str::random(6),
                'excerpt'          => $faker->sentence(20),
                'content'          => collect(range(1, rand(4, 8)))
                    ->map(fn () => '<p>' . $faker->paragraph(rand(4, 8)) . '</p>')
                    ->implode(''),
                'thumbnail'        => $thumbnailPath,
                'status'           => 'published',
                'is_headline'      => $i === 1,
                'is_breaking'      => $i <= 3,
                'views'            => rand(10, 5000),
                'published_at'     => $publishedAt,
                'meta_title'       => $title,
                'meta_description' => $faker->sentence(15),
                'og_image'         => $thumbnailPath,
            ]);

            $this->command->info("Berita #{$i} dibuat" . ($thumbnailPath ? ' (dengan gambar)' : ' (tanpa gambar)'));
        }

        $this->command->info('50 berita dummy berhasil dibuat.');
    }

    private function downloadRandomImage(int $seed): ?string
    {
        try {
            $response = Http::timeout(10)->get("https://picsum.photos/seed/berita{$seed}/800/500");

            if (! $response->successful()) {
                return null;
            }

            $filename = 'news/' . Str::random(20) . '.jpg';
            Storage::disk('public')->put($filename, $response->body());

            return $filename;
        } catch (\Throwable $e) {
            $this->command->warn("Gagal download gambar untuk berita #{$seed}: {$e->getMessage()}");
            return null;
        }
    }
}