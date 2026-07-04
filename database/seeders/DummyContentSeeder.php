<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Epaper;
use App\Models\Focus;
use App\Models\News;
use App\Models\PhotoGallery;
use App\Models\PhotoGalleryImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DummyContentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        $this->seedFocuses($faker);
        $this->seedEpapers($faker);
        $this->seedPhotoGalleries($faker);

        $this->command->info('Dummy Fokus, E-koran, dan Galeri Foto berhasil dibuat.');
    }

    // ============ FOKUS ============
    private function seedFocuses(\Faker\Generator $faker): void
    {
        $newsIds = News::published()->pluck('id');

        if ($newsIds->isEmpty()) {
            $this->command->warn('Belum ada berita, Fokus dilewati. Jalankan NewsSeeder dulu.');
            return;
        }

        for ($i = 1; $i <= 5; $i++) {
            $title = rtrim($faker->sentence(rand(4, 8)), '.');
            $cover = $this->downloadRandomImage('focus-' . $i, 'focuses');

            $focus = Focus::create([
                'title'       => $title,
                'slug'        => Str::slug($title),
                'description' => $faker->paragraph(3),
                'cover_image' => $cover,
                'is_active'   => true,
            ]);

            // Pilih 3-6 berita random untuk dimasukkan ke fokus ini, dengan urutan
            $selected = $newsIds->random(min(rand(3, 6), $newsIds->count()));
            $order = 0;
            foreach ($selected as $newsId) {
                $focus->news()->attach($newsId, ['order' => $order++]);
            }

            $this->command->info("Fokus #{$i} dibuat: {$title}");
        }
    }

    // ============ E-KORAN ============
    private function seedEpapers(\Faker\Generator $faker): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $date = now()->subDays($i - 1);
            $cover = $this->downloadRandomImage('epaper-' . $i, 'epapers');

            Epaper::create([
                'title'        => 'Edisi ' . $date->translatedFormat('l, d F Y'),
                'edition_date' => $date->toDateString(),
                'cover_image'  => $cover,
                'file_path'    => null, // butuh PDF asli, dikosongkan dulu
                'is_published' => true,
            ]);

            $this->command->info("E-koran edisi {$date->toDateString()} dibuat");
        }
    }

    // ============ GALERI FOTO ============
    private function seedPhotoGalleries(\Faker\Generator $faker): void
    {
        $categories = Category::all();
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('Belum ada user, Galeri Foto dilewati.');
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            $title = rtrim($faker->sentence(rand(4, 7)), '.');
            $cover = $this->downloadRandomImage('gallery-cover-' . $i, 'galleries');

            $gallery = PhotoGallery::create([
                'user_id'      => $users->random()->id,
                'category_id'  => $categories->isNotEmpty() ? $categories->random()->id : null,
                'title'        => $title,
                'slug'         => Str::slug($title) . '-' . Str::random(6),
                'description'  => $faker->paragraph(2),
                'cover_image'  => $cover,
                'status'       => 'published',
                'published_at' => $faker->dateTimeBetween('-30 days', 'now'),
            ]);

            // Setiap galeri diisi 4-8 foto
            $imageCount = rand(4, 8);
            for ($j = 1; $j <= $imageCount; $j++) {
                $imagePath = $this->downloadRandomImage("gallery-{$i}-img-{$j}", 'galleries');

                if ($imagePath) {
                    PhotoGalleryImage::create([
                        'photo_gallery_id' => $gallery->id,
                        'image_path'       => $imagePath,
                        'caption'          => $faker->sentence(6),
                        'order'            => $j,
                    ]);
                }
            }

            $this->command->info("Galeri #{$i} dibuat: {$title} ({$imageCount} foto)");
        }
    }

    // ============ HELPER DOWNLOAD GAMBAR ============
    private function downloadRandomImage(string $seed, string $folder): ?string
    {
        try {
            $response = Http::timeout(10)->get("https://picsum.photos/seed/{$seed}/800/500");

            if (! $response->successful()) {
                return null;
            }

            $filename = $folder . '/' . Str::random(20) . '.jpg';
            Storage::disk('public')->put($filename, $response->body());

            return $filename;
        } catch (\Throwable $e) {
            $this->command->warn("Gagal download gambar ({$seed}): {$e->getMessage()}");
            return null;
        }
    }
}