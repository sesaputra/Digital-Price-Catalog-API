<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Http\Request;

class PublicCatalogController extends Controller
{
    /**
     * Menampilkan daftar produk publik dari sebuah toko.
     */
    public function products(
        Request $request,
        Tenant $tenant
    ) {
        // Pastikan toko aktif
        if (!$tenant->is_active) {
            return response()->json([
                'message' => 'Toko tidak tersedia.',
            ], 404);
        }

        $query = Product::with([
            'category',
            'productType',
            'brand',
            'variants',
        ])
        ->where('tenant_id', $tenant->id)
        ->where('is_public', true);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'sku',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas(
                    'brand',
                    function ($brandQuery) use ($search) {
                        $brandQuery->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );
                    }
                )

                ->orWhereHas(
                    'productType',
                    function ($typeQuery) use ($search) {
                        $typeQuery->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );
                    }
                )

                ->orWhereHas(
                    'variants',
                    function ($variantQuery) use ($search) {
                        $variantQuery->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );
                    }
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Category
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category_id')) {
            $query->where(
                'category_id',
                $request->category_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Brand
        |--------------------------------------------------------------------------
        */

        if ($request->filled('brand_id')) {
            $query->where(
                'brand_id',
                $request->brand_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $perPage = min(
            (int) $request->input('per_page', 12),
            50
        );

        $products = $query
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return ProductResource::collection($products);
    }

    /**
     * Menampilkan detail satu produk publik.
     */
    public function product(
        Tenant $tenant,
        Product $product
    ) {
        // Pastikan toko aktif
        if (!$tenant->is_active) {
            return response()->json([
                'message' => 'Toko tidak tersedia.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi Tenant
        |--------------------------------------------------------------------------
        |
        | Produk harus benar-benar milik toko yang sedang dibuka.
        |
        */

        if (
            $product->tenant_id !== $tenant->id ||
            !$product->is_public
        ) {
            return response()->json([
                'message' => 'Produk tidak ditemukan.',
            ], 404);
        }

        $product->load([
            'category',
            'productType',
            'brand',
            'variants',
        ]);

        return new ProductResource($product);
    }
}