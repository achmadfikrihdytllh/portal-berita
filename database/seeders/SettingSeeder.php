<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // group: general
            ['key' => 'site_name', 'value' => 'Portal Berita', 'type' => 'text', 'group' => 'general', 'label' => 'Nama Situs'],
            ['key' => 'site_tagline', 'value' => 'Berita terkini & terpercaya', 'type' => 'text', 'group' => 'general', 'label' => 'Tagline'],

            // group: appearance -> ini yang dipakai untuk custom warna & logo
            ['key' => 'logo', 'value' => null, 'type' => 'image', 'group' => 'appearance', 'label' => 'Logo Situs'],
            ['key' => 'favicon', 'value' => null, 'type' => 'image', 'group' => 'appearance', 'label' => 'Favicon'],
            ['key' => 'color_primary', 'value' => '#2563eb', 'type' => 'color', 'group' => 'appearance', 'label' => 'Warna Utama'],
            ['key' => 'color_secondary', 'value' => '#1e40af', 'type' => 'color', 'group' => 'appearance', 'label' => 'Warna Sekunder'],
            ['key' => 'color_header_bg', 'value' => '#ffffff', 'type' => 'color', 'group' => 'appearance', 'label' => 'Warna Latar Header'],
            ['key' => 'color_header_text', 'value' => '#111827', 'type' => 'color', 'group' => 'appearance', 'label' => 'Warna Teks Header'],
            ['key' => 'color_footer_bg', 'value' => '#111827', 'type' => 'color', 'group' => 'appearance', 'label' => 'Warna Latar Footer'],
            ['key' => 'color_footer_text', 'value' => '#f9fafb', 'type' => 'color', 'group' => 'appearance', 'label' => 'Warna Teks Footer'],

            // group: seo
            ['key' => 'meta_description', 'value' => 'Portal berita terpercaya.', 'type' => 'text', 'group' => 'seo', 'label' => 'Meta Description'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
