<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataIndikator;
use Illuminate\Http\Request;

class DataIndikatorController extends Controller
{
    // GET /api/data-indikator/{indikatorId}?tahun=2024
    public function byIndikator(Request $request, $indikatorId)
    {
        $tahun = $request->query('tahun');

        $data = DataIndikator::with('kategori')
            ->where('indikator_id', $indikatorId)
            ->when($tahun, function($q) use ($tahun) {
                $q->where('tahun', $tahun);
            })
            ->get();

        return response()->json([
            'success' => true,
            'indikator_id' => $indikatorId,
            'tahun' => $tahun,
            'data' => $data
        ]);
    }
}
