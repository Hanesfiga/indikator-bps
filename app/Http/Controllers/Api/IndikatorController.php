<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Indikator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IndikatorController extends Controller
{
    /**
     * LIST INDIKATOR
     * GET /api/indikators
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Indikator::all()
        ]);
    }

    /**
     * DETAIL INDIKATOR BERDASARKAN SLUG + FILTER TAHUN
     * GET /api/indikators/{slug}?tahun=2024
     */
    public function show(Request $request, $slug)
    {
        $tahun = $request->query('tahun');

        $indikator = Indikator::where('slug', $slug)
            ->with(['kategoris' => function ($q) use ($tahun) {
                if ($tahun) {
                    $q->where('tahun', $tahun);
                }
            }])
            ->first();

        if (!$indikator) {
            return response()->json([
                'success' => false,
                'message' => 'Indikator tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $indikator->id,
                'nama_indikator' => $indikator->nama_indikator,
                'slug' => $indikator->slug,
                'deskripsi' => $indikator->deskripsi,
                'kategoris' => $indikator->kategoris->map(function ($kategori) {
                    return [
                        'id' => $kategori->id,
                        'nama_kategori' => $kategori->nama_kategori,
                        'deskripsi' => $kategori->deskripsi,
                        'tahun' => $kategori->tahun,
                        'gambar' => $kategori->gambar,
                    ];
                })->values(),
            ]
        ]);
    }

    /**
     * TAMBAH INDIKATOR
     * POST /api/indikators
     */
    public function store(Request $request)
    {
        // ✅ VALIDASI (slug tidak diminta dari frontend)
        $request->validate([
            'nama_indikator' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        // ✅ GENERATE SLUG OTOMATIS & AMAN
        $slug = Str::slug($request->nama_indikator);
        $count = Indikator::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug = "{$slug}-{$count}";
        }

        $indikator = Indikator::create([
            'nama_indikator' => $request->nama_indikator,
            'slug' => $slug,
            'deskripsi' => $request->deskripsi,
            'tahun' => 0,
        ]);

        return response()->json([
            'success' => true,
            'data' => $indikator
        ], 201);
    }
}
