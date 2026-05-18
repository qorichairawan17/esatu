<?php

namespace Tests\Feature;

use Tests\TestCase;

class PanduanThemeViewTest extends TestCase
{
    public function test_panduan_css_uses_project_green_theme(): void
    {
        $css = file_get_contents(public_path('assets/css/panduan.css'));

        $this->assertStringContainsString('--panduan-primary: #29aa59;', $css);
        $this->assertStringContainsString('--panduan-primary-soft: #ecfbf2;', $css);
        $this->assertStringContainsString('.sidebar-menu>li.sidebar>a,', $css);
        $this->assertStringContainsString('background: #ffffff !important;', $css);
        $this->assertStringContainsString('color: var(--panduan-dark) !important;', $css);
        $this->assertStringContainsString('.sidebar-menu>li.sidebar>a.active,', $css);
        $this->assertStringContainsString('.sidebar-menu .sidebar-submenu ul li>a.active {', $css);
        $this->assertStringContainsString('.page-wrapper .sidebar-wrapper #panduan-menu li.active>a,', $css);
        $this->assertStringContainsString('.page-wrapper .sidebar-wrapper #panduan-menu .sidebar-dropdown.active>a::after,', $css);
        $this->assertStringContainsString('color: var(--panduan-dark);', $css);
        $this->assertStringNotContainsString('background: rgba(41, 170, 89, 0.72) !important;', $css);
        $this->assertStringNotContainsString('background: rgba(41, 170, 89, 0.64) !important;', $css);
        $this->assertStringContainsString('.btn-primary,', $css);
        $this->assertStringContainsString('.btn-warning {', $css);
        $this->assertStringContainsString('rgba(41, 170, 89, 0.14)', $css);
        $this->assertStringNotContainsString('--panduan-primary: #2F55D4;', $css);
    }

    public function test_panduan_layout_loads_theme_assets(): void
    {
        $view = $this->view('panduan.header', [
            'title' => 'Panduan',
        ]);

        $view->assertSee('assets/css/panduan.css', false);
        $view->assertSee('admin/assets/css/bootstrap.min.css', false);
    }

    public function test_panduan_sidebar_uses_landing_logo_assets(): void
    {
        $view = $this->view('panduan.sidebar', [
            'infoApp' => (object) [
                'kontak' => '628123456789',
            ],
        ]);

        $view->assertSee('icons/navbar.png', false);
        $view->assertSee('icons/navbar-white.png', false);
        $view->assertDontSee('horizontal-e-suka', false);
    }
}
