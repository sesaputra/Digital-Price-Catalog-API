<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with([
            'category',
            'variants',
        ])
        ->where('tenant_id', $request->user()->tenant_id)
        ->latest()
        ->get();

        return response()->json([
            'data' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => [
                'required',
                'integer',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'sku' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'variants' => [
                'required',
                'array',
                'min:1',
            ],

            'variants.*.name' => [
                'required',
                'string',
                'max:255',
            ],

            'variants.*.unit' => [
                'required',
                'string',
                'max:50',
            ],

            'variants.*.price' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        $tenantId = $request->user()->tenant_id;

        /*
        |--------------------------------------------------------------------------
        | Pastikan kategori milik tenant yang sedang login
        |--------------------------------------------------------------------------
        */

        $categoryExists = \App\Models\Category::where('id', $validated['category_id'])
            ->where('tenant_id', $tenantId)
            ->exists();

        if (!$categoryExists) {
            throw ValidationException::withMessages([
                'category_id' => [
                    'Kategori tidak ditemukan.',
                ],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Buat Product + Variant dalam satu transaksi
        |--------------------------------------------------------------------------
        */

        $product = DB::transaction(function () use ($validated, $tenantId) {

            $product = Product::create([
                'tenant_id' => $tenantId,
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'sku' => $validated['sku'] ?? null,
                'description' => $validated['description'] ?? null,
            ]);

            foreach ($validated['variants'] as $variant) {
                $product->variants()->create([
                    'name' => $variant['name'],
                    'unit' => $variant['unit'],
                    'price' => $variant['price'],
                ]);
            }

            return $product;
        });

        $product->load([
            'category',
            'variants',
        ]);

        return response()->json([
            'message' => 'Produk berhasil dibuat.',
            'data' => $product,
        ], 201);
    }

    public function show(Request $request, Product $product)
    {
        /*
        |--------------------------------------------------------------------------
        | Pastikan product milik tenant
        |--------------------------------------------------------------------------
        */

        if ($product->tenant_id !== $request->user()->tenant_id) {
            return response()->json([
                'message' => 'Produk tidak ditemukan.',
            ], 404);
        }

        $product->load([
            'category',
            'variants',
        ]);

        return response()->json([
            'data' => $product,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        /*
        |--------------------------------------------------------------------------
        | Pastikan product milik tenant
        |--------------------------------------------------------------------------
        */

        if ($product->tenant_id !== $request->user()->tenant_id) {
            return response()->json([
                'message' => 'Produk tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'category_id' => [
                'required',
                'integer',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'sku' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'variants' => [
                'required',
                'array',
                'min:1',
            ],

            'variants.*.name' => [
                'required',
                'string',
                'max:255',
            ],

            'variants.*.unit' => [
                'required',
                'string',
                'max:50',
            ],

            'variants.*.price' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        $tenantId = $request->user()->tenant_id;

        /*
        |--------------------------------------------------------------------------
        | Validasi kategori
        |--------------------------------------------------------------------------
        */

        $categoryExists = \App\Models\Category::where('id', $validated['category_id'])
            ->where('tenant_id', $tenantId)
            ->exists();

        if (!$categoryExists) {
            throw ValidationException::withMessages([
                'category_id' => [
                    'Kategori tidak ditemukan.',
                ],
            ]);
        }

        DB::transaction(function () use ($product, $validated) {

            $product->update([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'sku' => $validated['sku'] ?? null,
                'description' => $validated['description'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Untuk MVP:
            | hapus variant lama lalu buat ulang variant baru
            |--------------------------------------------------------------------------
            */

            $product->variants()->delete();

            foreach ($validated['variants'] as $variant) {
                $product->variants()->create([
                    'name' => $variant['name'],
                    'unit' => $variant['unit'],
                    'price' => $variant['price'],
                ]);
            }
        });

        $product->load([
            'category',
            'variants',
        ]);

        return response()->json([
            'message' => 'Produk berhasil diperbarui.',
            'data' => $product,
        ]);
    }

    public function destroy(Request $request, Product $product)
    {
        /*
        |--------------------------------------------------------------------------
        | Pastikan product milik tenant
        |--------------------------------------------------------------------------
        */

        if ($product->tenant_id !== $request->user()->tenant_id) {
            return response()->json([
                'message' => 'Produk tidak ditemukan.',
            ], 404);
        }

        $product->delete();

        return response()->json([
            'message' => 'Produk berhasil dihapus.',
        ]);
    }
}