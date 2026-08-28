<?php

use Illuminate\Support\Facades\Route;

// Guest Routes
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

// Admin Routes
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/admin/attendance', function () {
    return view('admin.attendance');
})->name('admin.attendance');

Route::get('/admin/students', function () {
    return view('admin.students');
})->name('admin.students');

Route::get('/admin/grades', function () {
    return view('admin.grades');
})->name('admin.grades');

Route::get('/admin/announcements', function () {
    return view('admin.announcements');
})->name('admin.announcements');

Route::get('/admin/gallery', function () {
    return view('admin.gallery');
})->name('admin.gallery');