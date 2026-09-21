<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of brands for the current tenant.
     */
    public function index(Request $request)
    {
        $brands = Brand::where(
            'tenant_id',
            $request->user()->tenant_id
        )
            ->orderBy('name')
            ->get();

        return BrandResource::collection($brands);
    }

    /**
     * Store a newly created brand.
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

        $brand = Brand::create([
            'tenant_id' => $request->user()->tenant_id,
            'name' => $validated['name'],
        ]);

        return (new BrandResource($brand))
            ->additional([
                'message' => 'Brand berhasil dibuat.',
            ])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified brand.
     */
    public function show(Request $request, Brand $brand)
    {
        if (
            $brand->tenant_id !==
            $request->user()->tenant_id
        ) {
            return response()->json([
                'message' => 'Brand tidak ditemukan.',
            ], 404);
        }

        return new BrandResource($brand);
    }

    /**
     * Update the specified brand.
     */
    public function update(
        Request $request,
        Brand $brand
    ) {
        if (
            $brand->tenant_id !==
            $request->user()->tenant_id
        ) {
            return response()->json([
                'message' => 'Brand tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $brand->update([
            'name' => $validated['name'],
        ]);

        return (new BrandResource($brand))
            ->additional([
                'message' => 'Brand berhasil diperbarui.',
            ]);
    }

    /**
     * Remove the specified brand.
     */
    public function destroy(
        Request $request,
        Brand $brand
    ) {
        if (
            $brand->tenant_id !==
            $request->user()->tenant_id
        ) {
            return response()->json([
                'message' => 'Brand tidak ditemukan.',
            ], 404);
        }

        $brand->delete();

        return response()->json([
            'message' => 'Brand berhasil dihapus.',
        ]);
    }
}
