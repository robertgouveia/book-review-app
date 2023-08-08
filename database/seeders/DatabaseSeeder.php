<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Book::factory(33)->create()->each(function ($book) {
            //every single instance of the book
            $numReviews = random_int(5, 30);

            Review::factory()->count($numReviews)
                ->good()
                ->for($book)
                ->create();
            //this allows for a review to be made and it will be for this instance of the book
        });
        Book::factory(33)->create()->each(function ($book) {
            //every single instance of the book
            $numReviews = random_int(5, 30);

            Review::factory()->count($numReviews)
                ->average()
                ->for($book)
                ->create();
            //this allows for a review to be made and it will be for this instance of the book
        });
        Book::factory(34)->create()->each(function ($book) {
            //every single instance of the book
            $numReviews = random_int(5, 30);

            Review::factory()->count($numReviews)
                ->bad()
                ->for($book)
                ->create();
            //this allows for a review to be made and it will be for this instance of the book
        });
    }
}
