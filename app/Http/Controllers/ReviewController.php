<?php

namespace App\Http\Controllers;

use GuzzleHttp\Middleware;
use App\Models\Book;
use Illuminate\Http\Request;

class ReviewController extends Controller
{

    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Book $book)
    {
        return view('books.reviews.create', compact('book'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Book $book)
    {
        $review = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
        ]);

        $book->reviews()->create($review);
        return redirect()->route('books.index', $book);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return view('book.reviews.create', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        return view('books.reviews.edit', compact('book'));
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
