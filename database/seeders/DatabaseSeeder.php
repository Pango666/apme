<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\{
    Setting,
    Page,
    Community,
    Product,
    Album,
    AlbumPhoto,
    Post,
    Partner
};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /* =========================
         * PÁGINAS INSTITUCIONALES
         * ========================= */
        Page::updateOrCreate(['slug' => 'quienes-somos'], [
            'title'   => 'Quiénes somos',
            'excerpt' => 'Asociación de Productores de Miel Ecológica.',
            'body'    => "Somos una organización de apicultores que impulsa la apicultura sostenible."
        ]);
        Page::updateOrCreate(['slug' => 'mision'], [
            'title'   => 'Misión',
            'excerpt' => 'Fortalecer a las comunidades apícolas.',
            'body'    => "Promovemos prácticas sostenibles, comercio justo y calidad."
        ]);
        Page::updateOrCreate(['slug' => 'vision'], [
            'title'   => 'Visión',
            'excerpt' => 'Ser referentes en apicultura ecológica.',
            'body'    => "Cuidar a las abejas y mejorar la economía de las familias."
        ]);

        /* ==============
         * COMUNIDADES
         * ============== */
        $yungas = Community::updateOrCreate(['slug' => 'yungas'], [
            'name' => 'Yungas',
            'province' => 'La Paz',
            'description' => 'Comunidad apícola en Yungas.'
        ]);
        $valles = Community::updateOrCreate(['slug' => 'valles'], [
            'name' => 'Valles',
            'province' => 'Cochabamba',
            'description' => 'Comunidad de los valles.'
        ]);

        /* =============
         * PRODUCTOS
         * ============= */
        Product::updateOrCreate(['slug' => 'miel-yungas-500g'], [
            'community_id' => $yungas->id,
            'name' => 'Miel Yungas 500g',
            'type' => 'miel',
            'price_bs' => 25,
            'is_active' => true,
        ]);
        Product::updateOrCreate(['slug' => 'polen-valles-200g'], [
            'community_id' => $valles->id,
            'name' => 'Polen Valles 200g',
            'type' => 'polen',
            'price_bs' => 18,
            'is_active' => true,
        ]);

        /* =================
         * AJUSTES (HERO)
         * ================= */
        Setting::updateOrCreate(['key' => 'hero.title'],   ['value' => 'Miel justa de nuestras comunidades']);
        Setting::updateOrCreate(['key' => 'hero.subtitle'], ['value' => 'Asociación de Productores de Miel Ecológica']);
        Setting::updateOrCreate(['key' => 'hero.image'],   ['value' => '/hero-miel.webp']);

        /* =========================
         * FERIAS (ÁLBUMES + FOTOS)
         * ========================= */
        $expo = Album::updateOrCreate(['slug' => 'expoferia-2025'], [
            'title'  => 'APME presente en la Expoferia 2025',
            'type'   => 'feria',
            'date'   => Carbon::parse('2025-09-19'),
            'place'  => 'La Paz',
            'summary' => 'Participación histórica de APME mostrando mieles y derivados.',
        ]);

        // Fotos del álbum (ajusta paths a tus archivos reales)
        AlbumPhoto::updateOrCreate(
            ['album_id' => $expo->id, 'path' => '/feria-thumb.webp'],
            ['caption' => 'Stand APME', 'order' => 1]
        );
        AlbumPhoto::updateOrCreate(
            ['album_id' => $expo->id, 'path' => '/colmena1.webp'],
            ['caption' => 'Mieles de comunidades', 'order' => 2]
        );
        AlbumPhoto::updateOrCreate(
            ['album_id' => $expo->id, 'path' => '/colmena2.webp'],
            ['caption' => 'Productores en feria', 'order' => 3]
        );

        /* ==========
         * NOTICIAS
         * ========== */
        Post::updateOrCreate(['slug' => 'apme-presente-expoferia-2025'], [
            'title'        => 'APME presente en la Expoferia 2025 con productos ecológicos',
            'excerpt'      => 'Llevamos la voz de los apicultores y nuestras mieles certificadas.',
            'body'         => "APME participó en la Expoferia 2025 mostrando mieles, polen y derivados.\nPromovemos el comercio justo y la trazabilidad.",
            'cover_path'   => '/nota1.webp',
            'published_at' => now(),
        ]);

        Post::updateOrCreate(['slug' => 'convocatoria-capacitacion-jovenes-apicultores'], [
            'title'        => 'Convocatoria: capacitación para jóvenes apicultores',
            'excerpt'      => 'Talleres de buenas prácticas y seguridad alimentaria.',
            'body'         => "Abrimos cupos para formación técnica a nuevas generaciones de apicultores.",
            'cover_path'   => '/nota2.webp',
            'published_at' => now()->subDays(5),
        ]);

        /* ==========
         * PARTNERS
         * ========== */
        Partner::updateOrCreate(['name' => 'Peace & Human Rights'], [
            'logo_path' => '/logo1.webp',
            'url' => '#'
        ]);
        Partner::updateOrCreate(['name' => 'LifeCare'], [
            'logo_path' => '/logo2.webp',
            'url' => '#'
        ]);
        Partner::updateOrCreate(['name' => 'Hope Partners'], [
            'logo_path' => '/logo3.webp',
            'url' => '#'
        ]);
    }
}
