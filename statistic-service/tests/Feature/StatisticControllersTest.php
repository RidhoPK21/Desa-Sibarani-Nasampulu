<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatisticControllersTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_crud_idm()
    {
        $payload = [
            'tahun_idm' => 2025,
            'status_idm' => 'Berkembang',
            'score_idm' => 0.75,
            'sosial_idm' => 0.7,
            'ekonomi_idm' => 0.8,
            'lingkungan_idm' => 0.9,
        ];

        $create = $this->postJson('/api/idm', $payload);
        $create->assertStatus(201)->assertJsonPath('data.tahun_idm', 2025);

        $idmId = $create->json('data.id');

        $this->getJson('/api/idm')->assertStatus(200)->assertJsonPath('data.0.tahun_idm', 2025);
        $this->getJson("/api/idm/{$idmId}")->assertStatus(200)->assertJsonPath('data.id', $idmId);

        $update = $this->putJson("/api/idm/{$idmId}", ['status_idm' => 'Maju']);
        $update->assertStatus(200)->assertJsonPath('data.status_idm', 'Maju');

        $delete = $this->deleteJson("/api/idm/{$idmId}");
        $delete->assertStatus(200)->assertJsonPath('status', 'success');
        $this->getJson("/api/idm/{$idmId}")->assertStatus(404);
    }

    public function test_it_can_crud_dusun()
    {
        $payload = [
            'id' => 'D001',
            'nama_dusun' => 'Dusun Test',
            'penduduk_laki' => 12,
            'penduduk_perempuan' => 13,
        ];

        $create = $this->postJson('/api/dusun', $payload);
        $create->assertStatus(201)->assertJsonPath('data.id', 'D001');

        $this->getJson('/api/dusun')->assertStatus(200)->assertJsonPath('data.0.id', 'D001');
        $this->getJson('/api/dusun/D001')->assertStatus(200)->assertJsonPath('data.id', 'D001');

        $update = $this->putJson('/api/dusun/D001', ['nama_dusun' => 'Dusun Updated']);
        $update->assertStatus(200)->assertJsonPath('data.nama_dusun', 'Dusun Updated');

        $delete = $this->deleteJson('/api/dusun/D001');
        $delete->assertStatus(200)->assertJsonPath('status', 'success');
        $this->getJson('/api/dusun/D001')->assertStatus(404);
    }

    public function test_it_can_store_and_update_dusun_with_statistic_arrays()
    {
        $payload = [
            'id' => 'D002',
            'nama_dusun' => 'Dusun Statistik',
            'penduduk_laki' => 10,
            'penduduk_perempuan' => 12,
            'usias' => [
                ['kelompok_usia' => '0-5', 'jumlah_jiwa' => 5],
            ],
            'pendidikans' => [
                ['tingkat_pendidikan' => 'SD', 'jumlah_jiwa' => 7],
            ],
            'pekerjaans' => [
                ['jenis_pekerjaan' => 'Petani', 'jumlah_jiwa' => 8],
            ],
            'agamas' => [
                ['agama' => 'Islam', 'jumlah_jiwa' => 15],
            ],
            'perkawinans' => [
                ['status_perkawinan' => 'Kawin', 'jumlah_jiwa' => 10],
            ],
        ];

        $create = $this->postJson('/api/dusun', $payload);
        $create->assertStatus(201)->assertJsonPath('data.id', 'D002');

        $update = $this->putJson('/api/dusun/D002', [
            'nama_dusun' => 'Dusun Statistik Updated',
            'usias' => [
                ['kelompok_usia' => '6-10', 'jumlah_jiwa' => 6],
            ],
            'pendidikans' => [
                ['tingkat_pendidikan' => 'SMP', 'jumlah_jiwa' => 4],
            ],
            'pekerjaans' => [
                ['jenis_pekerjaan' => 'Nelayan', 'jumlah_jiwa' => 5],
            ],
            'agamas' => [
                ['agama' => 'Kristen', 'jumlah_jiwa' => 8],
            ],
            'perkawinans' => [
                ['status_perkawinan' => 'Belum Kawin', 'jumlah_jiwa' => 3],
            ],
        ]);

        $update->assertStatus(200)->assertJsonPath('data.nama_dusun', 'Dusun Statistik Updated');
        $this->assertCount(1, $update->json('data.usias'));
        $this->assertCount(1, $update->json('data.pendidikans'));
        $this->assertCount(1, $update->json('data.pekerjaans'));
        $this->assertCount(1, $update->json('data.agamas'));
        $this->assertCount(1, $update->json('data.perkawinans'));
    }
}
