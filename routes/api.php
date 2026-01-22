<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IndikatorController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\DataIndikatorController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AboutController;


// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index']); // ?tahun=2024

// Indikator
Route::get('/indikators', [IndikatorController::class, 'index']);
Route::get('/indikators/{slug}', [IndikatorController::class, 'show']);
Route::post('/indikators', [IndikatorController::class, 'store']);

// Kategori
Route::get('/indikator/{id}/kategori', [KategoriController::class, 'byIndikator']); // filter indikator
Route::get('/kategori', [KategoriController::class, 'index']); // ✅ ambil semua kategori
Route::post('/kategori', [KategoriController::class, 'store']);
Route::put('/kategori/{id}', [KategoriController::class, 'update']);

// Data Indikator
Route::get('/data-indikator/{indikatorId}', [DataIndikatorController::class, 'byIndikator']); // ?tahun=2024

// Admin
Route::post('/admin/login', [AdminController::class, 'login']);

// About
Route::get('/abouts', [AboutController::class, 'index']);
Route::get('/abouts/{id}', [AboutController::class, 'show']);
