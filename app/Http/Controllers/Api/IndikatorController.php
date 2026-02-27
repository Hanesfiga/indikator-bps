<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Indikator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class IndikatorController extends Controller
{
    /**
     * LIST INDIKATOR
     * GET /api/indikators
     */
    public function index()
    {
        $indikators = Indikator::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_indikator' => $item->nama_indikator,
                'slug' => $item->slug,
                'deskripsi' => $item->deskripsi,
                'background_image' => $item->background_image 
                    ? asset('storage/' . $item->background_image)
                    : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $indikators
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
                'background_image' => $indikator->background_image
                    ? asset('storage/' . $indikator->background_image)
                    : null,
                'kategoris' => $indikator->kategoris->map(function ($kategori) {
                    return [
                        'id' => $kategori->id,
                        'nama_kategori' => $kategori->nama_kategori,
                        'deskripsi' => $kategori->deskripsi,
                        'tahun' => $kategori->tahun,
                        'gambar' => $kategori->gambar
                            ? asset('storage/' . $kategori->gambar)
                            : null,
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
        $request->validate([
            'nama_indikator' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'background_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $slug = Str::slug($request->nama_indikator);
        $count = Indikator::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug = "{$slug}-{$count}";
        }

        $backgroundPath = null;

        if ($request->hasFile('background_image')) {
            $backgroundPath = $request->file('background_image')
                ->store('indikator_background', 'public');
        }

        $indikator = Indikator::create([
            'nama_indikator' => $request->nama_indikator,
            'slug' => $slug,
            'deskripsi' => $request->deskripsi,
            'background_image' => $backgroundPath,
        ]);

        return response()->json([
            'success' => true,
            'data' => $indikator
        ], 201);
    }

    /**
     * UPDATE BACKGROUND SAJA
     * POST /api/indikators/{id}/background
     */
    public function updateBackground(Request $request, $id)
    {
        $request->validate([
            'background_image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $indikator = Indikator::findOrFail($id);

        // Hapus gambar lama jika ada
        if ($indikator->background_image &&
            Storage::disk('public')->exists($indikator->background_image)) {
            Storage::disk('public')->delete($indikator->background_image);
        }

        $path = $request->file('background_image')
            ->store('indikator_background', 'public');

        $indikator->background_image = $path;
        $indikator->save();

        return response()->json([
            'success' => true,
            'message' => 'Background berhasil diupdate',
            'data' => [
                'id' => $indikator->id,
                'background_image' => asset('storage/' . $path)
            ]
        ]);
    }
}