<?php

use App\Http\Controllers\BookController;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('books.index');
});


Route::resource('books', BookController::class);
