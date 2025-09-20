<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('books.index');
});


Route::resource('books', BookController::class)->only(['index', 'show']);
Route::resource('books.reviews', ReviewController::class)->scoped([
    'reviews' => 'books',
])->only(['create', 'store', 'edit'])->middleware('throttle:reviews');
