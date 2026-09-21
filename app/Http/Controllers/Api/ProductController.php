<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $tenantId = $request->user()->tenant_id;

        $query = Product::with([
            'category',
            'productType',
            'brand',
            'variants',
        ])
            ->where('tenant_id', $tenantId);

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
    | Category Filter
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
    | Product Type Filter
    |--------------------------------------------------------------------------
    */

        if ($request->filled('product_type_id')) {
            $query->where(
                'product_type_id',
                $request->product_type_id
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Brand Filter
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
            (int) $request->input('per_page', 10),
            50
        );

        $products = $query
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created product.
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

            'product_type_id' => [
                'required',
                'integer',

                Rule::exists('product_types', 'id')
                    ->where(
                        fn($query) =>
                        $query->where(
                            'tenant_id',
                            $tenantId
                        )
                    ),
            ],

            'brand_id' => [
                'required',
                'integer',

                Rule::exists('brands', 'id')
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

            'sku' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'string',
                'max:255',
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
                'max:100',
            ],

            'variants.*.price' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Pastikan ProductType berada di Category yang dipilih
        |--------------------------------------------------------------------------
        */

        $productType = ProductType::where(
            'id',
            $validated['product_type_id']
        )
            ->where(
                'tenant_id',
                $tenantId
            )
            ->first();

        if (!$productType) {
            return response()->json([
                'message' => 'Jenis produk tidak ditemukan.',
            ], 404);
        }

        if (
            $productType->category_id !==
            (int) $validated['category_id']
        ) {
            return response()->json([
                'message' =>
                'Jenis produk tidak sesuai dengan kategori yang dipilih.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan Product + Variants
        |--------------------------------------------------------------------------
        */

        $product = DB::transaction(function () use (
            $validated,
            $tenantId
        ) {
            $product = Product::create([
                'tenant_id' => $tenantId,
                'category_id' => $validated['category_id'],
                'product_type_id' => $validated['product_type_id'],
                'brand_id' => $validated['brand_id'],
                'name' => $validated['name'],
                'sku' => $validated['sku'] ?? null,
                'description' => $validated['description'] ?? null,
                'image' => $validated['image'] ?? null,
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
            'productType',
            'brand',
            'variants',
        ]);

        return (new ProductResource($product))
            ->additional([
                'message' => 'Produk berhasil dibuat.',
            ])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified product.
     */
    public function show(Request $request, Product $product)
    {
        if ($product->tenant_id !== $request->user()->tenant_id) {
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
    /**
     * Update the specified product.
     */
    public function update(
        Request $request,
        Product $product
    ) {
        $tenantId = $request->user()->tenant_id;

        /*
        |--------------------------------------------------------------------------
        | Tenant Check
        |--------------------------------------------------------------------------
        */

        if ($product->tenant_id !== $tenantId) {
            return response()->json([
                'message' => 'Produk tidak ditemukan.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

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

            'product_type_id' => [
                'required',
                'integer',

                Rule::exists('product_types', 'id')
                    ->where(
                        fn($query) =>
                        $query->where(
                            'tenant_id',
                            $tenantId
                        )
                    ),
            ],

            'brand_id' => [
                'required',
                'integer',

                Rule::exists('brands', 'id')
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

            'sku' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'string',
                'max:255',
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
                'max:100',
            ],

            'variants.*.price' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Pastikan ProductType sesuai Category
        |--------------------------------------------------------------------------
        */

        $productType = ProductType::where(
            'id',
            $validated['product_type_id']
        )
            ->where(
                'tenant_id',
                $tenantId
            )
            ->first();

        if (!$productType) {
            return response()->json([
                'message' => 'Jenis produk tidak ditemukan.',
            ], 404);
        }

        if (
            $productType->category_id !==
            (int) $validated['category_id']
        ) {
            return response()->json([
                'message' =>
                'Jenis produk tidak sesuai dengan kategori yang dipilih.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Update Product + Variants
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $product,
            $validated
        ) {
            $product->update([
                'category_id' => $validated['category_id'],
                'product_type_id' => $validated['product_type_id'],
                'brand_id' => $validated['brand_id'],
                'name' => $validated['name'],
                'sku' => $validated['sku'] ?? null,
                'description' => $validated['description'] ?? null,
                'image' => $validated['image'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | MVP:
            | Hapus variant lama kemudian buat ulang
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
            'productType',
            'brand',
            'variants',
        ]);

        return (new ProductResource($product))
            ->additional([
                'message' => 'Produk berhasil diperbarui.',
            ]);
    }

    /**
     * Remove the specified product.
     */
    public function destroy(
        Request $request,
        Product $product
    ) {
        $tenantId = $request->user()->tenant_id;

        if ($product->tenant_id !== $tenantId) {
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
