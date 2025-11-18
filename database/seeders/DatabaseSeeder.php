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
            'body'    => <<<HTML
<p>Somos una organización de apicultores que impulsa la apicultura sostenible,
la trazabilidad y el comercio justo. Trabajamos con comunidades de distintas
regiones para garantizar productos de calidad y oportunidades dignas.</p>
HTML
        ]);

        Page::updateOrCreate(['slug' => 'mision'], [
            'title'   => 'Misión',
            'excerpt' => 'Fortalecer a las comunidades apícolas.',
            'body'    => '<p>Promover prácticas sostenibles, fortalecer capacidades y abrir mercados con precios justos.</p>',
        ]);

        Page::updateOrCreate(['slug' => 'vision'], [
            'title'   => 'Visión',
            'excerpt' => 'Ser referentes en apicultura ecológica.',
            'body'    => '<p>Proteger a las abejas, conservar el entorno y mejorar la economía de las familias productoras.</p>',
        ]);

        /* =================
         * AJUSTES (HOME)
         * ================= */
        Setting::updateOrCreate(['key' => 'hero.title'],     ['value' => 'Miel justa de nuestras comunidades']);
        Setting::updateOrCreate(['key' => 'hero.subtitle'],  ['value' => 'Asociación de Productores de Miel Ecológica']);
        // Ruta relativa (coloca un archivo real en public/storage o storage/app/public y usa storage:link)
        Setting::updateOrCreate(['key' => 'hero.image'],     ['value' => 'heroes/apme.webp']);

        Setting::updateOrCreate(['key' => 'contact.whatsapp'], ['value' => '+591 681 86701']);
        Setting::updateOrCreate(['key' => 'contact.email'],    ['value' => 'info@apme.bo']);
        Setting::updateOrCreate(['key' => 'contact.address'],  ['value' => 'La Paz – Bolivia']);

        /* ==============
         * COMUNIDADES
         * ============== */
        $yungas = Community::updateOrCreate(['slug' => 'yungas'], [
            'name'        => 'Yungas',
            'province'    => 'La Paz',
            'description' => 'Comunidad apícola en Yungas.',
            // Landing
            'hero_title'    => 'Productores de Yungas',
            'hero_subtitle' => 'Miel multifloral de altura',
            'hero_image'    => 'heroes/yungas.webp',
            'about_html'    => '<p>Somos familias apicultoras de los Yungas, enfocadas en buenas prácticas e inocuidad.</p>',
            'blocks'        => [
                ['type' => 'stats', 'data' => ['items' => [
                    ['label' => 'Familias', 'value' => '120'],
                    ['label' => 'Colmenas', 'value' => '850'],
                    ['label' => 'Años',     'value' => '15'],
                ]]],
                ['type' => 'image', 'data' => ['src' => 'gallery/yungas-1.webp', 'caption' => 'Cosecha en Yungas']],
                // community_id __SELF__ hace que el renderer tome la comunidad actual
                ['type' => 'products', 'data' => ['community_id' => '__SELF__', 'limit' => 8]],
            ],
            'latitude' => -16.35,
            'longitude' => -67.73,
        ]);

        $valles = Community::updateOrCreate(['slug' => 'valles'], [
            'name'        => 'Valles',
            'province'    => 'Cochabamba',
            'description' => 'Comunidad de los valles.',
            // Landing
            'hero_title'    => 'Productores de Valles',
            'hero_subtitle' => 'Polen e innovaciones apícolas',
            'hero_image'    => 'heroes/valles.webp',
            'about_html'    => '<p>En los valles producimos polen y miel monofloral con enfoque ecológico.</p>',
            'blocks'        => [
                ['type' => 'text', 'data' => ['html' => '<h2>Sobre nuestra comunidad</h2><p>Organización, formación y calidad.</p>']],
                ['type' => 'gallery', 'data' => ['items' => [
                    ['src' => 'gallery/valles-1.webp'],
                    ['src' => 'gallery/valles-2.webp'],
                    ['src' => 'gallery/valles-3.webp'],
                ]]],
            ],
            'latitude' => -17.40,
            'longitude' => -66.16,
        ]);

        /* =============
         * PRODUCTOS
         * ============= */
        Product::updateOrCreate(['slug' => 'miel-yungas-500g'], [
            'community_id' => $yungas->id,
            'name'         => 'Miel Yungas 500g',
            'type'         => 'miel',
            'price_bs'     => 25,
            'is_active'    => true,
            // Landing corta
            'hero_title'   => 'Miel Yungas 500g',
            'hero_subtitle' => 'Cosecha 2025 · Multifloral',
            'hero_image'   => 'products/miel-yungas-500.webp',
            'about_html'   => '<p>Textura cremosa, aroma floral y origen controlado. Ideal para consumo diario.</p>',
            'blocks'       => [
                ['type' => 'text', 'data' => ['html' => '<h3>Ficha técnica</h3><ul><li>Humedad &lt; 18%</li><li>Origen: Yungas</li></ul>']],
            ],
        ]);

        Product::updateOrCreate(['slug' => 'polen-valles-200g'], [
            'community_id' => $valles->id,
            'name'         => 'Polen Valles 200g',
            'type'         => 'polen',
            'price_bs'     => 18,
            'is_active'    => true,
            'hero_title'   => 'Polen Valles 200g',
            'hero_subtitle' => 'Secado controlado · Alto valor nutricional',
            'hero_image'   => 'products/polen-valles-200.webp',
            'about_html'   => '<p>Polen seleccionado con procesos de secado que preservan nutrientes.</p>',
            'blocks'       => [
                ['type' => 'gallery', 'data' => ['items' => [
                    ['src' => 'products/polen-1.webp'],
                    ['src' => 'products/polen-2.webp'],
                ]]],
            ],
        ]);

        /* =========================
         * FERIAS (ÁLBUMES + FOTOS)
         * ========================= */
        $expo = Album::updateOrCreate(['slug' => 'expoferia-2025'], [
            'title'        => 'APME presente en la Expoferia 2025',
            'type'         => 'feria',
            'date'         => Carbon::parse('2025-09-19'),
            'place'        => 'La Paz',
            'summary'      => 'Participación histórica de APME mostrando mieles y derivados.',
            // Landing feria
            'hero_title'   => 'Expoferia 2025',
            'hero_subtitle' => 'Muestras, degustaciones y venta directa',
            'hero_image'   => 'albums/expoferia-2025/hero.webp',
            'about_html'   => '<p>Gracias a todos los que visitaron nuestro stand. Seguimos promoviendo la apicultura ecológica.</p>',
            'blocks'       => [
                ['type' => 'text', 'data' => ['html' => '<h3>Programa</h3><p>Charlas técnicas, degustaciones y venta directa de productores.</p>']],
            ],
        ]);

        // Fotos del álbum (usa rutas relativas; crea los archivos si quieres verlas)
        AlbumPhoto::updateOrCreate(
            ['album_id' => $expo->id, 'path' => 'albums/expoferia-2025/thumb.webp'],
            ['caption' => 'Stand APME', 'order' => 1]
        );
        AlbumPhoto::updateOrCreate(
            ['album_id' => $expo->id, 'path' => 'albums/expoferia-2025/colmena1.webp'],
            ['caption' => 'Mieles de comunidades', 'order' => 2]
        );
        AlbumPhoto::updateOrCreate(
            ['album_id' => $expo->id, 'path' => 'albums/expoferia-2025/colmena2.webp'],
            ['caption' => 'Productores en feria', 'order' => 3]
        );

        /* ========== NOTICIAS ========== */
        Post::updateOrCreate(['slug' => 'apme-presente-expoferia-2025'], [
            'title'        => 'APME presente en la Expoferia 2025 con productos ecológicos',
            'excerpt'      => 'Llevamos la voz de los apicultores y nuestras mieles certificadas.',
            'body'         => "APME participó en la Expoferia 2025 mostrando mieles, polen y derivados.\nPromovemos el comercio justo y la trazabilidad.",
            'cover_path'   => 'posts/nota1.webp',
            'published_at' => now(),
            'is_published' => true,
        ]);

        Post::updateOrCreate(['slug' => 'convocatoria-capacitacion-jovenes-apicultores'], [
            'title'        => 'Convocatoria: capacitación para jóvenes apicultores',
            'excerpt'      => 'Talleres de buenas prácticas y seguridad alimentaria.',
            'body'         => "Abrimos cupos para formación técnica a nuevas generaciones de apicultores.",
            'cover_path'   => 'posts/nota2.webp',
            'published_at' => now()->subDays(5),
            'is_published' => true,
        ]);

        /* ========== PARTNERS ========== */
        Partner::updateOrCreate(['name' => 'Peace & Human Rights'], [
            'logo_path' => 'partners/logo1.webp',
            'url'       => '#',
        ]);
        Partner::updateOrCreate(['name' => 'LifeCare'], [
            'logo_path' => 'partners/logo2.webp',
            'url'       => '#',
        ]);
        Partner::updateOrCreate(['name' => 'Hope Partners'], [
            'logo_path' => 'partners/logo3.webp',
            'url'       => '#',
        ]);

        /* ========== PLANTILLAS (opcional) ========== */
        if (class_exists(\App\Models\ContentTemplate::class)) {
            \App\Models\ContentTemplate::updateOrCreate(
                ['entity' => 'community', 'name' => 'Comunidad · Básico'],
                [
                    'hero' => ['title' => 'Nuestra comunidad', 'subtitle' => 'Productores locales', 'image' => null],
                    'about_html' => '<p>Somos una comunidad de apicultores…</p>',
                    'blocks' => [
                        ['type' => 'stats', 'data' => ['items' => [
                            ['label' => 'Familias', 'value' => '100+'],
                            ['label' => 'Colmenas', 'value' => '1.2k'],
                        ]]],
                        ['type' => 'products', 'data' => ['community_id' => '__SELF__', 'limit' => 8]],
                        ['type' => 'map', 'data' => ['latitude' => -16.54, 'longitude' => -68.11, 'zoom' => 11]],
                    ],
                ]
            );
        }
    }
}
