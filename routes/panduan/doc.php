<?php

use App\Http\Controllers\Panduan\PanduanController;
use Illuminate\Support\Facades\Route;

Route::prefix('panduan')->controller(PanduanController::class)->name('panduan.')->group(function () {
    Route::get('/{slug?}', 'show')->where('slug', '.*')->name('show');
});
