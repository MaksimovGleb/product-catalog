<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Создаем администратора
        User::firstOrCreate(
            ['email' => 'Admin@mail.ru'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        // Создаем категории и товары
        $categories = [
            'Электроника' => 'Гаджеты, смартфоны и аксессуары',
            'Одежда' => 'Мужская и женская одежда',
            'Дом и сад' => 'Товары для уюта и дачи',
            'Спорт' => 'Спортивный инвентарь и питание',
            'Книги' => 'Художественная и научная литература',
        ];

        foreach ($categories as $name => $description) {
            $category = Category::create([
                'name' => $name,
                'description' => $description,
            ]);

            Product::factory(10)->create([
                'category_id' => $category->id,
            ]);
        }
    }
}
