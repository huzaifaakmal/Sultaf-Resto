<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['Main Course', 'Appetizers', 'Desserts', 'Drinks'];
        foreach ($categories as $category) {
            \App\Models\Category::updateOrCreate(['name' => $category]);
        }
    }
}
