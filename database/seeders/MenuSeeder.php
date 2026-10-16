<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mainCourse = \App\Models\Category::where('name', 'Main Course')->first()->id;
        $appetizers = \App\Models\Category::where('name', 'Appetizers')->first()->id;
        $desserts = \App\Models\Category::where('name', 'Desserts')->first()->id;

        \App\Models\MenuItem::create([
            'name' => 'Mandi Lamb',
            'description' => 'Slow-cooked lamb with fragrant basmati rice, infused with traditional Arabic spices and smoke.',
            'price' => 85000,
            'category_id' => $mainCourse,
            'image' => 'images/mandi.jpg'
        ]);

        \App\Models\MenuItem::create([
            'name' => 'Chicken Kabsa',
            'description' => 'The national dish of Saudi Arabia, featuring spiced rice and tender chicken.',
            'price' => 65000,
            'category_id' => $mainCourse,
            'image' => 'images/kabsa.jpg'
        ]);

        \App\Models\MenuItem::create([
            'name' => 'Hummus with Pita',
            'description' => 'Creamy chickpea dip with tahini, olive oil, and fresh warm pita bread.',
            'price' => 35000,
            'category_id' => $appetizers,
            'image' => 'images/hummus.jpg'
        ]);

        \App\Models\MenuItem::create([
            'name' => 'Baklava',
            'description' => 'Layers of filo pastry filled with chopped nuts and sweetened with honey syrup.',
            'price' => 25000,
            'category_id' => $desserts,
            'image' => 'images/baklava.jpg'
        ]);

        \App\Models\MenuItem::create([
            'name' => 'Samosa',
            'description' => 'Crispy fried pastry filled with spiced potatoes and peas.',
            'price' => 15000,
            'category_id' => $appetizers,
            'image' => 'images/samosa.jpg'
        ]);

        \App\Models\MenuItem::create([
            'name' => 'Chicken Biryani',
            'description' => 'Aromatic basmati rice layered with spiced chicken, herbs, and caramelized onions.',
            'price' => 55000,
            'category_id' => $mainCourse,
            'image' => 'images/biryani.jpg'
        ]);

        \App\Models\MenuItem::create([
            'name' => 'Chicken Karahi',
            'description' => 'Tender chicken cooked in a traditional wok with ginger, garlic, and fresh tomatoes.',
            'price' => 70000,
            'category_id' => $mainCourse,
            'image' => 'images/karahi.jpg'
        ]);
    }
}
