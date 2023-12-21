<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;

Auth::routes(['register' => false]);

Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');

    Route::prefix('company')->group(function ($router) {
        Route::get('/', [CompanyController::class, 'index'])->name('company.view');
        Route::post('/list', [CompanyController::class, 'list'])->name('company.list');
        Route::post('/delete', [CompanyController::class, 'delete'])->name('company.delete');
        Route::get('/create', [CompanyController::class, 'create'])->name('company.create');
        Route::post('/store', [CompanyController::class, 'store'])->name('company.store');
        Route::get('/edit/{id}', [CompanyController::class, 'edit'])->name('company.edit');
        Route::post('/update', [CompanyController::class, 'update'])->name('company.update');
    });

    Route::prefix('employee')->group(function ($router) {
        Route::get('/', [EmployeeController::class, 'index'])->name('employee.view');
        Route::post('/list', [EmployeeController::class, 'list'])->name('employee.list');
        Route::post('/delete', [EmployeeController::class, 'delete'])->name('employee.delete');
        Route::get('/create', [EmployeeController::class, 'create'])->name('employee.create');
        Route::post('/store', [EmployeeController::class, 'store'])->name('employee.store');
        Route::get('/edit/{id}', [EmployeeController::class, 'edit'])->name('employee.edit');
        Route::post('/update', [EmployeeController::class, 'update'])->name('employee.update');
    });
});
