<?php

namespace Tests\Feature;

use stdClass;
use Tests\TestCase;

class MailAndErrorThemeViewTest extends TestCase
{
    public function test_mail_views_use_landing_green_theme(): void
    {
        foreach ($this->mailViewData() as $viewName => $data) {
            $view = $this->view($viewName, $data);

            $view->assertSee('background-color: #136c34;', false);
            $view->assertSee('background-color: #f2fbf6;', false);
            $view->assertSee('rgba(19, 108, 52, 0.12)', false);
            $view->assertDontSee('#2f55d4', false);
            $view->assertDontSee('#e43f52', false);
            $view->assertDontSee('color: red', false);
        }
    }

    public function test_error_view_uses_landing_green_theme(): void
    {
        $view = $this->view('errors.404');

        $view->assertSee('icons/navbar.png', false);
        $view->assertSee('class="error-code"', false);
        $view->assertDontSee('text-danger', false);
        $view->assertDontSee('horizontal-E-SATU.png', false);

        $stylesheet = file_get_contents(public_path('admin/assets/css/error-custome.css'));

        $this->assertIsString($stylesheet);
        $this->assertStringContainsString('--error-primary: #136c34;', $stylesheet);
        $this->assertStringContainsString('--error-primary-dark: #0f5528;', $stylesheet);
        $this->assertStringContainsString('linear-gradient(180deg, #f2fbf6 0%, #ffffff 100%)', $stylesheet);
        $this->assertStringNotContainsString('#2F55D4', $stylesheet);
        $this->assertStringNotContainsString('#4169E1', $stylesheet);
        $this->assertStringNotContainsString('rgba(47, 85, 212', $stylesheet);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function mailViewData(): array
    {
        $user = new stdClass;
        $user->name = 'Pengguna Test';
        $user->reactivation = 0;

        $suratKuasa = new stdClass;
        $suratKuasa->id_daftar = 'ESATU-2026-001';
        $suratKuasa->register = new stdClass;
        $suratKuasa->register->nomor_surat_kuasa = 'PNMN/001/SK/2026';

        return [
            'mail.aktivasi-akun' => [
                'title' => 'Aktivasi Akun',
                'user' => $user,
                'activationUrl' => 'https://example.test/aktivasi',
            ],
            'mail.approve-surat-kuasa' => [
                'user' => $user,
                'suratKuasa' => $suratKuasa,
            ],
            'mail.reject-surat-kuasa' => [
                'title' => 'Pendaftaran Surat Kuasa Ditolak',
                'user' => $user,
                'suratKuasa' => $suratKuasa,
                'keterangan' => 'Dokumen belum lengkap.',
            ],
            'mail.profile-warning' => [
                'title' => 'Peringatan Pembaruan Profil',
                'user' => $user,
            ],
            'mail.reset-password-akun' => [
                'title' => 'Reset Password Akun',
                'user' => $user,
                'resetUrl' => 'https://example.test/reset',
            ],
        ];
    }
}
