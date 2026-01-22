<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Indikator;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->query('tahun');

        $indikator = Indikator::whereHas('dataIndikators', function ($q) use ($tahun) {
                if ($tahun) {
                    $q->where('tahun', $tahun);
                }
            })
            ->with(['dataIndikators' => function ($q) use ($tahun) {
                if ($tahun) {
                    $q->where('tahun', $tahun);
                }
            }])
            ->get();

        return response()->json([
            'success' => true,
            'tahun' => $tahun,
            'data' => $indikator
        ]);
    }
}

