<?php

use App\Http\Controllers\AttendanceSheetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StudentsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard/Index');
    })->name('dashboard');

    Route::prefix('home')->name('home.')->group(function() {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::post('/update', [DashboardController::class, 'update'])->name('update');
    });

    Route::prefix('settings')->name('settings.')->group(function() {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::post('/update', [SettingsController::class, 'update'])->name('update');
        Route::post('/updateCourses', [SettingsController::class, 'updateCourses'])->name('updateCourses');
        Route::post('/deleteCourse', [SettingsController::class, 'deleteCourse'])->name('deleteCourse');
    });


    Route::prefix('students')->name('students.')->group(function() {
        Route::get('/', [StudentsController::class, 'index'])->name('index');
        Route::post('/store', [StudentsController::class, 'store'])->name('store');
        Route::post('/update', [StudentsController::class, 'update'])->name('update');
        Route::post('/delete', [StudentsController::class, 'destroy'])->name('delete');
    });

    Route::prefix('attendance')->name('attendance.')->group(function() {
        Route::get('/', [AttendanceSheetController::class, 'index'])->name('index');
        Route::post('/store', [AttendanceSheetController::class, 'store'])->name('store');
        Route::post('/update', [AttendanceSheetController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [AttendanceSheetController::class, 'destroy'])->name('delete');
    });



});
