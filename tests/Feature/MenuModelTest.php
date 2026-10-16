<?php

namespace Tests\Feature;

use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_legacy_aliases_map_to_database_columns(): void
    {
        $menu = Menu::create([
            'nama_menu' => 'Chicken Karahi', 'harga' => 85000, 'status_ketersediaan' => 'habis',
        ]);

        $this->assertSame($menu->id_menu, $menu->id);
        $this->assertSame('Chicken Karahi', $menu->nama_makanan);
        $this->assertEquals(85000, $menu->harga_makanan);
        $this->assertTrue($menu->habis);
        $this->assertSame('Rp 85.000', $menu->harga_format);
    }
}
