<?php

namespace Tests\Feature;

use Tests\TestCase;

class PwaAssetsTest extends TestCase
{
    public function test_manifest_matches_esatu_pwa_configuration(): void
    {
        $manifest = json_decode(file_get_contents(public_path('manifest.json')), true, 512, JSON_THROW_ON_ERROR);

        $this->assertSame('E-SATU', $manifest['name']);
        $this->assertSame('E-SATU', $manifest['short_name']);
        $this->assertSame(
            'Layanan Pendaftaran Surat Kuasa Digital. Satu Pintu, Satu Klik, Urusan Kuasa Jadi Praktis.',
            $manifest['description'],
        );
        $this->assertSame('1.0.0', $manifest['version']);
        $this->assertSame('Pengadilan Negeri Mandailing Natal', $manifest['author']);
        $this->assertSame(
            'E-SATU, Digital Surat Kuasa, Surat Kuasa, Pengadilan Negeri Mandailing Natal. e-SATU',
            $manifest['keywords'],
        );
        $this->assertSame('Pengadilan Negeri Mandailing Natal', $manifest['developer']);
        $this->assertSame('/', $manifest['start_url']);
        $this->assertSame('/', $manifest['scope']);
        $this->assertSame('standalone', $manifest['display']);
        $this->assertSame('#136C34', $manifest['theme_color']);
        $this->assertSame('id-ID', $manifest['lang']);
        $this->assertContains('/icons/android-icon-512x512.png', array_column($manifest['icons'], 'src'));
        $this->assertContains('/icons/maskable-icon-512x512.png', array_column($manifest['icons'], 'src'));
        $this->assertContains('Masuk', array_column($manifest['shortcuts'], 'short_name'));
    }

    public function test_meta_partial_exposes_installable_pwa_tags(): void
    {
        $view = $this->view('miscellaneous.meta', ['title' => 'Beranda']);

        $view->assertSee('href="'.asset('manifest.json').'"', false);
        $view->assertSee('name="theme-color" content="#136C34"', false);
        $view->assertSee('name="application-name" content="E-SATU"', false);
        $view->assertSee('icons/android-icon-512x512.png', false);
    }

    public function test_service_worker_avoids_caching_sensitive_navigation(): void
    {
        $serviceWorker = file_get_contents(public_path('sw.js'));

        $this->assertStringContainsString('esatu-pwa-v3.0.2', $serviceWorker);
        $this->assertStringContainsString('"/signin"', $serviceWorker);
        $this->assertStringContainsString('"/signup"', $serviceWorker);
        $this->assertStringContainsString('"/surat-kuasa"', $serviceWorker);
        $this->assertStringContainsString('cacheFirstWithRefresh', $serviceWorker);
        $this->assertStringContainsString('networkFirst', $serviceWorker);
    }

    public function test_offline_page_uses_project_identity(): void
    {
        $offlinePage = file_get_contents(public_path('offline.html'));

        $this->assertStringContainsString('Mode Offline - E-SATU', $offlinePage);
        $this->assertStringContainsString('Pengadilan Negeri Mandailing Natal', $offlinePage);
        $this->assertStringContainsString('Satu Pintu, Satu Klik', $offlinePage);
        $this->assertStringContainsString('#136c34', $offlinePage);
    }
}
