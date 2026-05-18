<?php

namespace Database\Seeders;

use App\Enum\RoleEnum;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enum\StatusPaniteraEnum;
use App\Models\Pengaturan\AplikasiModel;
use App\Models\Pengguna\PaniteraModel;
use App\Models\Profile\ProfileModel;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $profile1 = ProfileModel::firstOrCreate([
            'kontak' => '080000000',
            'nama_depan' => 'Qori',
            'nama_belakang' => 'Chairawan',
        ], [
            'tanggal_lahir' => '2000-07-17',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jalan Jenderal Sudirman No. 58 Lubuk Pakam',
        ]);

        $profile2 = ProfileModel::firstOrCreate([
            'kontak' => '080000000000', // Menggunakan kontak berbeda untuk membedakan profil jika diperlukan
            'nama_depan' => 'Qori',
            'nama_belakang' => 'Chairawan',
        ], [
            'tanggal_lahir' => '2000-07-17',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jalan Jenderal Sudirman No. 58 Lubuk Pakam',
        ]);

        User::updateOrCreate(
            ['email' => 'qorichairawan17@gmail.com'],
            [
                'name' => 'Qori Chairawan',
                'password' => 'qori',
                'role' => RoleEnum::Superadmin->value,
                'block' => '0',
                'profile_id' => $profile1->id,
                'profile_status' => '1',
            ]
        );

        User::updateOrCreate(
            ['email' => 'qorichairawan@gmail.com'],
            [
                'name' => 'Pengguna',
                'password' => 'qori',
                'role' => RoleEnum::User->value,
                'block' => '0',
                'profile_id' => $profile2->id,
                'profile_status' => '1',
            ]
        );

        AplikasiModel::create([
            'pengadilan_tinggi' => 'Pengadilan Tinggi Medan',
            'pengadilan_negeri' => 'Pengadilan Negeri Mandailing Natal',
            'kode_dipa' => '400395',
            'kode_surat_kuasa' => '#NOMOR/W2-U4/SK/#BULAN/#TAHUN/PN Lbp',
            'provinsi' => 'Sumatera Utara',
            'kabupaten' => 'Mandailing Natal',
            'kode_pos' => '22976',
            'alamat' => ' Jalan Lintas Sumatera KM. 7, Panyabungan, Mompang Jae, Kec. Panyabungan Utara, Kabupaten Mandailing Natal, Sumatera Utara',
            'website' => 'https://pn-mandailingnatal.go.id/',
            'facebook' => 'https://pn-mandailingnatal.go.id/',
            'instagram' => 'https://pn-mandailingnatal.go.id/',
            'youtube' => 'https://pn-mandailingnatal.go.id/',
            'kontak' => '08238827272',
            'email' => 'pnmandailingnatal@yahoo.co.id',
            'maintance' => '0',
        ]);

        PaniteraModel::insert([
            [
                'nip' => '121441',
                'nama' => 'Syawal Aswad Siregar, S.H.,M.Hum',
                'jabatan' => 'Panitera',
                'status' => StatusPaniteraEnum::NonPlh->value,
                'aktif' => '1',
                'created_by' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '121442',
                'nama' => 'Dedy Anthony, SH,MH',
                'jabatan' => 'Panitera Muda Pidana',
                'status' => StatusPaniteraEnum::Plh->value,
                'aktif' => '1',
                'created_by' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
