<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Menampilkan semua kategori tenant yang sedang login.
     */
    public function index(Request $request)
    {
        $categories = Category::where(
            'tenant_id',
            $request->user()->tenant_id
        )
        ->latest()
        ->get();

        return response()->json([
            'data' => $categories,
        ]);
    }

    /**
     * Membuat kategori baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $category = Category::create([
            'tenant_id' => $request->user()->tenant_id,
            'name' => $validated['name'],
        ]);

        return response()->json([
            'message' => 'Kategori berhasil dibuat.',
            'data' => $category,
        ], 201);
    }

    /**
     * Menampilkan satu kategori.
     */
    public function show(Request $request, Category $category)
    {
        if ($category->tenant_id !== $request->user()->tenant_id) {
            return response()->json([
                'message' => 'Kategori tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'data' => $category,
        ]);
    }

    /**
     * Mengubah kategori.
     */
    public function update(Request $request, Category $category)
    {
        if ($category->tenant_id !== $request->user()->tenant_id) {
            return response()->json([
                'message' => 'Kategori tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $category->update([
            'name' => $validated['name'],
        ]);

        return response()->json([
            'message' => 'Kategori berhasil diperbarui.',
            'data' => $category,
        ]);
    }

    /**
     * Menghapus kategori.
     */
    public function destroy(Request $request, Category $category)
    {
        if ($category->tenant_id !== $request->user()->tenant_id) {
            return response()->json([
                'message' => 'Kategori tidak ditemukan.',
            ], 404);
        }

        $category->delete();

        return response()->json([
            'message' => 'Kategori berhasil dihapus.',
        ]);
    }
}