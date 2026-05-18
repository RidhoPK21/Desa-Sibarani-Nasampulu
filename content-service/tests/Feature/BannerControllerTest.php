<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BannerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_crud_banner_and_public_index()
    {
        Storage::fake('public');

        $create = $this->post('/api/banner', [
            'nama_banner' => 'Banner Test',
            'gambar_banner' => UploadedFile::fake()->image('banner.jpg'),
            'urutan' => 1,
            'shown' => true,
        ]);

        $create->assertStatus(201)->assertJsonPath('data.nama_banner', 'Banner Test');
        $bannerId = $create->json('data.id');

        $this->getJson('/api/banner')->assertStatus(200)->assertJsonPath('data.0.id', $bannerId);
        $this->getJson('/api/banner/public')->assertStatus(200)->assertJsonPath('data.0.id', $bannerId);
        $this->getJson("/api/banner/{$bannerId}")->assertStatus(200)->assertJsonPath('data.id', $bannerId);

        $update = $this->putJson("/api/banner/{$bannerId}", [
            'nama_banner' => 'Banner Updated',
            'urutan' => 2,
            'shown' => false,
        ]);

        $update->assertStatus(200)->assertJsonPath('data.nama_banner', 'Banner Updated')->assertJsonPath('data.shown', false);

        $delete = $this->deleteJson("/api/banner/{$bannerId}");
        $delete->assertStatus(200)->assertJsonPath('status', 'success');
        $this->getJson("/api/banner/{$bannerId}")->assertStatus(404);
    }

    public function test_it_can_default_order_when_creating_banner_and_update_image()
    {
        Storage::fake('public');

        $create = $this->post('/api/banner', [
            'nama_banner' => 'Banner Default Order',
            'gambar_banner' => UploadedFile::fake()->image('banner1.jpg'),
            'shown' => true,
        ]);

        $create->assertStatus(201)
               ->assertJsonPath('data.nama_banner', 'Banner Default Order')
               ->assertJsonPath('data.urutan', 1);

        $bannerId = $create->json('data.id');

        $update = $this->post("/api/banner/{$bannerId}", [
            '_method' => 'PUT',
            'nama_banner' => 'Banner Updated Image',
            'gambar_banner' => UploadedFile::fake()->image('banner2.jpg'),
            'shown' => false,
        ]);

        $update->assertStatus(200)
               ->assertJsonPath('data.nama_banner', 'Banner Updated Image')
               ->assertJsonPath('data.shown', false);

        $this->assertTrue(Storage::disk('public')->exists('banner_images/' . basename($update->json('data.gambar_banner'))));
    }
}
