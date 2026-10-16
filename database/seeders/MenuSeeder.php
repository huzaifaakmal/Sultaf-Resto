<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            ['Chicken Karahi', 'Wok-cooked chicken with fresh ginger, tomato and whole spices.', 65000, 'menu/chicken_karahi.jpg', 4.9],
            ['White Karahi', 'Creamy white karahi with green chili and black pepper.', 70000, 'menu/white_karahi.jpg', 4.7],
            ['Butter Chicken', 'Tandoori chicken in a rich buttery tomato gravy.', 68000, 'menu/butter_chicken.jpg', 4.8],
            ['Tandoori Chicken', 'Marinated chicken roasted in the tandoor.', 60000, 'menu/tandoori_chicken.jpg', 4.6],
            ['Zurbiyan Rice', 'Yemeni spiced rice slow-cooked with tender meat.', 85000, 'menu/zurbiyan_rice.jpg', 4.9],
            ['Fasolya Hamra', 'Yemeni red bean stew served with fresh paratha.', 45000, 'menu/fasolya_hamra.jpg', 4.5],
            ['Kunafa', 'Crisp kunafa with melted cheese and sweet syrup.', 40000, 'menu/kunafa.jpg', 4.8],
            ['Mango Lassi', 'Chilled yogurt drink blended with ripe mango.', 25000, 'menu/mango_lassi.jpg', 4.7],
        ];

        // Views load images from the public disk (asset('storage/...')), so copy the sample photos there.
        foreach ($menus as [, , , $foto]) {
            $source = public_path('assets/'.basename($foto));
            if (is_file($source)) {
                Storage::disk('public')->put($foto, file_get_contents($source));
            }
        }

        foreach ($menus as [$nama, $deskripsi, $harga, $foto, $rating]) {
            Menu::updateOrCreate(['nama_menu' => $nama], [
                'deskripsi' => $deskripsi,
                'harga' => $harga,
                'foto_menu' => $foto,
                'rating' => $rating,
                'status_ketersediaan' => 'tersedia',
                'is_halal' => true,
            ]);
        }
    }
}
