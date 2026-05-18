<?php

namespace Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Tests\TestCase;

class LandingHomeViewTest extends TestCase
{
    public function test_landing_home_view_renders_service_information(): void
    {
        $infoApp = (object) [
            'pengadilan_negeri' => 'Pengadilan Negeri Mandailing Natal',
            'pengadilan_tinggi' => 'Pengadilan Tinggi Medan',
            'website' => 'https://pn-mandailingnatal.go.id',
            'email' => 'layanan@example.test',
            'facebook' => 'https://facebook.example.test',
            'instagram' => 'https://instagram.example.test',
            'alamat' => 'Jalan Merdeka',
            'kabupaten' => 'Mandailing Natal',
            'provinsi' => 'Sumatera Utara',
            'kode_pos' => '22976',
            'kontak' => '061123456',
        ];

        $view = $this->view('landing.home', [
            'title' => 'Beranda',
            'infoApp' => $infoApp,
            'pejabatStruktural' => null,
            'testimoni' => collect(),
            'totalSuratKuasa' => 12,
            'totalUser' => 8,
        ]);

        $view->assertSeeText('Pendaftaran Surat Kuasa Digital');
        $view->assertSeeText('Layanan yang Lebih Ringkas');
        $view->assertSeeText('Mudah, Cepat, Biaya Ringan');
        $view->assertSeeText('Rp10.000');
        $view->assertSeeText('layanan@example.test');
        $view->assertSee('landing-footer', false);
        $view->assertSee('icons/navbar.png', false);
    }

    public function test_landing_navbar_disables_about_menu(): void
    {
        $view = $this->view('landing.navbar');

        $view->assertSeeText('Tentang');
        $view->assertSee('aria-disabled="true"', false);
        $view->assertSee('disabled-menu', false);
        $view->assertDontSee('href="'.route('app.about').'"', false);
    }

    public function test_public_sitemap_does_not_include_about_page(): void
    {
        $sitemap = file_get_contents(public_path('sitemap.xml'));
        $robots = file_get_contents(public_path('robots.txt'));

        $this->assertStringNotContainsString('/index/about', $sitemap);
        $this->assertStringNotContainsString('Allow: /index/about', $robots);
    }

    public function test_about_meta_uses_noindex(): void
    {
        $request = Request::create('/index/about');
        $route = new Route(['GET'], '/index/about', ['as' => 'app.about', 'uses' => fn () => null]);

        $request->setRouteResolver(fn () => $route);
        $this->app->instance('request', $request);

        $view = $this->view('miscellaneous.meta', ['title' => 'Tentang']);

        $view->assertSee('name="robots" content="noindex, nofollow"', false);
    }
}
