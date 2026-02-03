<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use App\Http\Middleware\SetLocale;

Route::middleware([SetLocale::class])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::post('/locale', function (Request $request) {
        $locale = session('locale', app()->getLocale()) === 'en' ? 'ar' : 'en';
        session()->put('locale', $locale);
        return redirect()->back();
    })->name('set.locale');
});
