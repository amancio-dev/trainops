<?php

use App\Http\Controllers\AnnualBudgetController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\ReferenceDataController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('budgets', [AnnualBudgetController::class, 'index'])->name('budgets.index');
    Route::get('references', [ReferenceDataController::class, 'index'])->name('references.index');
    Route::get('trainings', [TrainingController::class, 'index'])->name('trainings.index');
    Route::get('trainings/export', [TrainingController::class, 'export'])->name('trainings.export');

    Route::middleware(['can-manage', 'throttle:60,1'])->group(function () {
        Route::post('courses', [CourseController::class, 'store'])->name('courses.store');
        Route::put('courses/{course}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');

        Route::post('budgets', [AnnualBudgetController::class, 'store'])->name('budgets.store');
        Route::put('budgets/{budget}', [AnnualBudgetController::class, 'update'])->name('budgets.update');
        Route::delete('budgets/{budget}', [AnnualBudgetController::class, 'destroy'])->name('budgets.destroy');

        Route::post('trainings', [TrainingController::class, 'store'])->name('trainings.store');
        Route::put('trainings/{training}', [TrainingController::class, 'update'])->name('trainings.update');
        Route::delete('trainings/{training}', [TrainingController::class, 'destroy'])->name('trainings.destroy');

        Route::post('references/{kind}', [ReferenceDataController::class, 'store'])
            ->whereIn('kind', ['job-positions', 'training-types'])
            ->name('references.store');
        Route::put('references/{kind}/{id}', [ReferenceDataController::class, 'update'])
            ->whereIn('kind', ['job-positions', 'training-types'])
            ->name('references.update');
        Route::delete('references/{kind}/{id}', [ReferenceDataController::class, 'destroy'])
            ->whereIn('kind', ['job-positions', 'training-types'])
            ->name('references.destroy');
    });

    Route::middleware(['admin', 'throttle:30,1'])->group(function () {
        Route::post('employees', [EmployeeController::class, 'store'])->name('employees.store');
        Route::put('employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
        Route::get('audit', AuditLogController::class)->name('audit.index');
    });
});

require __DIR__.'/settings.php';
