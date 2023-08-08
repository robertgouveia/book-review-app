<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = $request->input('title');
        $filter = $request->input('filter', '');

        //if filters are different send a manipulate request without the page var

        //if the title is not null then you can run a function
        //calling query scope (title search function) and inputting the title which the user sent to index
        $books = Book::when($title, fn ($query, $title) => $query->title($title));

        $books = match ($filter) {
            'popular_last_month' => $books->popularLastMonth(),
            'popular_last_6months' => $books->popularLast6Months(),
            'highest_rated_last_month' => $books->highestRatedLastMonth(),
            'highest_rated_last_6months' => $books->highestRatedLast6Months(),
            default => $books->latest()->withAverageRating()->withReviewsCount(),
        };

        $page = $request->query('page');
        $cacheKey = 'books:' . $filter . ':' . $title . ':' . $page;
        //remember books for one hour and use this rather than calling a query
        //if there is no books array in cache then get books and save for one hour
        $books = cache()->remember($cacheKey, 3600, fn () =>  $books->paginate(5)->appends($request->query()));

        return view('books.index', ['books' => $books]);
        //renders the view index but passes books as the new book array
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $cacheKey = 'book:' . $id;

        $book = cache()->remember($cacheKey, 3600, fn () => Book::with(['reviews' => fn ($query) => $query->latest()])->withAverageRating()->withReviewsCount()->findOrFail($id));

        return view('books.show', ['book' => $book]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
