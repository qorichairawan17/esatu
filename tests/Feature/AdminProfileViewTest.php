<?php

namespace Tests\Feature;

use App\Enum\RoleEnum;
use App\Models\Profile\ProfileModel;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class AdminProfileViewTest extends TestCase
{
    public function test_profile_view_renders_clean_bootstrap_layout(): void
    {
        Schema::create('notifications', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        $profile = new ProfileModel;
        $profile->forceFill([
            'nama_depan' => 'Qori',
            'nama_belakang' => 'Chairawan',
            'tanggal_lahir' => '1990-02-03',
            'jenis_kelamin' => 'Laki-Laki',
            'kontak' => '081234567890',
            'alamat' => 'Jalan Merdeka Nomor 1',
            'foto' => null,
        ]);

        $user = User::factory()->make([
            'name' => 'Qori Chairawan',
            'email' => 'qori@example.test',
            'role' => RoleEnum::User->value,
            'profile_status' => 1,
            'google_id' => null,
            'created_at' => Carbon::parse('2026-05-18 08:00:00'),
        ]);
        $user->id = 1;
        $user->setRelation('profile', $profile);

        $this->actingAs($user);

        $view = $this->view('admin.pengguna.profil', [
            'title' => 'Profil - E-SATU',
            'pageTitle' => 'Profil Saya',
            'breadCumb' => [
                ['title' => 'Dashboard', 'url' => '#', 'active' => '', 'aria' => ''],
                ['title' => 'Profil', 'url' => '#', 'active' => 'active', 'aria' => 'aria-current="page"'],
            ],
            'infoApp' => (object) [
                'kontak' => '08238827272',
                'email' => 'layanan@example.test',
                'website' => 'https://example.test',
                'pengadilan_negeri' => 'Pengadilan Negeri Mandailing Natal',
            ],
            'user' => $user,
            'errors' => new ViewErrorBag,
        ]);

        $view->assertSee('profile-page', false);
        $view->assertSee('profile-card', false);
        $view->assertSee('profile-panel', false);
        $view->assertSee('id="updateProfileForm"', false);
        $view->assertSee('id="updatePasswordForm"', false);
        $view->assertSee('id="uploadFoto"', false);
        $view->assertSee('id="btn-delete-account"', false);
        $view->assertSeeText('Profil siap digunakan');
        $view->assertSeeText('Tautkan Google');
    }

    public function test_admin_theme_contains_profile_page_styles(): void
    {
        $css = file_get_contents(public_path('admin/assets/css/admin-theme.css'));

        $this->assertStringContainsString('.admin-theme .profile-page', $css);
        $this->assertStringContainsString('.admin-theme .profile-card', $css);
        $this->assertStringContainsString('.admin-theme .profile-tabs .nav-link.active', $css);
    }
}
