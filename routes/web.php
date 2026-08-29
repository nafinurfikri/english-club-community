<?php

use App\Http\Controllers\AdminContentController;
use App\Http\Controllers\AdminSearchController;
use App\Http\Controllers\AdminSessionController;
use App\Http\Controllers\AdminStudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradeController;
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
    Route::post('/sessions', [AdminSessionController::class, 'store'])->name('admin.sessions.store');
    Route::patch('/sessions/{clubSession}/open', [AdminSessionController::class, 'open'])->name('admin.sessions.open');
    Route::patch('/sessions/{clubSession}/close', [AdminSessionController::class, 'close'])->name('admin.sessions.close');
    Route::patch('/sessions/{clubSession}/code', [AdminSessionController::class, 'regenerateCode'])->name('admin.sessions.code');
    Route::post('/sessions/{clubSession}/materials', [AdminSessionController::class, 'storeMaterial'])->name('admin.materials.store');
    Route::patch('/attendance/status', [AdminSessionController::class, 'updateStatus'])->name('admin.attendance.status');

    Route::get('/students', [AdminStudentController::class, 'index'])->name('admin.students');
    Route::post('/students', [AdminStudentController::class, 'store'])->name('admin.students.store');
    Route::patch('/students/{user}/status', [AdminStudentController::class, 'updateStatus'])->name('admin.students.status');
    Route::delete('/students/{user}', [AdminStudentController::class, 'destroy'])->name('admin.students.destroy');

    Route::get('/grades', [GradeController::class, 'adminIndex'])->name('admin.grades');
    Route::post('/grades', [GradeController::class, 'store'])->name('admin.grades.store');
    Route::put('/grades/{grade}', [GradeController::class, 'update'])->name('admin.grades.update');
    Route::delete('/grades/{grade}', [GradeController::class, 'destroy'])->name('admin.grades.destroy');
    Route::post('/grade-categories', [GradeController::class, 'storeCategory'])->name('admin.grade-categories.store');
    Route::put('/grade-categories/{gradeCategory}', [GradeController::class, 'updateCategory'])->name('admin.grade-categories.update');
    Route::delete('/grade-categories/{gradeCategory}', [GradeController::class, 'destroyCategory'])->name('admin.grade-categories.destroy');

    Route::get('/subjects', [SubjectController::class, 'adminIndex'])->name('admin.subjects');
    Route::post('/subjects', [SubjectController::class, 'store'])->name('admin.subjects.store');
    Route::put('/subjects/{subject}', [SubjectController::class, 'update'])->name('admin.subjects.update');
    Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])->name('admin.subjects.destroy');

    Route::get('/announcements', [AdminContentController::class, 'announcements'])->name('admin.announcements');
    Route::post('/announcements', [AdminContentController::class, 'announcement'])->name('admin.announcements.store');
    Route::put('/announcements/{announcement}', [AdminContentController::class, 'updateAnnouncement'])->name('admin.announcements.update');
    Route::delete('/announcements/{announcement}', [AdminContentController::class, 'deleteAnnouncement'])->name('admin.announcements.destroy');

    Route::get('/landing', [AdminContentController::class, 'landingIndex'])->name('admin.landing');
    Route::put('/landing/{key}', [AdminContentController::class, 'landing'])->name('admin.landing.update');

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

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('student.attendance');
    Route::post('/attendance/{clubSession}', [AttendanceController::class, 'store'])->name('student.attendance.store');
    Route::get('/materials/{material}', [MaterialController::class, 'show'])->name('student.materials.show');

    Route::get('/grades', [GradeController::class, 'index'])->name('student.grades');

    Route::get('/announcements', function () {
        return view('student.announcements');
    })->name('student.announcements');

    Route::get('/profile', [StudentProfileController::class, 'edit'])->name('student.profile');
    Route::patch('/profile', [StudentProfileController::class, 'update'])->name('student.profile.update');
});
