<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Api\IndikatorController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\DataIndikatorController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AboutController;


// =====================
// Dashboard
// =====================
Route::get('/dashboard', [DashboardController::class, 'index']); // ?tahun=2024


// =====================
// Indikator
// =====================
Route::get('/indikators', [IndikatorController::class, 'index']);
Route::get('/indikators/{slug}', [IndikatorController::class, 'show']);
Route::post('/indikators', [IndikatorController::class, 'store']);
Route::post('/indikators/{id}/background', [IndikatorController::class, 'updateBackground']);


// =====================
// Kategori
// =====================
Route::get('/indikator/{id}/kategori', [KategoriController::class, 'byIndikator']);
Route::get('/kategori', [KategoriController::class, 'index']);
Route::post('/kategori', [KategoriController::class, 'store']);
Route::put('/kategori/{id}', [KategoriController::class, 'update']);


// =====================
// Data Indikator
// =====================
Route::get('/data-indikator/{indikatorId}', [DataIndikatorController::class, 'byIndikator']);


// =====================
// Admin
// =====================
Route::post('/admin/login', [AdminController::class, 'login']);


// =====================
// About
// =====================
Route::get('/abouts', [AboutController::class, 'index']);
Route::get('/abouts/{id}', [AboutController::class, 'show']);
Route::put('/abouts/{id}', [AboutController::class, 'update']);


// ===================================================
// 🔥 ROUTE KHUSUS UNTUK GAMBAR (ANTI CORS ERROR)
// ===================================================
Route::get('/image/{folder}/{filename}', function ($folder, $filename) {

    $path = $folder . '/' . $filename;

    if (!Storage::disk('public')->exists($path)) {
        return response()->json(['message' => 'File not found'], 404);
    }

    $file = Storage::disk('public')->get($path);
    $type = Storage::disk('public')->mimeType($path);

    return response($file, 200)
        ->header("Content-Type", $type)
        ->header("Access-Control-Allow-Origin", "*");
});