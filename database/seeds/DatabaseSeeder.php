<?php

use App\Category;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //desabilita las restruicciones de claves foraneas
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        //vacia las tablas
        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        //desabilita los event listener durante el relleno de la base de datos
        User::flushEventListeners();
        Category::flushEventListeners();
        Product::flushEventListeners();
        Transaction::flushEventListeners();

        factory(User::class, 1000)->create();
        factory(Category::class, 30)->create();
        factory(Product::class, 1000)->create()->each(
            function ($product) {
                $categories = Category::all()->random(mt_rand(1, 5));
                $product->categories()->attach($categories);
            }
        );
        factory(Transaction::class, 1000)->create();
    }
}
