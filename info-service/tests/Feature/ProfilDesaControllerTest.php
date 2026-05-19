<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfilDesaControllerTest extends TestCase
{
    use RefreshDatabase;

    // ==========================================
    // --- KATA SAMBUTAN TESTS ---
    // ==========================================

    public function test_it_can_crud_kata_sambutan()
    {
        $create = $this->postJson('/api/profil/kata-sambutan', [
            'kata' => 'Sambutan Kepala Desa untuk warga',
        ]);

        $create->assertStatus(201)->assertJsonPath('data.kata', 'Sambutan Kepala Desa untuk warga');
        $kataSambutanId = $create->json('data.id');

        $this->getJson('/api/profil/kata-sambutan')
            ->assertStatus(200)
            ->assertJsonPath('data.0.id', $kataSambutanId);

        $this->getJson("/api/profil/kata-sambutan/{$kataSambutanId}")
            ->assertStatus(200)
            ->assertJsonPath('data.kata', 'Sambutan Kepala Desa untuk warga');

        $update = $this->putJson("/api/profil/kata-sambutan/{$kataSambutanId}", [
            'kata' => 'Sambutan Diperbarui',
        ]);

        $update->assertStatus(200)->assertJsonPath('data.kata', 'Sambutan Diperbarui');

        $delete = $this->deleteJson("/api/profil/kata-sambutan/{$kataSambutanId}");
        $delete->assertStatus(200)->assertJsonPath('status', 'success');
        
        $this->getJson("/api/profil/kata-sambutan/{$kataSambutanId}")->assertStatus(404);
    }

    public function test_it_returns_error_for_missing_kata_sambutan()
    {
        $response = $this->getJson('/api/profil/kata-sambutan/9999');
        $response->assertStatus(404)->assertJsonPath('status', 'error');
    }

    // ==========================================
    // --- VISI MISI TESTS ---
    // ==========================================

    public function test_it_can_crud_visi_misi()
    {
        $create = $this->postJson('/api/profil/visi-misi', [
            'visi' => 'Visi Desa Maju',
            'misi' => 'Misi 1: Pembangunan; Misi 2: Pendidikan',
        ]);

        $create->assertStatus(201)
            ->assertJsonPath('data.visi', 'Visi Desa Maju')
            ->assertJsonPath('data.misi', 'Misi 1: Pembangunan; Misi 2: Pendidikan');

        $visiMisiId = $create->json('data.id');

        $this->getJson('/api/profil/visi-misi')
            ->assertStatus(200)
            ->assertJsonPath('data.0.id', $visiMisiId);

        $this->getJson("/api/profil/visi-misi/{$visiMisiId}")
            ->assertStatus(200)
            ->assertJsonPath('data.visi', 'Visi Desa Maju');

        $update = $this->putJson("/api/profil/visi-misi/{$visiMisiId}", [
            'visi' => 'Visi Desa Mandiri',
            'misi' => 'Misi Baru',
        ]);

        $update->assertStatus(200)
            ->assertJsonPath('data.visi', 'Visi Desa Mandiri')
            ->assertJsonPath('data.misi', 'Misi Baru');

        $delete = $this->deleteJson("/api/profil/visi-misi/{$visiMisiId}");
        $delete->assertStatus(200)->assertJsonPath('status', 'success');
        
        $this->getJson("/api/profil/visi-misi/{$visiMisiId}")->assertStatus(404);
    }

    public function test_it_validates_visi_misi_required_fields()
    {
        $response = $this->postJson('/api/profil/visi-misi', [
            'visi' => 'Visi Only',
        ]);

        $response->assertStatus(422);
    }

    public function test_it_can_partially_update_visi_misi()
    {
        $create = $this->postJson('/api/profil/visi-misi', [
            'visi' => 'Visi Original',
            'misi' => 'Misi Original',
        ]);

        $id = $create->json('data.id');

        $update = $this->putJson("/api/profil/visi-misi/{$id}", [
            'visi' => 'Visi Updated Only',
        ]);

        $update->assertStatus(200)
            ->assertJsonPath('data.visi', 'Visi Updated Only')
            ->assertJsonPath('data.misi', 'Misi Original');
    }

    public function test_it_returns_error_for_missing_visi_misi()
    {
        $response = $this->getJson('/api/profil/visi-misi/9999');
        $response->assertStatus(404)->assertJsonPath('status', 'error');
    }

    // ==========================================
    // --- PERANGKAT DESA TESTS ---
    // ==========================================

    public function test_it_can_crud_perangkat_desa()
    {
        Storage::fake('public');

        $create = $this->post('/api/profil/perangkat-desa', [
            'nama' => 'Kepala Desa',
            'jabatan' => 'Kepala Desa',
            'foto' => UploadedFile::fake()->image('kepala.jpg'),
        ]);

        $create->assertStatus(201)->assertJsonPath('data.nama', 'Kepala Desa');
        $perangkatId = $create->json('data.id');

        $this->getJson('/api/profil/perangkat-desa')
            ->assertStatus(200)
            ->assertJsonPath('data.0.id', $perangkatId);

        $this->getJson("/api/profil/perangkat-desa/{$perangkatId}")
            ->assertStatus(200)
            ->assertJsonPath('data.jabatan', 'Kepala Desa');

        $update = $this->putJson("/api/profil/perangkat-desa/{$perangkatId}", [
            'nama' => 'Kepala Desa Updated',
            'jabatan' => 'Kepala Desa Definitif',
        ]);

        $update->assertStatus(200)->assertJsonPath('data.nama', 'Kepala Desa Updated');

        $delete = $this->deleteJson("/api/profil/perangkat-desa/{$perangkatId}");
        $delete->assertStatus(200)->assertJsonPath('status', 'success');
        
        $this->getJson("/api/profil/perangkat-desa/{$perangkatId}")->assertStatus(404);
    }

    public function test_it_can_update_perangkat_desa_with_new_foto()
    {
        Storage::fake('public');

        $create = $this->post('/api/profil/perangkat-desa', [
            'nama' => 'Sekretaris Desa',
            'jabatan' => 'Sekretaris',
            'foto' => UploadedFile::fake()->image('sekretaris.jpg'),
        ]);

        $perangkatId = $create->json('data.id');
        $oldFoto = basename($create->json('data.foto'));

        $this->assertTrue(Storage::disk('public')->exists('perangkat_desa_images/' . $oldFoto));

        $update = $this->post("/api/profil/perangkat-desa/{$perangkatId}", [
            '_method' => 'PUT',
            'nama' => 'Sekretaris Desa Updated',
            'foto' => UploadedFile::fake()->image('sekretaris-new.jpg'),
        ]);

        $update->assertStatus(200)->assertJsonPath('data.nama', 'Sekretaris Desa Updated');
        $newFoto = basename($update->json('data.foto'));
        $this->assertTrue(Storage::disk('public')->exists('perangkat_desa_images/' . $newFoto));
    }

    public function test_it_can_store_perangkat_desa_without_foto()
    {
        $create = $this->postJson('/api/profil/perangkat-desa', [
            'nama' => 'Bendahara',
            'jabatan' => 'Bendahara Desa',
        ]);

        $create->assertStatus(201)
            ->assertJsonPath('data.nama', 'Bendahara')
            ->assertJsonPath('data.jabatan', 'Bendahara Desa');
    }

    public function test_it_validates_perangkat_desa_required_fields()
    {
        Storage::fake('public');

        $response = $this->postJson('/api/profil/perangkat-desa', [
            'nama' => 'Tanpa Jabatan',
        ]);

        $response->assertStatus(422);
    }

    public function test_it_returns_error_for_missing_perangkat_desa()
    {
        $response = $this->getJson('/api/profil/perangkat-desa/9999');
        $response->assertStatus(404)->assertJsonPath('status', 'error');
    }

    public function test_it_deletes_old_foto_when_updating_perangkat_desa()
    {
        Storage::fake('public');

        $create = $this->post('/api/profil/perangkat-desa', [
            'nama' => 'Test Delete Foto',
            'jabatan' => 'Test',
            'foto' => UploadedFile::fake()->image('test.jpg'),
        ]);

        $perangkatId = $create->json('data.id');
        $oldFoto = basename($create->json('data.foto'));

        $this->assertTrue(Storage::disk('public')->exists('perangkat_desa_images/' . $oldFoto));

        $update = $this->post("/api/profil/perangkat-desa/{$perangkatId}", [
            '_method' => 'PUT',
            'nama' => 'Test Delete Foto Updated',
            'foto' => UploadedFile::fake()->image('test-new.jpg'),
        ]);

        $update->assertStatus(200);
        $this->assertFalse(Storage::disk('public')->exists('perangkat_desa_images/' . $oldFoto));
    }

    public function test_it_deletes_foto_when_deleting_perangkat_desa()
    {
        Storage::fake('public');

        $create = $this->post('/api/profil/perangkat-desa', [
            'nama' => 'To Be Deleted',
            'jabatan' => 'Test',
            'foto' => UploadedFile::fake()->image('delete.jpg'),
        ]);

        $perangkatId = $create->json('data.id');
        $foto = basename($create->json('data.foto'));

        $this->assertTrue(Storage::disk('public')->exists('perangkat_desa_images/' . $foto));

        $delete = $this->deleteJson("/api/profil/perangkat-desa/{$perangkatId}");
        $delete->assertStatus(200);

        $this->assertFalse(Storage::disk('public')->exists('perangkat_desa_images/' . $foto));
    }
}
