<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //redirect ke halaman login
    return redirect()->route('login');
  
});
// JSA PDF export route
use App\Http\Controllers\JsaPdfController;

Route::get('/jsa/{jsa}/pdf', [JsaPdfController::class, 'show'])
    ->name('jsa.pdf');
