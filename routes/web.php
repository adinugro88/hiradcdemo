<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    redirect ('/admin/login');
});
// JSA PDF export route
use App\Http\Controllers\JsaPdfController;

Route::get('/jsa/{jsa}/pdf', [JsaPdfController::class, 'show'])
    ->name('jsa.pdf');
