<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    public function index(): JsonResponse
    {
        $abouts = About::orderBy('id', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $abouts
        ]);
    }

    public function show($id): JsonResponse
    {
        $about = About::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $about
        ]);
    }

    public function update(Request $request, $id)
{
    $about = About::findOrFail($id);

    $request->validate([
        'title'   => 'required|string',
        'content' => 'required|string',
        'image'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $imagePath = $about->image; // pakai gambar lama

    if ($request->hasFile('image')) {

        if ($about->image && Storage::disk('public')->exists($about->image)) {
            Storage::disk('public')->delete($about->image);
        }

        $imagePath = $request->file('image')->store('about', 'public');
    }

    $about->update([
        'title'   => $request->title,
        'content' => $request->content,
        'image'   => $imagePath,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'About berhasil diperbarui',
        'data' => $about
    ]);
}


}
