<?php

namespace Tests\Feature;

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
}
