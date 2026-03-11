<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SectionTypeController;
use App\Http\Controllers\ChapterController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    if (!auth()->user()->current_project_id) {
        return redirect()->route('projects.index');
    }

    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::post('/projects/{project}/select', [ProjectController::class, 'select'])->name('projects.select');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/sections', [SectionController::class, 'index'])->name('sections.index');
    Route::post('/sections', [SectionController::class, 'store'])->name('sections.store');
    Route::patch('/sections/{section}', [SectionController::class, 'update'])->name('sections.update');
    Route::post('/sections/reorder', [SectionController::class, 'reorder'])->name('sections.reorder');
    Route::delete('/sections/{section}', [SectionController::class, 'destroy'])->name('sections.destroy');

    Route::get('/section-types', [SectionTypeController::class, 'index'])->name('section-types.index');
    Route::post('/section-types', [SectionTypeController::class, 'store'])->name('section-types.store');
    Route::patch('/section-types/{sectionType}', [SectionTypeController::class, 'update'])->name('section-types.update');
    Route::delete('/section-types/{sectionType}', [SectionTypeController::class, 'destroy'])->name('section-types.destroy');

    Route::get('/chapters', [ChapterController::class, 'index'])->name('chapters.index');
    Route::post('/chapters', [ChapterController::class, 'store'])->name('chapters.store');
    Route::patch('/chapters/reorder', [ChapterController::class, 'reorder'])->name('chapters.reorder');
    Route::put('/chapters/{chapter}', [ChapterController::class, 'update'])->name('chapters.update');
    Route::delete('/chapters/{chapter}', [ChapterController::class, 'destroy'])->name('chapters.destroy');

});

// Everything else requires a selected project
Route::middleware(['auth', 'project.selected'])->group(function () {
    //Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/dashboard', fn () => redirect()->route('sections.index'))->name('dashboard');
});

require __DIR__.'/auth.php';
