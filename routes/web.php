<?php

use App\Http\Controllers\NovelController;
use Illuminate\Support\Facades\Route;

Route::get('/', [NovelController::class, 'home'])->name('home');

Route::prefix('novels')->group(function () {
    Route::get('/', [NovelController::class, 'index'])->name('novels.index');
    Route::get('/{slug}', [NovelController::class, 'show'])->name('novels.show');
    Route::get('/{novelSlug}/{chapterSlug}', [NovelController::class, 'readChapter'])->name('novels.read');
});

Route::get('/search', [NovelController::class, 'search'])->name('search');
