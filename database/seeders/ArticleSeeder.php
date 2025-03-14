<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class ArticleSeeder extends Seeder
{
    public function run()
    {
        Article::factory()->count(10)->create([
            'source_name' => 'The Guardian'
        ]);

        Article::factory()->count(10)->create([
            'source_name' => 'The New York Times'
        ]);
    }
}
