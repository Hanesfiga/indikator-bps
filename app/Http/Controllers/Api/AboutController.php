<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\JsonResponse;

class AboutController extends Controller
{
    /**
     * Menampilkan seluruh data About
     * Digunakan untuk halaman About (accordion)
     */
    public function index(): JsonResponse
    {
        $abouts = About::orderBy('id', 'asc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data about berhasil diambil',
            'data' => $abouts
        ], 200);
    }
}
