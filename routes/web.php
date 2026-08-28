<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

Route::get('/register', function () {
    return view('auth.register');
})->name('student.register');

Route::post('/student/register', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    return redirect()->route('student.register')->with('success', 'Pendaftaran berhasil. Akun kamu sudah dibuat.');
})->name('student.register.store');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    if (! Auth::attempt($credentials, $request->boolean('remember'))) {
        return back()
            ->withErrors(['email' => 'Email atau password tidak sesuai.'])
            ->withInput($request->only('email'));
    }

    $request->session()->regenerate();

    return redirect()->intended(route('student.dashboard'));
})->name('login.store');

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

// Student Routes
Route::get('/student/dashboard', function () {
    return view('student.dashboard');
})->name('student.dashboard');

Route::get('/student/subjects', function () {
    return view('student.subjects');
})->name('student.subjects');


Route::get('/student/attendance', function () {
    return view('student.attendance');
})->name('student.attendance');

Route::get('/student/grades', function () {
    return view('student.grades');
})->name('student.grades');

Route::get('/student/announcements', function () {
    return view('student.announcements');
})->name('student.announcements');

Route::get('/student/profile', function () {
    return view('student.profile');
})->name('student.profile');
