<?php

use Illuminate\Support\Facades\Route;
use App\Models\Task;

// Controllers Umum
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BusinessProfileController;
use App\Http\Controllers\ProfitStudioController;
use App\Http\Controllers\CustomerCenterController;
use App\Http\Controllers\ContentStudioController;
use App\Http\Controllers\AIBossController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AiWorkspaceController;
use App\Http\Controllers\LiveScriptController;
use App\Http\Controllers\BusinessPlaybookController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\WorkflowController;

// Controller & Middleware Admin
use App\Http\Controllers\AdminController;
use App\Http\Middleware\IsAdmin;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| 👑 RUTE MASTER ADMIN (Wajib PIN & IsAdmin)
|--------------------------------------------------------------------------
*/
// Form Login Admin (Bebas diakses publik)
Route::get('/master-admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login.form');
Route::post('/master-admin/login', [AdminController::class, 'login'])->name('admin.login.submit');

// Rute yang butuh Login Admin
Route::middleware(['auth'])->group(function () {

    // Form Input PIN Master
    Route::get('/master-admin/pin-lock', [AdminController::class, 'showPinForm'])->name('admin.pin.form');
    Route::post('/master-admin/pin-lock', [AdminController::class, 'verifyPin'])->name('admin.pin.submit');

    // Panel Admin (Dikawal ketat oleh Middleware IsAdmin)
    Route::middleware([IsAdmin::class])->group(function () {
        Route::get('/master-admin', [AdminController::class, 'index'])->name('admin.users');
        Route::post('/master-admin/reset-password/{id}', [AdminController::class, 'resetPassword'])->name('admin.reset-password');
        Route::post('/master-admin/reset-pin/{id}', [AdminController::class, 'resetPin'])->name('admin.reset-pin');
        Route::put('/master-admin/update-business/{id}', [AdminController::class, 'updateBusiness'])->name('admin.update-business');
        Route::delete('/master-admin/delete-user/{id}', [AdminController::class, 'destroyUser'])->name('admin.delete-user');
    });
});


/*
|--------------------------------------------------------------------------
| 👥 RUTE MEMBER / PELANGGAN (JALUR NORMAL BEBAS PIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // --- DASHBOARD ---
    Route::get('/dashboard', function () {
        if (!auth()->user()->businessProfile) {
            return redirect()->route('business.create');
        }

        $tasks = Task::where('user_id', auth()->id())->where('is_completed', false)->latest()->paginate(5, ['*'], 'tasks_page');
        $completedTasks = Task::where('user_id', auth()->id())->where('is_completed', true)->whereDate('updated_at', \Carbon\Carbon::today())->latest('updated_at')->paginate(5, ['*'], 'history_page');

        return view('dashboard', compact('tasks', 'completedTasks'));
    })->name('dashboard');

    Route::patch('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');

    // --- PENGATURAN PROFIL ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/business-profile/create', [BusinessProfileController::class, 'create'])->name('business.create');
    Route::post('/business-profile/store', [BusinessProfileController::class, 'store'])->name('business.store');

    // --- MODUL AI BOSS ---
    Route::get('/ai-boss', [AIBossController::class, 'index'])->name('aiboss.index');
    Route::post('/ai-boss/generate', [AIBossController::class, 'generateActionPlan'])->name('aiboss.generate');

    Route::get('/content-studio', [ContentStudioController::class, 'index'])->name('content-studio');
    Route::post('/content-studio/generate', [ContentStudioController::class, 'generate'])->name('content-studio.generate');
    Route::post('/content-studio/store', [ContentStudioController::class, 'store'])->name('content-studio.store');
    Route::delete('/content-studio/destroy/{id}', [ContentStudioController::class, 'destroy'])->name('content-studio.destroy');

    Route::get('/live-script', [LiveScriptController::class, 'index'])->name('live-script');
    Route::post('/live-script/generate', [LiveScriptController::class, 'generate'])->name('live-script.generate');
    Route::post('/live-script/store', [LiveScriptController::class, 'store'])->name('live-script.store');
    Route::delete('/live-script/destroy/{id}', [LiveScriptController::class, 'destroy'])->name('live-script.destroy');

    Route::get('/customer-center', [CustomerCenterController::class, 'index'])->name('customer-center');
    Route::post('/customer-center/generate', [CustomerCenterController::class, 'generate'])->name('customer-center.generate');
    Route::post('/customer-center/store', [CustomerCenterController::class, 'store'])->name('customer-center.store');
    Route::delete('/customer-center/destroy/{id}', [CustomerCenterController::class, 'destroy'])->name('customer-center.destroy');

    Route::get('/profit-studio', [ProfitStudioController::class, 'index'])->name('profit-studio');
    Route::post('/profit-studio/generate', [ProfitStudioController::class, 'generate'])->name('profit-studio.generate');
    Route::post('/profit-studio/store', [ProfitStudioController::class, 'store'])->name('profit-studio.store');
    Route::delete('/profit-studio/destroy/{id}', [ProfitStudioController::class, 'destroy'])->name('profit-studio.destroy');

    Route::get('/playbook', [BusinessPlaybookController::class, 'index'])->name('playbook');
    Route::post('/playbook/generate', [BusinessPlaybookController::class, 'generate'])->name('playbook.generate');
    Route::post('/playbook/store', [BusinessPlaybookController::class, 'store'])->name('playbook.store');
    Route::delete('/playbook/destroy/{id}', [BusinessPlaybookController::class, 'destroy'])->name('playbook.destroy');

    Route::get('/workflow', [WorkflowController::class, 'index'])->name('workflow');
    Route::post('/workflow/generate', [WorkflowController::class, 'generate'])->name('workflow.generate');
    Route::post('/workflow/store', [WorkflowController::class, 'store'])->name('workflow.store');
    Route::delete('/workflow/destroy/{id}', [WorkflowController::class, 'destroy'])->name('workflow.destroy');

    Route::get('/ai-workspace', [AiWorkspaceController::class, 'index'])->name('ai-workspace');
    Route::post('/ai-workspace/chat', [AiWorkspaceController::class, 'chat'])->name('ai-workspace.chat');

    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
});

// Wajib diletakkan paling bawah (Rute default Laravel Breeze untuk login member)
require __DIR__.'/auth.php';
