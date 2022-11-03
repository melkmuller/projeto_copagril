<?php

use App\Http\Controllers\ScraperController;
use Illuminate\Support\Facades\Route;

Route::get('/inicio', function () {
    return view('welcome');
});

Route::get('scraper', [ScraperController::class, 'scraper'])->name('scrapper');