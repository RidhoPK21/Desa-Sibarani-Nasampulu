<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatisticControllersTest extends TestCase
{
    use RefreshDatabase;

    // ==========================================
    // --- IDM TESTS ---
    // ==========================================

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

    public function test_it_can_store_idm_with_all_fields()
    {
        $payload = [
            'tahun_idm' => 2024,
            'status_idm' => 'Maju',
            'score_idm' => 0.85,
            'sosial_idm' => 0.80,
            'ekonomi_idm' => 0.90,
            'lingkungan_idm' => 0.85,
        ];

        $create = $this->postJson('/api/idm', $payload);
        $create->assertStatus(201)->assertJsonPath('data.tahun_idm', 2024);
        
        $id = $create->json('data.id');
        $this->getJson("/api/idm/{$id}")->assertStatus(200)->assertJsonPath('data.status_idm', 'Maju');
    }

    public function test_it_validates_idm_show_not_found()
    {
        $response = $this->getJson('/api/idm/9999');
        $response->assertStatus(404)->assertJsonPath('status', 'error');
    }

    public function test_it_validates_idm_update_not_found()
    {
        $response = $this->putJson('/api/idm/9999', [
            'status_idm' => 'Berkembang',
        ]);

        $response->assertStatus(404)->assertJsonPath('status', 'error');
    }

    public function test_it_validates_idm_delete_not_found()
    {
        $response = $this->deleteJson('/api/idm/9999');
        $response->assertStatus(404)->assertJsonPath('status', 'error');
    }


    // ==========================================
    // --- DUSUN TESTS ---
    // ==========================================

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

    public function test_it_can_crud_dusun_and_view_with_relations()
    {
        $create = $this->postJson('/api/dusun', [
            'id' => 'D003',
            'nama_dusun' => 'Dusun View Test',
            'penduduk_laki' => 50,
            'penduduk_perempuan' => 60,
        ]);

        $create->assertStatus(201)->assertJsonPath('data.id', 'D003');

        $show = $this->getJson('/api/dusun/D003');
        $show->assertStatus(200)
            ->assertJsonPath('data.id', 'D003')
            ->assertJsonStructure(['data' => ['usias', 'pendidikans', 'pekerjaans', 'agamas', 'perkawinans']]);

        $delete = $this->deleteJson('/api/dusun/D003');
        $delete->assertStatus(200)->assertJsonPath('status', 'success');
    }

    public function test_it_validates_dusun_id_unique()
    {
        $this->postJson('/api/dusun', [
            'id' => 'D004',
            'nama_dusun' => 'Dusun Pertama',
            'penduduk_laki' => 100,
        ])->assertStatus(201);

        $duplicate = $this->postJson('/api/dusun', [
            'id' => 'D004',
            'nama_dusun' => 'Dusun Duplikat',
            'penduduk_laki' => 100,
        ]);

        $duplicate->assertStatus(422);
    }

    public function test_it_can_update_dusun_partial()
    {
        $create = $this->postJson('/api/dusun', [
            'id' => 'D005',
            'nama_dusun' => 'Dusun Update Partial',
            'penduduk_laki' => 40,
            'penduduk_perempuan' => 45,
        ]);

        $id = $create->json('data.id');

        $update = $this->putJson("/api/dusun/{$id}", [
            'nama_dusun' => 'Dusun Partial Updated',
        ]);

        $update->assertStatus(200)
            ->assertJsonPath('data.nama_dusun', 'Dusun Partial Updated')
            ->assertJsonPath('data.penduduk_laki', 40);
    }

    public function test_it_can_update_dusun_with_only_statistics()
    {
        $create = $this->postJson('/api/dusun', [
            'id' => 'D006',
            'nama_dusun' => 'Dusun Statistik Only',
            'penduduk_laki' => 30,
        ]);

        $id = $create->json('data.id');

        $update = $this->putJson("/api/dusun/{$id}", [
            'usias' => [
                ['kelompok_usia' => '0-5', 'jumlah_jiwa' => 2],
                ['kelompok_usia' => '5-10', 'jumlah_jiwa' => 3],
            ],
            'pendidikans' => [
                ['tingkat_pendidikan' => 'SD', 'jumlah_jiwa' => 10],
            ],
        ]);

        $update->assertStatus(200)
            ->assertJsonCount(2, 'data.usias')
            ->assertJsonCount(1, 'data.pendidikans');
    }

    public function test_it_validates_dusun_show_not_found()
    {
        $response = $this->getJson('/api/dusun/NOTFOUND');
        $response->assertStatus(404)->assertJsonPath('status', 'error');
    }

    public function test_it_validates_dusun_update_not_found()
    {
        $response = $this->putJson('/api/dusun/NOTFOUND', [
            'nama_dusun' => 'Test',
        ]);

        $response->assertStatus(404)->assertJsonPath('status', 'error');
    }

    public function test_it_validates_dusun_delete_not_found()
    {
        $response = $this->deleteJson('/api/dusun/NOTFOUND');
        $response->assertStatus(404)->assertJsonPath('status', 'error');
    }

    // =========================================================
    // --- EXCEPTION COVERAGE TESTS (Untuk 100% DusunController) ---
    // =========================================================

    public function test_it_returns_500_on_dusun_store_exception()
    {
        // Payload valid untuk Dusun, tapi data relasi sengaja disalahkan
        // agar memicu QueryException di database, sehingga masuk ke catch (\Exception $e)
        $payload = [
            'id' => 'E001',
            'nama_dusun' => 'Dusun Error Store',
            'usias' => [
                ['kolom_ngasal' => 'pasti gagal di database']
            ]
        ];

        $response = $this->postJson('/api/dusun', $payload);
        
        $response->assertStatus(500)
                 ->assertJsonPath('status', 'error');
    }

    public function test_it_returns_500_on_dusun_update_exception()
    {
        // 1. Buat data dusun yang valid
        $this->postJson('/api/dusun', [
            'id' => 'E002',
            'nama_dusun' => 'Dusun Normal'
        ]);

        // 2. Lakukan update dengan relasi yang memicu QueryException
        $response = $this->putJson('/api/dusun/E002', [
            'pendidikans' => [
                ['kolom_salah' => 'pasti gagal di database']
            ]
        ]);

        $response->assertStatus(500)
                 ->assertJsonPath('status', 'error');
    }
}