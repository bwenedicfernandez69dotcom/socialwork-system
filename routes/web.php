<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Scheduler Controllers
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\CalendarController;

// Admin Controllers
use App\Http\Controllers\Admin\FacultyController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\AdminScheduleController;
use App\Http\Controllers\Admin\FacultyAvailabilityController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ReportController;

// Faculty Controllers
use App\Http\Controllers\Faculty\FacultyScheduleController;
use App\Http\Controllers\Faculty\AvailabilityController;
use App\Http\Controllers\Faculty\NotificationController;

use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', fn () => redirect()->route('login'));

// Guest routes (login only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
});

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Default Dashboard
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /**
     * Scheduler Module
     */
    Route::middleware(['role:scheduler'])->prefix('schedules')->name('schedules.')->group(function () {

        // Calendar view
        Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');

        // Timetable view
        Route::get('/timetable', [ScheduleController::class, 'timetable'])->name('timetable');

        // ✅ Export Timetable to PDF
        Route::get('/timetable/export-pdf', [ScheduleController::class, 'exportTimetablePDF'])
            ->name('timetable.export.pdf');

        // CRUD routes
        Route::get('/', [ScheduleController::class, 'index'])->name('index');
        Route::get('/create', [ScheduleController::class, 'create'])->name('create');
        Route::post('/', [ScheduleController::class, 'store'])->name('store');
        Route::get('/{schedule}/edit', [ScheduleController::class, 'edit'])->name('edit');
        Route::put('/{schedule}', [ScheduleController::class, 'update'])->name('update');
        Route::delete('/{schedule}', [ScheduleController::class, 'destroy'])->name('destroy');

        // ✅ Subject Filter Route (for scheduler)
        Route::get('/subjects/filter', [SubjectController::class, 'filter'])->name('subjects.filter');
    });

    /**
     * Admin Module
     */
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // CRUD
        Route::resource('faculties', FacultyController::class);
        Route::resource('rooms', RoomController::class);
        Route::resource('subjects', SubjectController::class);
        Route::resource('sections', SectionController::class);

        // ✅ Subject Filter Route (for admin)
        Route::get('/subjects/filter', [SubjectController::class, 'filter'])->name('subjects.filter');

        // Faculty Availabilities
        Route::prefix('faculties/{faculty}')->name('faculties.')->group(function () {
            Route::get('/availabilities', [FacultyAvailabilityController::class, 'index'])
                ->name('availabilities.index');
        });

        // Schedule viewing
        Route::get('/schedules', [AdminScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/schedules/{id}', [AdminScheduleController::class, 'show'])->name('schedules.show');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-pdf', [ReportController::class, 'exportPDF'])->name('reports.export.pdf');
    });

    /**
     * Faculty Module
     */
    Route::middleware(['role:faculty'])->prefix('faculty')->name('faculty.')->group(function () {
        Route::get('/dashboard', fn () => view('faculty.dashboard'))->name('dashboard');

        Route::get('/schedules', [FacultyScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/schedules/{schedule}', [FacultyScheduleController::class, 'show'])->name('schedules.show');

        Route::resource('availabilities', AvailabilityController::class);

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

        // ✅ Updated respond route to match the new controller method
        Route::post('/notifications/{notification}/respond', [NotificationController::class, 'respond'])
            ->name('notifications.respond');
    });

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

require __DIR__.'/auth.php';
