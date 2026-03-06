<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Labs\LabIndex;
use App\Livewire\Admin\Labs\LabCreate;
use App\Livewire\Admin\Labs\LabEdit;
use App\Livewire\Lab\Dashboard as LabDashboard;
use App\Livewire\Lab\Patients\PatientIndex;
use App\Livewire\Lab\Patients\PatientCreate;
use App\Livewire\Lab\Patients\PatientEdit;
use App\Livewire\Lab\Tests\TestCategoryIndex;
use App\Livewire\Lab\Tests\TestIndex;
use App\Livewire\Lab\Orders\OrderIndex;
use App\Livewire\Lab\Orders\OrderCreate;
use App\Livewire\Lab\Orders\OrderShow;
use App\Livewire\Lab\Results\ResultIndex;
use App\Livewire\Lab\Invoices\InvoiceIndex;
use App\Livewire\Lab\Settings\LabSettings;
use App\Livewire\Lab\Users\UserIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Fallback dashboard redirect (used by Breeze internals)
Route::get('/dashboard', function () {
    if (auth()->user()?->hasRole('superadmin')) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('lab.dashboard');
})->middleware('auth')->name('dashboard');

// ─── Super Admin Routes ───────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/labs', LabIndex::class)->name('labs.index');
    Route::get('/labs/create', LabCreate::class)->name('labs.create');
    Route::get('/labs/{lab}/edit', LabEdit::class)->name('labs.edit');
});

// ─── Lab Routes ───────────────────────────────────────────────────────────────
Route::prefix('lab')->name('lab.')->middleware(['auth', 'role:lab_admin|lab_incharge|receptionist|technician'])->group(function () {
    Route::get('/dashboard', LabDashboard::class)->name('dashboard');

    // Patients
    Route::get('/patients', PatientIndex::class)->name('patients.index');
    Route::get('/patients/create', PatientCreate::class)->name('patients.create');
    Route::get('/patients/{patient}/edit', PatientEdit::class)->name('patients.edit');

    // Test Catalog
    Route::get('/test-categories', TestCategoryIndex::class)->name('test-categories.index');
    Route::get('/tests', TestIndex::class)->name('tests.index');

    // Orders
    Route::get('/orders', OrderIndex::class)->name('orders.index');
    Route::get('/orders/create', OrderCreate::class)->name('orders.create');
    Route::get('/orders/{order}', OrderShow::class)->name('orders.show');

    // Results
    Route::get('/results', ResultIndex::class)->name('results.index');

    // Reports
    Route::get('/orders/{order}/report', [ReportController::class, 'orderReport'])->name('orders.report');

    // Invoices
    Route::get('/invoices', InvoiceIndex::class)->name('invoices.index');

    // Settings & Users (lab_admin only)
    Route::get('/settings', LabSettings::class)->name('settings')->middleware('role:lab_admin');
    Route::get('/users', UserIndex::class)->name('users.index')->middleware('role:lab_admin');
});

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
