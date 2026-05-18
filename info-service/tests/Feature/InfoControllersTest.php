<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InfoControllersTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_crud_berita()
    {
        Storage::fake('public');

        $create = $this->post('/api/berita', [
            'judul' => 'Berita Test',
            'konten' => 'Isi berita testing',
            'gambar_url' => UploadedFile::fake()->image('berita.jpg'),
            'is_published' => true,
        ]);

        $create->assertStatus(201)->assertJsonPath('data.judul', 'Berita Test');
        $id = $create->json('data.id');

        $this->getJson('/api/berita')->assertStatus(200)->assertJsonPath('data.0.id', $id);
        $this->getJson("/api/berita/{$id}")->assertStatus(200)->assertJsonPath('data.judul', 'Berita Test');

        $update = $this->putJson("/api/berita/{$id}", [
            'judul' => 'Berita Updated',
            'konten' => 'Konten updated',
            'is_published' => false,
        ]);

        $update->assertStatus(200)->assertJsonPath('data.judul', 'Berita Updated');

        $delete = $this->deleteJson("/api/berita/{$id}");
        $delete->assertStatus(200)->assertJsonPath('status', 'success');
        $this->getJson("/api/berita/{$id}")->assertStatus(404);
    }

    public function test_it_can_update_berita_with_new_image()
    {
        Storage::fake('public');

        $create = $this->post('/api/berita', [
            'judul' => 'Berita Image Test',
            'konten' => 'Isi berita testing',
            'gambar_url' => UploadedFile::fake()->image('berita.jpg'),
            'is_published' => true,
        ]);

        $id = $create->json('data.id');
        $firstImage = basename($create->json('data.gambar_url'));
        $this->assertTrue(Storage::disk('public')->exists('berita_images/' . $firstImage));

        $update = $this->post("/api/berita/{$id}", [
            '_method' => 'PUT',
            'gambar_url' => UploadedFile::fake()->image('berita-new.jpg'),
            'is_published' => false,
        ]);

        $update->assertStatus(200)->assertJsonPath('data.is_published', false);
        $this->assertTrue(Storage::disk('public')->exists('berita_images/' . basename($update->json('data.gambar_url'))));
    }

    public function test_it_can_crud_dokumen()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf');
        $create = $this->post('/api/dokumen', [
            'nama_ppid' => 'Dokumen Test',
            'jenis_ppid' => 'Informasi Berkala',
            'deskripsi_ppid' => 'Deskripsi dokumen',
            'file' => $file,
        ]);

        $create->assertStatus(201)->assertJsonPath('data.nama_ppid', 'Dokumen Test');
        $id = $create->json('data.id');

        $this->getJson('/api/dokumen')->assertStatus(200)->assertJsonPath('data.0.id', $id);
        $this->getJson("/api/dokumen/{$id}")->assertStatus(200)->assertJsonPath('data.jenis_ppid', 'Informasi Berkala');

        $update = $this->putJson("/api/dokumen/{$id}", [
            'nama_ppid' => 'Dokumen Updated',
            'jenis_ppid' => 'Informasi Serta Merta',
            'deskripsi_ppid' => 'Deskripsi baru',
        ]);

        $update->assertStatus(200)->assertJsonPath('data.nama_ppid', 'Dokumen Updated');

        $delete = $this->deleteJson("/api/dokumen/{$id}");
        $delete->assertStatus(200)->assertJsonPath('status', 'success');
    }

    public function test_it_can_update_dokumen_with_new_file()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf');

        $create = $this->post('/api/dokumen', [
            'nama_ppid' => 'Dokumen File Test',
            'jenis_ppid' => 'Informasi Berkala',
            'deskripsi_ppid' => 'Deskripsi file test',
            'file' => $file,
        ]);

        $id = $create->json('data.id');
        $oldFile = basename($create->json('data.file'));
        $this->assertTrue(Storage::disk('public')->exists('dokumen_ppid/' . $oldFile));

        $update = $this->post('/api/dokumen/' . $id, [
            '_method' => 'PUT',
            'nama_ppid' => 'Dokumen File Updated',
            'jenis_ppid' => 'Informasi Serta Merta',
            'deskripsi_ppid' => 'Deskripsi diperbarui',
            'file' => UploadedFile::fake()->create('dokumen-new.pdf', 120, 'application/pdf'),
        ]);

        $update->assertStatus(200)->assertJsonPath('data.nama_ppid', 'Dokumen File Updated');
        $this->assertTrue(Storage::disk('public')->exists('dokumen_ppid/' . basename($update->json('data.file'))));
    }

    public function test_it_can_crud_kegiatan()
    {
        Storage::fake('public');

        $create = $this->post('/api/kegiatan', [
            'jenis_kegiatan' => 'program kerja',
            'judul_kegiatan' => 'Program Test',
            'deskripsi_kegiatan' => 'Deskripsi kegiatan test',
            'gambar' => UploadedFile::fake()->image('kegiatan.jpg'),
            'tanggal_pelaksana' => '2026-05-18',
            'tanggal_berakhir' => '2026-05-18',
            'status_kegiatan' => 'Akan Datang',
        ]);

        $create->assertStatus(201)->assertJsonPath('data.judul_kegiatan', 'Program Test');
        $id = $create->json('data.id');

        $this->getJson('/api/kegiatan')->assertStatus(200)->assertJsonPath('data.0.id', $id);
        $this->getJson("/api/kegiatan/{$id}")->assertStatus(200)->assertJsonPath('data.judul_kegiatan', 'Program Test');

        $update = $this->putJson("/api/kegiatan/{$id}", [
            'jenis_kegiatan' => 'program kerja',
            'judul_kegiatan' => 'Program Updated',
            'tanggal_pelaksana' => '2026-05-18',
            'tanggal_berakhir' => '2026-05-18',
        ]);

        $update->assertStatus(200)->assertJsonPath('data.judul_kegiatan', 'Program Updated');

        $delete = $this->deleteJson("/api/kegiatan/{$id}");
        $delete->assertStatus(200)->assertJsonPath('status', 'success');
    }

    public function test_it_can_update_kegiatan_with_new_image()
    {
        Storage::fake('public');

        $create = $this->post('/api/kegiatan', [
            'jenis_kegiatan' => 'program kerja',
            'judul_kegiatan' => 'Program Test',
            'deskripsi_kegiatan' => 'Deskripsi kegiatan test',
            'gambar' => UploadedFile::fake()->image('kegiatan.jpg'),
            'tanggal_pelaksana' => '2026-05-18',
            'tanggal_berakhir' => '2026-05-18',
            'status_kegiatan' => 'Akan Datang',
        ]);

        $id = $create->json('data.id');
        $oldImage = basename($create->json('data.gambar'));
        $this->assertTrue(Storage::disk('public')->exists('kegiatan_images/' . $oldImage));

        $update = $this->post('/api/kegiatan/' . $id, [
            '_method' => 'PUT',
            'jenis_kegiatan' => 'program kerja',
            'gambar' => UploadedFile::fake()->image('kegiatan-new.jpg'),
        ]);

        $update->assertStatus(200)->assertJsonPath('data.id', $id);
        $this->assertTrue(Storage::disk('public')->exists('kegiatan_images/' . basename($update->json('data.gambar'))));
    }

    public function test_it_can_crud_apbdes_versioning_and_history()
    {
        $create = $this->postJson('/api/apbdes', [
            'nama_desa' => 'Desa Test',
            'tahun' => 2025,
        ]);

        $create->assertStatus(201)->assertJsonPath('data.tahun', 2025);
        $id = $create->json('data.id');

        $this->getJson('/api/apbdes')->assertStatus(200)->assertJsonPath('data.0.id', $id);
        $this->getJson("/api/apbdes/{$id}")->assertStatus(200)->assertJsonPath('data.nama_desa', 'Desa Test');

        $update = $this->putJson("/api/apbdes/{$id}", [
            'alasan_perubahan' => 'Perubahan data',
            'nama_desa' => 'Desa Test Update',
        ]);

        $update->assertStatus(200)->assertJsonPath('data.versi', 2)->assertJsonPath('data.nama_desa', 'Desa Test Update');
        $this->getJson('/api/apbdes/riwayat/2025')->assertStatus(200)->assertJsonCount(2, 'data');

        $delete = $this->deleteJson("/api/apbdes/{$id}");
        $delete->assertStatus(200)->assertJsonPath('status', 'success');
    }

    public function test_it_rejects_duplicate_apbdes_year()
    {
        $this->postJson('/api/apbdes', [
            'nama_desa' => 'Desa Duplicate',
            'tahun' => 2026,
        ])->assertStatus(201);

        $duplicate = $this->postJson('/api/apbdes', [
            'nama_desa' => 'Desa Duplicate',
            'tahun' => 2026,
        ]);

        $duplicate->assertStatus(400)
                  ->assertJsonPath('status', 'error')
                  ->assertJsonPath('message', 'Data APBDes untuk tahun 2026 sudah ada! Silakan gunakan tombol "Ubah & Buat Versi Baru" (ikon pesan) pada tabel.');
    }

    public function test_it_can_crud_profil_desa_subresources()
    {
        // Kata Sambutan
        $kata = $this->postJson('/api/profil/kata-sambutan', ['kata' => 'Sambutan test']);
        $kata->assertStatus(201)->assertJsonPath('data.kata', 'Sambutan test');
        $kataId = $kata->json('data.id');
        $this->getJson('/api/profil/kata-sambutan')->assertStatus(200)->assertJsonPath('data.0.id', $kataId);
        $this->getJson("/api/profil/kata-sambutan/{$kataId}")->assertStatus(200)->assertJsonPath('data.kata', 'Sambutan test');
        $this->putJson("/api/profil/kata-sambutan/{$kataId}", ['kata' => 'Sambutan update'])->assertStatus(200)->assertJsonPath('data.kata', 'Sambutan update');
        $this->deleteJson("/api/profil/kata-sambutan/{$kataId}")->assertStatus(200);

        // Visi Misi
        $visi = $this->postJson('/api/profil/visi-misi', ['visi' => 'Visi test', 'misi' => 'Misi test']);
        $visi->assertStatus(201)->assertJsonPath('data.visi', 'Visi test');
        $visiId = $visi->json('data.id');
        $this->getJson('/api/profil/visi-misi')->assertStatus(200)->assertJsonPath('data.0.id', $visiId);
        $this->getJson("/api/profil/visi-misi/{$visiId}")->assertStatus(200)->assertJsonPath('data.misi', 'Misi test');
        $this->putJson("/api/profil/visi-misi/{$visiId}", ['visi' => 'Visi update'])->assertStatus(200)->assertJsonPath('data.visi', 'Visi update');
        $this->deleteJson("/api/profil/visi-misi/{$visiId}")->assertStatus(200);

        // Perangkat Desa
        Storage::fake('public');
        $perangkat = $this->post('/api/profil/perangkat-desa', [
            'nama' => 'Admin Test',
            'jabatan' => 'Kepala Desa',
            'foto' => UploadedFile::fake()->image('admin.jpg'),
        ]);
        $perangkat->assertStatus(201)->assertJsonPath('data.nama', 'Admin Test');
        $perangkatId = $perangkat->json('data.id');
        $this->getJson('/api/profil/perangkat-desa')->assertStatus(200)->assertJsonPath('data.0.id', $perangkatId);
        $this->getJson("/api/profil/perangkat-desa/{$perangkatId}")->assertStatus(200)->assertJsonPath('data.nama', 'Admin Test');
        $this->putJson("/api/profil/perangkat-desa/{$perangkatId}", ['nama' => 'Admin Updated', 'jabatan' => 'Sekretaris'])->assertStatus(200)->assertJsonPath('data.nama', 'Admin Updated');
        $this->deleteJson("/api/profil/perangkat-desa/{$perangkatId}")->assertStatus(200);
    }

    public function test_it_can_update_profil_perangkat_desa_with_new_photo()
    {
        Storage::fake('public');

        $create = $this->post('/api/profil/perangkat-desa', [
            'nama' => 'Operator Test',
            'jabatan' => 'Staf',
            'foto' => UploadedFile::fake()->image('operator.jpg'),
        ]);

        $id = $create->json('data.id');
        $oldPhoto = basename($create->json('data.foto'));
        $this->assertTrue(Storage::disk('public')->exists('perangkat_desa_images/' . $oldPhoto));

        $update = $this->post('/api/profil/perangkat-desa/' . $id, [
            '_method' => 'PUT',
            'jabatan' => 'Staf Updated',
            'foto' => UploadedFile::fake()->image('operator-new.jpg'),
        ]);

        $update->assertStatus(200)->assertJsonPath('data.jabatan', 'Staf Updated');
        $this->assertTrue(Storage::disk('public')->exists('perangkat_desa_images/' . basename($update->json('data.foto'))));
    }
}
