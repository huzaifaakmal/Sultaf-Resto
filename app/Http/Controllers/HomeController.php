<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = collect(); // Empty collection since categories table was removed

        $featuredMenus = Menu::where('habis', false)
            ->orderByDesc('rating')
            ->take(12)
            ->get();

        $info = [
            'name' => 'Sultaf Restaurant',
            'tagline' => 'Authentic Pakistani & Yemeni Flavors in the Heart of Yogyakarta',
            'description' => 'Experience the rich culinary heritage of Pakistan and Yemen right here in Condongcatur, Yogyakarta. From rich wok-cooked Chicken Karahi to hearty Yemeni Zurbiyan, Butter Chicken, and freshly baked Kunafa, every dish is prepared with authentic whole spices and passionate hospitality.',
            'address' => 'Jl. Harjosudiro No.129, Sanggrahan, Condongcatur, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55283',
            'phone' => '0813-1699-146',
            'whatsappNumber' => '628131699146',
            'hours' => '12:00 PM – 9:30 PM daily',
            'ratings' => [
                'google' => '4.9',
                'googleReviewsCount' => '150+',
                'gofood' => '4.5'
            ],
            'socials' => [
                'instagram' => 'https://instagram.com/sultafrestaurant',
                'instagramHandle' => '@sultafrestaurant',
                'tiktok' => 'https://tiktok.com/@sultaf.restaurant',
                'tiktokHandle' => '@sultaf.restaurant',
                'linktree' => 'https://linktr.ee/sultafrestaurant',
                'gofood' => 'https://gofood.link/sultafrestaurant'
            ],
            'mapEmbedUrl' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.279868748301!2d110.3958!3d-7.7601!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59966144e05b%3A0xb367123456789!2sJl.%20Harjosudiro%20No.129%2C%20Sanggrahan%2C%20Condongcatur%2C%20Kec.%20Depok%2C%20Kabupaten%20Sleman%2C%20Daerah%20Istimewa%20Yogyakarta%2055283!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid'
        ];

        $reviews = [
            [
                'name' => 'Ahmad Rizky',
                'rating' => 5,
                'source' => 'Google Review',
                'date' => '2 weeks ago',
                'text' => 'The Chicken Karahi here is the real deal! Tastes exactly like authentic Pakistani karahi in Lahore. Fresh ginger and piping hot. Great cozy outdoor seating too!'
            ],
            [
                'name' => 'Fatima Al-Habsyi',
                'rating' => 5,
                'source' => 'Google Review',
                'date' => '1 month ago',
                'text' => 'Subhanallah, their Zurbiyan Kambing and Fasolya Hamra with fresh Paratha are amazing. Warm atmosphere, polite staff, fast service, and high chairs for kids.'
            ],
            [
                'name' => 'Budi Santoso',
                'rating' => 5,
                'source' => 'GoFood Review',
                'date' => '3 weeks ago',
                'text' => 'Ordered Cheese Paratha, Samosa Ayam, and Mango Lassi via GoFood. Delivered hot and crispy. The Kunafa and Chai Adeni are 10/10, definitely ordering again!'
            ],
            [
                'name' => 'Sarah Miller',
                'rating' => 5,
                'source' => 'Google Review',
                'date' => '1 month ago',
                'text' => 'Best Pakistani & Middle Eastern spot in Jogja! Butter Chicken is rich & flavorful, and Mint Margarita is super refreshing.'
            ]
        ];

        $galleryPhotos = [
            ['title' => 'Yemeni Zurbiyan Lamb & Chicken Rice', 'image' => 'assets/zurbiyan_rice.jpg', 'category' => 'Food'],
            ['title' => 'Rich & Creamy Butter Chicken', 'image' => 'assets/butter_chicken.jpg', 'category' => 'Food'],
            ['title' => 'Flame-Roasted Tandoori Chicken', 'image' => 'assets/tandoori_chicken.jpg', 'category' => 'Food'],
            ['title' => 'Golden Melted Cheese & Aloo Paratha', 'image' => 'assets/stuffed_paratha.jpg', 'category' => 'Food'],
            ['title' => 'Authentic Egyptian Umm Ali Dessert', 'image' => 'assets/umm_ali.jpg', 'category' => 'Food'],
            ['title' => 'Aromatic Spiced Chai Adeni', 'image' => 'assets/chai_adeni.jpg', 'category' => 'Food'],
            ['title' => 'Crispy Tangy Pani Puri Platter', 'image' => 'assets/pani_puri.jpg', 'category' => 'Food'],
            ['title' => 'Middle Eastern Herb Omelette Breakfast', 'image' => 'assets/breakfast_egg.jpg', 'category' => 'Food'],
            ['title' => 'Wok-Simmered Chicken Karahi', 'image' => 'assets/chicken_karahi.jpg', 'category' => 'Food'],
            ['title' => 'Sweet Cheesy Kunafa Pastry', 'image' => 'assets/kunafa.jpg', 'category' => 'Food'],
            ['title' => 'Chilled Mango Lassi Smoothie', 'image' => 'assets/mango_lassi.jpg', 'category' => 'Food'],
            ['title' => 'Cozy Dining Ambiance & Seating', 'image' => 'assets/ambiance.jpg', 'category' => 'Interior']
        ];

        return view('welcome', compact('categories', 'featuredMenus', 'info', 'reviews', 'galleryPhotos'));
    }
}