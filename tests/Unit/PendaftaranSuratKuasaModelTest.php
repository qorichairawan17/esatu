<?php

namespace Tests\Unit;

use App\Enum\RoleEnum;
use App\Enum\TahapanSuratKuasaEnum;
use App\Models\Suratkuasa\PendaftaranSuratKuasaModel;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class PendaftaranSuratKuasaModelTest extends TestCase
{
    public function test_user_can_delete_own_registration_in_pendaftaran_stage(): void
    {
        $user = $this->user(RoleEnum::User, 10);
        $pendaftaran = $this->pendaftaran(userId: 10, tahapan: TahapanSuratKuasaEnum::Pendaftaran);

        $this->assertTrue($pendaftaran->canBeDeletedBy($user));
    }

    public function test_user_cannot_delete_own_registration_after_pendaftaran_stage(): void
    {
        $user = $this->user(RoleEnum::User, 10);
        $pendaftaran = $this->pendaftaran(userId: 10, tahapan: TahapanSuratKuasaEnum::Pembayaran);

        $this->assertFalse($pendaftaran->canBeDeletedBy($user));
    }

    public function test_user_cannot_delete_another_users_registration(): void
    {
        $user = $this->user(RoleEnum::User, 10);
        $pendaftaran = $this->pendaftaran(userId: 99, tahapan: TahapanSuratKuasaEnum::Pendaftaran);

        $this->assertFalse($pendaftaran->canBeDeletedBy($user));
    }

    public function test_non_user_can_delete_registration(): void
    {
        $admin = $this->user(RoleEnum::Administrator, 1);
        $pendaftaran = $this->pendaftaran(userId: 10, tahapan: TahapanSuratKuasaEnum::Pembayaran);

        $this->assertTrue($pendaftaran->canBeDeletedBy($admin));
    }

    private function user(RoleEnum $role, int $id): User
    {
        $user = new User;
        $user->id = $id;
        $user->role = $role->value;

        return $user;
    }

    private function pendaftaran(int $userId, TahapanSuratKuasaEnum $tahapan): PendaftaranSuratKuasaModel
    {
        return new PendaftaranSuratKuasaModel([
            'user_id' => $userId,
            'tahapan' => $tahapan->value,
        ]);
    }
}
