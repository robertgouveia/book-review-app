<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['review', 'rating'];

    public function book()
    {
        //specifies this belongs to a book class
        return $this->belongsTo(Book::class);
    }

    public static function booted()
    {
        //when the review is updated update the cache for only the review id
        static::updated(fn (Review $review) => cache()->forget('book:' . $review->book_id));
        //when the review is deleted then forget the review from the cache
        static::deleted(fn (Review $review) => cache()->forget('book:' . $review->book_id));
        //when review added reset cache
        static::created(fn (Review $review) => cache()->forget('book:' . $review->book_id));
    }
}
