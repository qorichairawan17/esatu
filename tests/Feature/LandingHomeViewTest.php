<?php

namespace Tests\Feature;

use App\Models\Pengguna\PaniteraModel;
use App\Models\Suratkuasa\PendaftaranSuratKuasaModel;
use App\Models\Suratkuasa\PihakSuratKuasaModel;
use App\Models\Suratkuasa\RegisterSuratKuasaModel;
use App\Models\User;
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
        $view->assertSeeText('Satu Pintu, Satu Klik, Urusan Kuasa Jadi Praktis.');
        $view->assertSeeText('Rp10.000');
        $view->assertSeeText('layanan@example.test');
        $view->assertSee('landing-footer', false);
        $view->assertSee('--esatu-primary: #136c34;', false);
        $view->assertSee('icons/navbar.png', false);
    }

    public function test_verify_surat_kuasa_view_renders_clean_theme_layout(): void
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

        $pendaftaran = new PendaftaranSuratKuasaModel([
            'id_daftar' => 'ESATU-2026-001',
            'tanggal_daftar' => '2026-05-17',
            'perihal' => 'Perkara Perdata Gugatan',
            'jenis_surat' => 'Surat Kuasa Khusus',
            'klasifikasi' => 'Advokat',
        ]);

        $pendaftaran->setRelation('user', new User(['name' => 'Pemohon Utama']));
        $pendaftaran->setRelation('pihak', collect([
            new PihakSuratKuasaModel(['jenis' => 'Pemberi', 'nama' => 'Andi Pemberi']),
            new PihakSuratKuasaModel(['jenis' => 'Penerima', 'nama' => 'Sari Penerima']),
        ]));

        $suratKuasa = new RegisterSuratKuasaModel([
            'tanggal_register' => '2026-05-18',
            'nomor_surat_kuasa' => 'PNMN/001/SK/2026',
        ]);

        $suratKuasa->setRelation('pendaftaran', $pendaftaran);
        $suratKuasa->setRelation('panitera', new PaniteraModel(['nama' => 'Panitera Penguji']));
        $suratKuasa->setRelation('approval', new User(['name' => 'Petugas Verifikasi']));

        $view = $this->view('landing.verify-surat-kuasa', [
            'title' => 'Verifikasi Surat Kuasa',
            'infoApp' => $infoApp,
            'suratKuasa' => $suratKuasa,
        ]);

        $view->assertSeeText('Surat Kuasa Terverifikasi');
        $view->assertSeeText('Laman Verifikasi Resmi');
        $view->assertSeeText('Sah dan terdaftar');
        $view->assertSeeText('PNMN/001/SK/2026');
        $view->assertSeeText('ESATU-2026-001');
        $view->assertSeeText('Andi Pemberi');
        $view->assertSeeText('Sari Penerima');
        $view->assertSee('verify-hero', false);
        $view->assertSee('assets/css/verify-surat-kuasa.css', false);

        $stylesheet = file_get_contents(public_path('assets/css/verify-surat-kuasa.css'));

        $this->assertStringContainsString('rgba(var(--esatu-primary-rgb)', $stylesheet);
    }

    public function test_landing_navbar_disables_about_menu(): void
    {
        $view = $this->view('landing.navbar');

        $view->assertDontSeeText('Tentang');
        $view->assertDontSee('aria-disabled="true"', false);
        $view->assertDontSee('disabled-menu', false);
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
