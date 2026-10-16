<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminMenuFormTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $this->seed();

        return User::where('role', 'admin')->firstOrFail();
    }

    public function test_create_form_fields_are_saved(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())->post('/admin/menu', [
            'nama_makanan'  => 'Test Dish',
            'deskripsi'     => 'Tasty',
            'category_id'   => 1,
            'harga_makanan' => 45000,
            'harga_modal'   => 20000,
            'rating'        => 4.5,
            'spice_level'   => 2,
            'is_halal'      => 1,
            // 1x1 PNG, built without the GD extension
            'foto'          => UploadedFile::fake()->createWithContent(
                'dish.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==')
            ),
        ])->assertRedirect(route('admin.menu.index'));

        $menu = Menu::where('nama_menu', 'Test Dish')->firstOrFail();
        $this->assertEquals(45000, $menu->harga);
        $this->assertSame('tersedia', $menu->status_ketersediaan);
        $this->assertTrue($menu->is_halal);
        Storage::disk('public')->assertExists($menu->foto_menu);
    }

    public function test_create_form_requires_name_and_price(): void
    {
        $this->actingAs($this->admin())
            ->post('/admin/menu', ['deskripsi' => 'x'])
            ->assertSessionHasErrors(['nama_menu', 'nama_makanan', 'harga', 'harga_makanan']);
    }

    public function test_edit_form_updates_and_delete_removes(): void
    {
        $admin = $this->admin();
        $menu = Menu::firstOrFail();

        $this->actingAs($admin)->put("/admin/menu/{$menu->id_menu}", [
            'nama_menu'           => 'Renamed',
            'harga'               => 50000,
            'status_ketersediaan' => 'habis',
        ])->assertRedirect(route('admin.menu.index'));

        $menu->refresh();
        $this->assertSame('Renamed', $menu->nama_menu);
        $this->assertSame('habis', $menu->status_ketersediaan);

        $this->actingAs($admin)->delete("/admin/menu/{$menu->id_menu}")->assertRedirect(route('admin.menu.index'));
        $this->assertModelMissing($menu);
    }
}
