<?php

use App\Http\Controllers\AdminContentController;
use App\Http\Controllers\AdminSearchController;
use App\Http\Controllers\AdminSessionController;
use App\Http\Controllers\AdminStudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PublicContentController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::get('/', [PublicContentController::class, 'home'])->name('home');

Route::get('/announcement', [PublicContentController::class, 'announcements'])->name('announcement');

Route::get('/gallery', [PublicContentController::class, 'gallery'])->name('gallery');

Route::get('/about', function () {
    return view('guest.about');
})->name('about');

Route::get('/register', [AuthController::class, 'showRegister'])->name('student.register');
Route::post('/student/register', [AuthController::class, 'register'])->name('student.register.store');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'active', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');

    Route::get('/search', [AdminSearchController::class, 'index'])->name('admin.search');

    Route::get('/attendance', [AdminSessionController::class, 'index'])->name('admin.attendance');
    Route::get('/attendance/history', [AdminSessionController::class, 'history'])->name('admin.attendance.history');
    Route::get('/attendance/history/{clubSession}', [AdminSessionController::class, 'historySession'])->name('admin.attendance.history.session');
    Route::post('/sessions', [AdminSessionController::class, 'store'])->name('admin.sessions.store');
    Route::patch('/sessions/{clubSession}/open', [AdminSessionController::class, 'open'])->name('admin.sessions.open');
    Route::patch('/sessions/{clubSession}/close', [AdminSessionController::class, 'close'])->name('admin.sessions.close');
    Route::patch('/sessions/{clubSession}/code', [AdminSessionController::class, 'regenerateCode'])->name('admin.sessions.code');
    Route::patch('/sessions/{clubSession}/revoke-code', [AdminSessionController::class, 'revokeCode'])->name('admin.sessions.revoke-code');
    Route::get('/sessions/{clubSession}/otp', [AdminSessionController::class, 'otp'])->name('admin.sessions.otp');
    Route::post('/sessions/{clubSession}/materials', [AdminSessionController::class, 'storeMaterial'])->name('admin.materials.store');
    Route::patch('/session-materials/{material}', [AdminSessionController::class, 'publishSessionMaterial'])->name('admin.session-materials.publish');
    Route::delete('/session-materials/{material}', [AdminSessionController::class, 'destroySessionMaterial'])->name('admin.session-materials.destroy');
    Route::patch('/attendance/status', [AdminSessionController::class, 'updateStatus'])->name('admin.attendance.status');

    Route::get('/students', [AdminStudentController::class, 'index'])->name('admin.students');
    Route::post('/students', [AdminStudentController::class, 'store'])->name('admin.students.store');
    Route::patch('/students/{user}/status', [AdminStudentController::class, 'updateStatus'])->name('admin.students.status');
    Route::delete('/students/{user}', [AdminStudentController::class, 'destroy'])->name('admin.students.destroy');

    Route::get('/subjects', [SubjectController::class, 'adminIndex'])->name('admin.subjects');
    Route::post('/subjects', [SubjectController::class, 'store'])->name('admin.subjects.store');
    Route::put('/subjects/{subject}', [SubjectController::class, 'update'])->name('admin.subjects.update');
    Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])->name('admin.subjects.destroy');
    Route::post('/subjects/{subject}/materials', [SubjectController::class, 'storeMaterial'])->name('admin.subjects.materials.store');
    Route::put('/materials/{material}', [SubjectController::class, 'updateMaterial'])->name('admin.subjects.materials.update');
    Route::delete('/materials/{material}', [SubjectController::class, 'destroyMaterial'])->name('admin.subjects.materials.destroy');

    Route::get('/announcements', [AdminContentController::class, 'announcements'])->name('admin.announcements');
    Route::post('/announcements', [AdminContentController::class, 'announcement'])->name('admin.announcements.store');
    Route::put('/announcements/{announcement}', [AdminContentController::class, 'updateAnnouncement'])->name('admin.announcements.update');
    Route::delete('/announcements/{announcement}', [AdminContentController::class, 'deleteAnnouncement'])->name('admin.announcements.destroy');

    Route::get('/gallery', [AdminContentController::class, 'gallery'])->name('admin.gallery');
    Route::post('/gallery', [AdminContentController::class, 'galleryItem'])->name('admin.gallery.store');
    Route::put('/gallery/{galleryItem}', [AdminContentController::class, 'updateGallery'])->name('admin.gallery.update');
    Route::delete('/gallery/{galleryItem}', [AdminContentController::class, 'deleteGallery'])->name('admin.gallery.destroy');

    Route::post('/gallery-categories', [AdminContentController::class, 'storeCategory'])->name('admin.gallery-categories.store');
    Route::put('/gallery-categories/{galleryCategory}', [AdminContentController::class, 'updateCategory'])->name('admin.gallery-categories.update');
    Route::delete('/gallery-categories/{galleryCategory}', [AdminContentController::class, 'deleteCategory'])->name('admin.gallery-categories.destroy');
});

// Student Routes
Route::middleware(['auth', 'active'])->prefix('student')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('student.dashboard');

    Route::get('/subjects', [SubjectController::class, 'studentIndex'])->name('student.subjects');
    Route::get('/subjects/{subject}', [SubjectController::class, 'show'])->name('student.subjects.show');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('student.attendance');
    Route::post('/attendance/{clubSession}', [AttendanceController::class, 'store'])->name('student.attendance.store');
    Route::get('/materials/{material}', [MaterialController::class, 'show'])->name('student.materials.show');

    Route::get('/announcements', function () {
        return view('student.announcements');
    })->name('student.announcements');

    Route::get('/profile', [StudentProfileController::class, 'edit'])->name('student.profile');
    Route::patch('/profile', [StudentProfileController::class, 'update'])->name('student.profile.update');
});
