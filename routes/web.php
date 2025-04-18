<?php

use App\Http\Controllers\CohortController;
use App\Http\Controllers\CommonLifeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RetroController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\KnowledgeController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

// Redirect the root path to /dashboard
Route::redirect('/', 'dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('verified')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Cohorts
        Route::get('/cohorts', [CohortController::class, 'index'])->name('cohort.index');
        Route::get('/cohort/{cohort}', [CohortController::class, 'show'])->name('cohort.show');

        // Teachers
        Route::get('/teachers', [TeacherController::class, 'index'])->name('teacher.index');

        // Students
        Route::get('students', [StudentController::class, 'index'])->name('student.index');

        // Knowledge
        Route::get('knowledge', [KnowledgeController::class, 'index'])->name('knowledge.index');
        Route::post('knowledge/store', [KnowledgeController::class, 'store'])->name('knowledge.store');
        Route::post('knowledge-Language/store', [KnowledgeController::class, 'languageStore'])->name('knowledge-language.store');
        Route::post('knowledge-user-answers/store', [KnowledgeController::class, 'userAnswersStore'])->name('usersAnswer.store');
        Route::get('/knowledge/questions', [KnowledgeController::class, 'getKnowledgeQuestions'])->name('knowledge.questions');



        // Groups
        Route::get('groups', [GroupController::class, 'index'])->name('group.index');

        // Retro
        route::get('retros', [RetroController::class, 'index'])->name('retro.index');

        // Common life
        Route::get('common-life', [CommonLifeController::class, 'index'])->name('common-life.index');
        Route::post('common-life/store', [CommonLifeController::class, 'store'])->name('commonLifeAdmin.store');
        Route::delete('/common-life-admin/{id}', [CommonLifeController::class, 'delete'])->name('commonLifeAdmin.delete');
        Route::put('/common-life/{id}', [CommonLifeController::class, 'update'])->name('commonLifeAdmin.update');
        Route::put('common-life/check/{id}', [CommonLifeController::class, 'check'])->name('commonLifeCheckStudent.check');
        Route::get('/task-modal', [CommonLifeController::class, 'getTaskModal'])->name('task.modal');

    });

});

require __DIR__.'/auth.php';
