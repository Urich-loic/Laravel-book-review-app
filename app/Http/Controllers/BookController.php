<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = $request->input('title');
        $filter = $request->input('filter', '');

        $books = Book::when(
            $title,
            fn($query, $title) => $query->Title('title', 'like', "%{$title}%")->withCount('reviews')->orderBy('reviews_count', 'desc')
        );

        $books = match ($filter) {
            'popular' => $books->Popular()->latest()->paginate(5),
            'highest_rated' => $books->highestRated()->latest()->paginate(5),
            'popular_last_month' =>  $books->popularLastMonth()->latest()->paginate(5),
            'popular_last_6months' => $books->PopularLast6Months()->latest()->paginate(5),
            'highest_rated_last_month' => $books->HighestRatedLastMonth()->latest()->paginate(5),
            'highest_rated_last_6months' => $books->HighestRatedLast6Months()->latest()->paginate(5),
            default =>  $books->latest()->withPopular()->withHighestRated()->paginate(5)
        };

        // $cacheKey = 'book:' . $title . ':' . $filter;
        // cache()->remember($cacheKey, 3600, fn() => $books);

        return view('books.index', compact('books'));
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
        $cachedKey = 'book:' . $id;
        $book = cache()->remember(
            $cachedKey,
            3600,
            fn() => Book::with(['reviews' => fn($q) => $q->latest()])->withPopular()->withHighestRated()->findOrFail($id)
        );
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
