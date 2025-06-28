<?php

use App\Http\Controllers\AdminCourseCategoryController;
use App\Http\Controllers\AdminCourseController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\MyCourseController;
use App\Http\Controllers\PengajarCourseController;
use App\Http\Controllers\PengajarDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseReviewController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PengajarTopicController;
use App\Http\Controllers\PengajarTopicVideoController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\RoleMiddleware;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/', fn () => view('landing.landing-page'))->name('landing-page');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/search', [CourseController::class, 'search'])->name('search');
Route::get('/courses', [CourseController::class, 'search'])->name('courses'); 
Route::post('/courses/{courseId}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
Route::post('/courses/{course}/reviews', [CourseReviewController::class, 'store'])->name('courses.reviews.store');

Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/courses/{courseId}/lessons/{lessonId}', [CourseController::class, 'showLesson'])->name('courses.lessons.show');

// Calendar routes
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
Route::get('/calendar/events/range', [CalendarController::class, 'getEventsForRange'])->name('calendar.events.range');
Route::get('/calendar/events/date', [CalendarController::class, 'getEventsForDate'])->name('calendar.events.date');
Route::get('/calendar/events/upcoming', [CalendarController::class, 'getUpcomingEvents'])->name('calendar.events.upcoming');
Route::post('/calendar/events', [CalendarController::class, 'store'])->name('calendar.events.store');
Route::get('/calendar/events/{event}', [CalendarController::class, 'show'])->name('calendar.events.show');
Route::put('/calendar/events/{event}', [CalendarController::class, 'update'])->name('calendar.events.update');
Route::delete('/calendar/events/{event}', [CalendarController::class, 'destroy'])->name('calendar.events.destroy');
Route::patch('/calendar/events/{event}/complete', [CalendarController::class, 'markAsCompleted'])->name('calendar.events.complete');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/my-course', [MyCourseController::class, 'index'])->name('my-course');
});

Route::prefix('teacher')->middleware('role:teacher')->name('pengajar.')->group(function () {
    Route::resource('courses', PengajarCourseController::class);
    Route::resource('courses.topics', PengajarTopicController::class);
    Route::resource('courses.topics.video', PengajarTopicVideoController::class);
    Route::get('dashboard', [PengajarDashboardController::class, 'index'])->name('dashboard');
});

Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::resource('users', controller: AdminUserController::class);
    Route::resource('categories', AdminCourseCategoryController::class)->parameters([
    'categories' => 'courseCategory',]);
    Route::resource('courses', AdminCourseController::class);
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});


Route::post('/payment/create', [PaymentController::class, 'createPayment'])->name('payment.create');
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::post('/payment/update-status', [PaymentController::class, 'updateStatus'])->name('payment.updateStatus');
Route::post('/payment/confirm', [PaymentController::class, 'confirm'])->name('payment.confirm');

Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');

