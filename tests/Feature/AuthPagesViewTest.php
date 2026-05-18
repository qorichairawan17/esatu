<?php

namespace Tests\Feature;

use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class AuthPagesViewTest extends TestCase
{
    public function test_signin_view_uses_green_auth_theme(): void
    {
        $view = $this->view('auth.signin', [
            'title' => 'Masuk',
            'errors' => new ViewErrorBag,
        ]);

        $view->assertSee('assets/css/style-green.min.css', false);
        $view->assertSee('class="auth-body"', false);
        $view->assertSee('--esatu-primary: #136c34;', false);
        $view->assertSee('id="login-form"', false);
        $view->assertSee('id="captcha-img"', false);
        $view->assertSeeText('Selamat Datang');
        $view->assertSeeText('Masuk untuk melanjutkan pendaftaran surat kuasa digital.');
        $view->assertSee('icons/navbar.png', false);
    }

    public function test_signup_view_uses_green_auth_theme(): void
    {
        $view = $this->view('auth.signup', [
            'title' => 'Daftar',
            'errors' => new ViewErrorBag,
        ]);

        $view->assertSee('assets/css/style-green.min.css', false);
        $view->assertSee('class="auth-body"', false);
        $view->assertSee('--esatu-primary: #136c34;', false);
        $view->assertSee('id="register-form"', false);
        $view->assertSee('id="privacy_policy"', false);
        $view->assertSeeText('Buat Akun Baru');
        $view->assertSeeText('Gunakan email aktif untuk menerima aktivasi akun.');
        $view->assertSee('icons/navbar.png', false);
    }

    public function test_forgot_password_view_uses_green_auth_theme(): void
    {
        $view = $this->view('auth.forgot-password', [
            'title' => 'Lupa Password',
            'errors' => new ViewErrorBag,
        ]);

        $view->assertSee('assets/css/style-green.min.css', false);
        $view->assertSee('class="auth-body"', false);
        $view->assertSee('--esatu-primary: #136c34;', false);
        $view->assertSee('id="send-link-form"', false);
        $view->assertSeeText('Reset Akses Akun');
        $view->assertSeeText('Tautan reset akan dikirim ke email yang terdaftar pada akun Kamu.');
        $view->assertSee('icons/navbar.png', false);
    }

    public function test_reset_password_view_uses_green_auth_theme(): void
    {
        $view = $this->view('auth.forgot-password', [
            'title' => 'Reset Password',
            'token' => 'reset-token',
            'email' => 'pengguna@example.test',
            'errors' => new ViewErrorBag,
        ]);

        $view->assertSee('assets/css/style-green.min.css', false);
        $view->assertSee('class="auth-body"', false);
        $view->assertSee('id="reset-password-form"', false);
        $view->assertSee('name="token" value="reset-token"', false);
        $view->assertSeeText('Password Baru');
        $view->assertSeeText('Gunakan minimal 8 karakter untuk mengamankan akun Kamu.');
    }

    public function test_captcha_image_endpoint_renders(): void
    {
        $response = $this->get('/captcha/flat');

        $response->assertOk();
        $this->assertStringStartsWith('image/jpeg', (string) $response->headers->get('content-type'));
        $this->assertNotEmpty($response->getContent());
    }
}
