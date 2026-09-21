<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductTypeResource;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of product types
     * for the current tenant.
     */
    public function index(Request $request)
    {
        $tenantId = $request->user()->tenant_id;

        $query = ProductType::with('category')
            ->where('tenant_id', $tenantId);

        if ($request->filled('category_id')) {
            $query->where(
                'category_id',
                $request->category_id
            );
        }

        $productTypes = $query
            ->orderBy('name')
            ->get();

        return ProductTypeResource::collection(
            $productTypes
        );
    }

    /**
     * Store a newly created product type.
     */
    public function store(Request $request)
    {
        $tenantId = $request->user()->tenant_id;

        $validated = $request->validate([
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')
                    ->where(
                        fn($query) =>
                        $query->where(
                            'tenant_id',
                            $tenantId
                        )
                    ),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $productType = ProductType::create([
            'tenant_id' => $tenantId,
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
        ]);

        $productType->load('category');

        return (new ProductTypeResource($productType))
            ->additional([
                'message' => 'Jenis produk berhasil dibuat.',
            ])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified product type.
     */
    public function show(
        Request $request,
        ProductType $productType
    ) {
        if (
            $productType->tenant_id !==
            $request->user()->tenant_id
        ) {
            return response()->json([
                'message' => 'Jenis produk tidak ditemukan.',
            ], 404);
        }

        $productType->load('category');

        return new ProductTypeResource($productType);
    }

    /**
     * Update the specified product type.
     */
    public function update(
        Request $request,
        ProductType $productType
    ) {
        $tenantId = $request->user()->tenant_id;

        if (
            $productType->tenant_id !==
            $tenantId
        ) {
            return response()->json([
                'message' => 'Jenis produk tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')
                    ->where(
                        fn($query) =>
                        $query->where(
                            'tenant_id',
                            $tenantId
                        )
                    ),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $productType->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
        ]);

        $productType->load('category');

        return (new ProductTypeResource($productType))
            ->additional([
                'message' => 'Jenis produk berhasil diperbarui.',
            ]);
    }

    /**
     * Remove the specified product type.
     */
    public function destroy(
        Request $request,
        ProductType $productType
    ) {
        if (
            $productType->tenant_id !==
            $request->user()->tenant_id
        ) {
            return response()->json([
                'message' => 'Jenis produk tidak ditemukan.',
            ], 404);
        }

        $productType->delete();

        return response()->json([
            'message' => 'Jenis produk berhasil dihapus.',
        ]);
    }
}
