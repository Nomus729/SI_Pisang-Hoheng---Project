<?php
class HomeController {
    public function index() {
        // Data Simulasi
        $data = [
            'title' => 'Si Pisang - Produk Unggulan',
            
            // Hero Section
            'hero_product' => [
                'name' => 'PISANG GORENG',
                'desc' => 'Pisang Goreng Panas dengan taburan keju dan coklat',
                'rating' => '4,9',
                'image' => 'https://placehold.co/400x400/e67e22/fff?text=Pisang+Utama' 
            ],

            // Daftar Menu
            'menu_items' => [
                ['name' => 'Pisang Goreng Keju', 'img' => 'https://placehold.co/200x150/f1c40f/fff?text=Keju'],
                ['name' => 'Pisang Coklat', 'img' => 'https://placehold.co/200x150/d35400/fff?text=Coklat'],
                ['name' => 'Pisang Matcha', 'img' => 'https://placehold.co/200x150/27ae60/fff?text=Matcha'],
                ['name' => 'Pisang Original', 'img' => 'https://placehold.co/200x150/f39c12/fff?text=Original'],
            ],

            // Produk Bawah (Lingkaran Menyembul)
            'bottom_products' => [
                ['img' => 'https://placehold.co/200x200/d35400/fff?text=Pisang+A'],
                ['img' => 'https://placehold.co/200x200/e67e22/fff?text=Pisang+B'],
                ['img' => 'https://placehold.co/200x200/c0392b/fff?text=Pisang+C'],
            ]
        ];

        // Load View
        require 'views/home.php';
    }
}
?>