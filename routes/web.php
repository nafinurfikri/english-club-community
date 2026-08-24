<?php

use Illuminate\Support\Facades\Route;

    Route::get('/', function () {
        return view('guest.home');
        })->name('home');

    Route::get('/announcement', function () {
        return view('guest.announcement');
        })->name('announcement');

    Route::get('/gallery', function () {
        return view('guest.gallery');
        })->name('gallery');
    Route::get('/about', function () {
    return view('guest.about');
        })->name('about');