<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    // GET /api/indikator/{id}/kategori?tahun=2024
    public function byIndikator(Request $request, $id)
    {
        $tahun = $request->query('tahun');
        $query = Kategori::where('indikator_id', $id);

        if ($tahun) $query->where('tahun', $tahun);

        $data = $query->get()->map(function ($kategori) {
            return [
                'id' => $kategori->id,
                'nama_kategori' => $kategori->nama_kategori,
                'deskripsi' => $kategori->deskripsi,
                'tahun' => $kategori->tahun,
                'gambar' => $kategori->gambar ? asset('storage/' . $kategori->gambar) : null,
                'indikator_id' => $kategori->indikator_id,
            ];
        });

        return response()->json([
            'success' => true,
            'indikator_id' => $id,
            'tahun' => $tahun,
            'data' => $data,
        ]);
    }

    // GET /api/kategori
    public function index()
    {
        $data = Kategori::all()->map(function ($kategori) {
            return [
                'id' => $kategori->id,
                'nama_kategori' => $kategori->nama_kategori,
                'deskripsi' => $kategori->deskripsi,
                'tahun' => $kategori->tahun,
                'gambar' => $kategori->gambar ? asset('storage/' . $kategori->gambar) : null,
                'indikator_id' => $kategori->indikator_id,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    // POST /api/kategori
    public function store(Request $request)
    {
        $request->validate([
            'indikator_id' => 'required|exists:indikators,id',
            'nama_kategori' => 'required|string',
            'deskripsi' => 'nullable|string',
            'tahun' => 'required|integer',
            'gambar' => 'nullable|image|max:2048',
        ]);

        $path = $request->hasFile('gambar') ? $request->file('gambar')->store('kategori', 'public') : null;

        $kategori = Kategori::create([
            'indikator_id' => $request->indikator_id,
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
            'tahun' => $request->tahun,
            'gambar' => $path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $kategori,
        ], 201);
    }

    // PUT /api/kategori/{id}
    public function update(Request $request, $id)
    {
        $kategori = Kategori::find($id);
        if (!$kategori) return response()->json(['success' => false, 'message' => 'Kategori tidak ditemukan'], 404);

        $request->validate([
            'nama_kategori' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'tahun' => 'nullable|integer',
            'gambar' => 'nullable|image|max:2048',
        ]);

        $kategori->fill($request->only(['nama_kategori','deskripsi','tahun']));

        if ($request->hasFile('gambar')) {
            if ($kategori->gambar && file_exists(public_path('storage/' . $kategori->gambar))) {
                @unlink(public_path('storage/' . $kategori->gambar));
            }
            $kategori->gambar = $request->file('gambar')->store('kategori', 'public');
        }

        $kategori->save();

        return response()->json(['success' => true, 'data' => $kategori]);
    }
}
