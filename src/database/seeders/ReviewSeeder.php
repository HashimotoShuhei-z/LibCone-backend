<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        Review::create([
            'user_id' => 1,
            'company_book_id' => 1,
            'review_title' => 'Laravelを始める人は読むべき!',
            'review_content' => 'Laravelを0から学べる最高の書籍。イラストもついていて理解しやすくなっている。',
            'review_rate' => 5,
        ]);
    }
}
