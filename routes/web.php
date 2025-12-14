<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});
// JSA PDF export route
use App\Http\Controllers\JsaPdfController;

Route::get('/jsa/{jsa}/pdf', [JsaPdfController::class, 'show'])
    ->name('jsa.pdf');
