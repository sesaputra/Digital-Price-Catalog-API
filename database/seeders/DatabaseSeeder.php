<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | TENANT
        |--------------------------------------------------------------------------
        */

        $tenant = Tenant::create([
            'name' => 'Toko Tedy',
            'slug' => 'toko-tedy',
            'is_active' => true,
        ]);


        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */

        $owner = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Tedy',
            'email' => 'tedy@example.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        $employee = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Andi',
            'email' => 'andi@example.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);


        /*
        |--------------------------------------------------------------------------
        | CATEGORIES
        |--------------------------------------------------------------------------
        */

        $pakuCategory = Category::create([
            'tenant_id' => $tenant->id,
            'name' => 'Paku',
        ]);

        $semenCategory = Category::create([
            'tenant_id' => $tenant->id,
            'name' => 'Semen',
        ]);

        $catCategory = Category::create([
            'tenant_id' => $tenant->id,
            'name' => 'Cat',
        ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUCT - PAKU
        |--------------------------------------------------------------------------
        */

        $paku = Product::create([
            'tenant_id' => $tenant->id,
            'category_id' => $pakuCategory->id,
            'name' => 'Paku',
            'sku' => 'PKU-001',
            'description' => 'Paku untuk kebutuhan konstruksi dan bangunan.',
        ]);

        ProductVariant::create([
            'product_id' => $paku->id,
            'name' => '1/2 inch',
            'unit' => 'kg',
            'price' => 18000,
        ]);

        ProductVariant::create([
            'product_id' => $paku->id,
            'name' => '1 inch',
            'unit' => 'kg',
            'price' => 17000,
        ]);

        ProductVariant::create([
            'product_id' => $paku->id,
            'name' => '2 inch',
            'unit' => 'kg',
            'price' => 15000,
        ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUCT - SEMEN
        |--------------------------------------------------------------------------
        */

        $semen = Product::create([
            'tenant_id' => $tenant->id,
            'category_id' => $semenCategory->id,
            'name' => 'Semen',
            'sku' => 'SMN-001',
            'description' => 'Semen untuk kebutuhan konstruksi.',
        ]);

        ProductVariant::create([
            'product_id' => $semen->id,
            'name' => '40 kg',
            'unit' => 'sak',
            'price' => 65000,
        ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUCT - CAT
        |--------------------------------------------------------------------------
        */

        $cat = Product::create([
            'tenant_id' => $tenant->id,
            'category_id' => $catCategory->id,
            'name' => 'Cat Tembok',
            'sku' => 'CAT-001',
            'description' => 'Cat tembok untuk kebutuhan bangunan.',
        ]);

        ProductVariant::create([
            'product_id' => $cat->id,
            'name' => '5 kg',
            'unit' => 'kaleng',
            'price' => 125000,
        ]);

        ProductVariant::create([
            'product_id' => $cat->id,
            'name' => '20 kg',
            'unit' => 'kaleng',
            'price' => 425000,
        ]);
    }
}