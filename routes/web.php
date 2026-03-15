<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BudgetPdfController;
use App\Http\Controllers\PublicBudgetController;
use App\Http\Controllers\ClientAreaController;

Route::get('/', function () {
    return view('welcome');
});

// Ruta para descargar PDF de presupuesto
Route::get('/presupuestos/{budget}/pdf', [BudgetPdfController::class, 'generate'])
    ->name('budgets.pdf')
    ->middleware('auth');

   

Route::get('/presupuesto/{token}', [PublicBudgetController::class, 'show'])
    ->name('public.budget.show');

Route::post('/presupuesto/{token}/accept', [PublicBudgetController::class, 'accept'])
    ->name('public.budget.accept');

Route::post('/presupuesto/{token}/reject', [PublicBudgetController::class, 'reject'])
    ->name('public.budget.reject');

Route::get('/presupuesto/{token}/pdf', [PublicBudgetController::class, 'downloadPdf'])
    ->name('public.budget.pdf');   

// === Área Privada de Clientes ===
Route::middleware(['guest'])->group(function () {
    Route::get('/client-login', [ClientAreaController::class, 'showLoginForm'])->name('client.login');
    Route::post('/client-login', [ClientAreaController::class, 'login']);
    Route::get('/client-register', [ClientAreaController::class, 'showRegisterForm'])->name('client.register');
    Route::post('/client-register', [ClientAreaController::class, 'register']);
});

Route::middleware(['auth:client'])->prefix('mi-area')->name('client.')->group(function () {
    Route::get('/', [ClientAreaController::class, 'dashboard'])->name('dashboard');
    Route::get('/presupuesto/{id}', [ClientAreaController::class, 'viewBudget'])->name('budget.view');
    Route::post('/presupuesto/{id}/accept', [ClientAreaController::class, 'acceptBudget'])->name('budget.accept');
    Route::post('/presupuesto/{id}/reject', [ClientAreaController::class, 'rejectBudget'])->name('budget.reject');
    Route::get('/presupuesto/{id}/pdf', [ClientAreaController::class, 'downloadPdf'])->name('budget.pdf');
    Route::post('/logout', [ClientAreaController::class, 'logout'])->name('logout');
});
Route::get('/pwa', function () {
    return view('pwa');
})->name('pwa');

