<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminThemeViewTest extends TestCase
{
    public function test_admin_layout_loads_green_theme_assets(): void
    {
        $view = $this->view('admin.layout.header', [
            'title' => 'Dashboard',
        ]);

        $view->assertSee('admin/assets/css/admin-theme.css', false);
        $view->assertSee('<body class="admin-theme">', false);
    }

    public function test_admin_theme_css_contains_green_palette_overrides(): void
    {
        $css = file_get_contents(public_path('admin/assets/css/admin-theme.css'));

        $this->assertStringContainsString('--admin-primary: #136c34;', $css);
        $this->assertStringContainsString('.admin-theme #preloader', $css);
        $this->assertStringContainsString('.admin-theme #status .spinner .double-bounce1', $css);
        $this->assertStringContainsString('.admin-theme .btn-primary', $css);
        $this->assertStringContainsString('.admin-theme .dataTables_wrapper', $css);
        $this->assertStringContainsString('.admin-theme .dataTables_wrapper .dataTables_paginate .page-item.active .page-link', $css);
        $this->assertStringContainsString('.admin-theme .dt-container .dt-paging .page-item.active .page-link', $css);
        $this->assertStringContainsString('.admin-theme .premium-dashboard .welcome-banner::after', $css);
        $this->assertStringContainsString('.admin-theme .monev-dashboard', $css);
    }

    public function test_admin_navigation_uses_landing_logo_assets(): void
    {
        $sidebar = file_get_contents(resource_path('views/admin/layout/sidebar.blade.php'));
        $topHeader = file_get_contents(resource_path('views/admin/component/top-header.blade.php'));

        $this->assertStringContainsString('icons/navbar.png', $sidebar);
        $this->assertStringContainsString('icons/navbar-white.png', $sidebar);
        $this->assertStringContainsString('icons/navbar.png', $topHeader);
        $this->assertStringContainsString('icons/navbar-white.png', $topHeader);
        $this->assertStringNotContainsString('horizontal-E-SATU', $topHeader);
    }

    public function test_admin_dashboard_charts_use_green_theme_colors(): void
    {
        $adminDashboard = file_get_contents(resource_path('views/admin/home/home-admin.blade.php'));
        $userDashboard = file_get_contents(resource_path('views/admin/home/home-pengguna.blade.php'));
        $monitoring = file_get_contents(resource_path('views/admin/monitoring/index.blade.php'));

        $this->assertStringContainsString('rgba(19, 108, 52, 0.82)', $adminDashboard);
        $this->assertStringContainsString("'#136C34'", $adminDashboard);
        $this->assertStringContainsString('rgba(19, 108, 52, 0.85)', $userDashboard);
        $this->assertStringContainsString("const statusColors = ['#136C34'", $monitoring);
        $this->assertStringContainsString('rgba(19, 108, 52, 0.82)', $monitoring);
    }

    public function test_surat_kuasa_detail_barcode_widget_uses_dedicated_styles(): void
    {
        $view = file_get_contents(resource_path('views/admin/surat-kuasa/detail-surat-kuasa.blade.php'));
        $css = file_get_contents(public_path('admin/assets/css/detail-surat-kuasa.css'));

        $this->assertStringContainsString('class="barcode-widget mb-4"', $view);
        $this->assertStringContainsString('class="barcode-widget__button"', $view);
        $this->assertStringNotContainsString('linear-gradient(135deg, #198754 0%, #20c997 100%)', $view);

        $this->assertStringContainsString('.barcode-widget', $css);
        $this->assertStringContainsString('var(--admin-primary, #136c34)', $css);
        $this->assertStringContainsString('var(--admin-primary-dark, #0f5528)', $css);
        $this->assertStringContainsString('.barcode-widget__pattern', $css);
        $this->assertStringContainsString('.barcode-widget__button:hover', $css);
    }
}
