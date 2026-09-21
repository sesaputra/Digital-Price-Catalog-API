<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductType;
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
        | TENANT - TOKO TEDY
        |--------------------------------------------------------------------------
        */

        $tokoTedy = Tenant::create([
            'name' => 'Toko Tedy',
            'slug' => 'toko-tedy',
            'is_active' => true,
        ]);


        /*
        |--------------------------------------------------------------------------
        | USERS - TOKO TEDY
        |--------------------------------------------------------------------------
        */

        User::create([
            'tenant_id' => $tokoTedy->id,
            'name' => 'Tedy',
            'email' => 'tedy@example.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        User::create([
            'tenant_id' => $tokoTedy->id,
            'name' => 'Andi',
            'email' => 'andi@example.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);


        /*
        |--------------------------------------------------------------------------
        | CATEGORIES - TOKO TEDY
        |--------------------------------------------------------------------------
        */

        $pakuCategory = Category::create([
            'tenant_id' => $tokoTedy->id,
            'name' => 'Paku',
        ]);

        $semenCategory = Category::create([
            'tenant_id' => $tokoTedy->id,
            'name' => 'Semen',
        ]);

        $catCategory = Category::create([
            'tenant_id' => $tokoTedy->id,
            'name' => 'Cat',
        ]);


        /*
        |--------------------------------------------------------------------------
        | BRANDS - TOKO TEDY
        |--------------------------------------------------------------------------
        */

        $jayasteel = Brand::create([
            'tenant_id' => $tokoTedy->id,
            'name' => 'Jayasteel',
        ]);

        $krisbow = Brand::create([
            'tenant_id' => $tokoTedy->id,
            'name' => 'Krisbow',
        ]);

        $semenGresik = Brand::create([
            'tenant_id' => $tokoTedy->id,
            'name' => 'Semen Gresik',
        ]);

        $avian = Brand::create([
            'tenant_id' => $tokoTedy->id,
            'name' => 'Avian',
        ]);

        $dulux = Brand::create([
            'tenant_id' => $tokoTedy->id,
            'name' => 'Dulux',
        ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUCT TYPES - PAKU
        |--------------------------------------------------------------------------
        */

        $pakuBeton = ProductType::create([
            'tenant_id' => $tokoTedy->id,
            'category_id' => $pakuCategory->id,
            'name' => 'Paku Beton',
        ]);

        $pakuKayu = ProductType::create([
            'tenant_id' => $tokoTedy->id,
            'category_id' => $pakuCategory->id,
            'name' => 'Paku Kayu',
        ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUCT TYPES - SEMEN
        |--------------------------------------------------------------------------
        */

        $semenPortland = ProductType::create([
            'tenant_id' => $tokoTedy->id,
            'category_id' => $semenCategory->id,
            'name' => 'Semen Portland',
        ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUCT TYPES - CAT
        |--------------------------------------------------------------------------
        */

        $catTembok = ProductType::create([
            'tenant_id' => $tokoTedy->id,
            'category_id' => $catCategory->id,
            'name' => 'Cat Tembok',
        ]);

        $catKayu = ProductType::create([
            'tenant_id' => $tokoTedy->id,
            'category_id' => $catCategory->id,
            'name' => 'Cat Kayu',
        ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUCT - JAYASTEEL PAKU BETON
        |--------------------------------------------------------------------------
        */

        $jayasteelPakuBeton = Product::create([
            'tenant_id' => $tokoTedy->id,
            'category_id' => $pakuCategory->id,
            'product_type_id' => $pakuBeton->id,
            'brand_id' => $jayasteel->id,
            'name' => 'Jayasteel Paku Beton',
            'sku' => 'PKB-JYS-001',
            'description' => 'Paku beton Jayasteel untuk kebutuhan konstruksi dan pekerjaan bangunan.',
        ]);

        ProductVariant::create([
            'product_id' => $jayasteelPakuBeton->id,
            'name' => '1 inch',
            'unit' => 'kg',
            'price' => 18000,
        ]);

        ProductVariant::create([
            'product_id' => $jayasteelPakuBeton->id,
            'name' => '2 inch',
            'unit' => 'kg',
            'price' => 17000,
        ]);

        ProductVariant::create([
            'product_id' => $jayasteelPakuBeton->id,
            'name' => '3 inch',
            'unit' => 'kg',
            'price' => 16000,
        ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUCT - KRISBOW PAKU BETON
        |--------------------------------------------------------------------------
        */

        $krisbowPakuBeton = Product::create([
            'tenant_id' => $tokoTedy->id,
            'category_id' => $pakuCategory->id,
            'product_type_id' => $pakuBeton->id,
            'brand_id' => $krisbow->id,
            'name' => 'Krisbow Paku Beton',
            'sku' => 'PKB-KRB-001',
            'description' => 'Paku beton Krisbow untuk pekerjaan konstruksi dan renovasi.',
        ]);

        ProductVariant::create([
            'product_id' => $krisbowPakuBeton->id,
            'name' => '1 inch',
            'unit' => 'kg',
            'price' => 20000,
        ]);

        ProductVariant::create([
            'product_id' => $krisbowPakuBeton->id,
            'name' => '2 inch',
            'unit' => 'kg',
            'price' => 18500,
        ]);

        ProductVariant::create([
            'product_id' => $krisbowPakuBeton->id,
            'name' => '3 inch',
            'unit' => 'kg',
            'price' => 17500,
        ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUCT - JAYASTEEL PAKU KAYU
        |--------------------------------------------------------------------------
        */

        $jayasteelPakuKayu = Product::create([
            'tenant_id' => $tokoTedy->id,
            'category_id' => $pakuCategory->id,
            'product_type_id' => $pakuKayu->id,
            'brand_id' => $jayasteel->id,
            'name' => 'Jayasteel Paku Kayu',
            'sku' => 'PKK-JYS-001',
            'description' => 'Paku kayu Jayasteel untuk pekerjaan kayu dan konstruksi ringan.',
        ]);

        ProductVariant::create([
            'product_id' => $jayasteelPakuKayu->id,
            'name' => '1 inch',
            'unit' => 'kg',
            'price' => 17000,
        ]);

        ProductVariant::create([
            'product_id' => $jayasteelPakuKayu->id,
            'name' => '2 inch',
            'unit' => 'kg',
            'price' => 16000,
        ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUCT - SEMEN GRESIK
        |--------------------------------------------------------------------------
        */

        $semenGresikPortland = Product::create([
            'tenant_id' => $tokoTedy->id,
            'category_id' => $semenCategory->id,
            'product_type_id' => $semenPortland->id,
            'brand_id' => $semenGresik->id,
            'name' => 'Semen Gresik Portland',
            'sku' => 'SMN-GSK-001',
            'description' => 'Semen Gresik untuk kebutuhan konstruksi dan pekerjaan bangunan.',
        ]);

        ProductVariant::create([
            'product_id' => $semenGresikPortland->id,
            'name' => '40 kg',
            'unit' => 'sak',
            'price' => 65000,
        ]);

        ProductVariant::create([
            'product_id' => $semenGresikPortland->id,
            'name' => '50 kg',
            'unit' => 'sak',
            'price' => 78000,
        ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUCT - AVIAN CAT TEMBOK
        |--------------------------------------------------------------------------
        */

        $avianCatTembok = Product::create([
            'tenant_id' => $tokoTedy->id,
            'category_id' => $catCategory->id,
            'product_type_id' => $catTembok->id,
            'brand_id' => $avian->id,
            'name' => 'Avian Cat Tembok',
            'sku' => 'CAT-AVN-001',
            'description' => 'Cat tembok Avian untuk kebutuhan pengecatan interior dan eksterior.',
        ]);

        ProductVariant::create([
            'product_id' => $avianCatTembok->id,
            'name' => '1 kg',
            'unit' => 'kaleng',
            'price' => 55000,
        ]);

        ProductVariant::create([
            'product_id' => $avianCatTembok->id,
            'name' => '5 kg',
            'unit' => 'kaleng',
            'price' => 125000,
        ]);

        ProductVariant::create([
            'product_id' => $avianCatTembok->id,
            'name' => '20 kg',
            'unit' => 'kaleng',
            'price' => 425000,
        ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUCT - DULUX CAT TEMBOK
        |--------------------------------------------------------------------------
        */

        $duluxCatTembok = Product::create([
            'tenant_id' => $tokoTedy->id,
            'category_id' => $catCategory->id,
            'product_type_id' => $catTembok->id,
            'brand_id' => $dulux->id,
            'name' => 'Dulux Cat Tembok',
            'sku' => 'CAT-DLX-001',
            'description' => 'Cat tembok Dulux untuk kebutuhan pengecatan rumah dan bangunan.',
        ]);

        ProductVariant::create([
            'product_id' => $duluxCatTembok->id,
            'name' => '1 kg',
            'unit' => 'kaleng',
            'price' => 65000,
        ]);

        ProductVariant::create([
            'product_id' => $duluxCatTembok->id,
            'name' => '5 kg',
            'unit' => 'kaleng',
            'price' => 145000,
        ]);

        ProductVariant::create([
            'product_id' => $duluxCatTembok->id,
            'name' => '20 kg',
            'unit' => 'kaleng',
            'price' => 465000,
        ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUCT - AVIAN CAT KAYU
        |--------------------------------------------------------------------------
        */

        $avianCatKayu = Product::create([
            'tenant_id' => $tokoTedy->id,
            'category_id' => $catCategory->id,
            'product_type_id' => $catKayu->id,
            'brand_id' => $avian->id,
            'name' => 'Avian Cat Kayu',
            'sku' => 'CKY-AVN-001',
            'description' => 'Cat kayu Avian untuk perlindungan dan finishing permukaan kayu.',
        ]);

        ProductVariant::create([
            'product_id' => $avianCatKayu->id,
            'name' => '1 kg',
            'unit' => 'kaleng',
            'price' => 58000,
        ]);

        ProductVariant::create([
            'product_id' => $avianCatKayu->id,
            'name' => '2.5 kg',
            'unit' => 'kaleng',
            'price' => 135000,
        ]);


        /*
        |--------------------------------------------------------------------------
        | TENANT - TOKO DIAN
        |--------------------------------------------------------------------------
        */

        $tokoDian = Tenant::create([
            'name' => 'Toko Dian',
            'slug' => 'toko-dian',
            'is_active' => true,
        ]);


        /*
        |--------------------------------------------------------------------------
        | USERS - TOKO DIAN
        |--------------------------------------------------------------------------
        */

        User::create([
            'tenant_id' => $tokoDian->id,
            'name' => 'Dian',
            'email' => 'dian@example.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);


        /*
        |--------------------------------------------------------------------------
        | CATEGORIES - TOKO DIAN
        |--------------------------------------------------------------------------
        */

        $keramikCategory = Category::create([
            'tenant_id' => $tokoDian->id,
            'name' => 'Keramik',
        ]);

        $catDianCategory = Category::create([
            'tenant_id' => $tokoDian->id,
            'name' => 'Cat',
        ]);


        /*
        |--------------------------------------------------------------------------
        | BRANDS - TOKO DIAN
        |--------------------------------------------------------------------------
        */

        $roman = Brand::create([
            'tenant_id' => $tokoDian->id,
            'name' => 'Roman',
        ]);

        $platinum = Brand::create([
            'tenant_id' => $tokoDian->id,
            'name' => 'Platinum',
        ]);

        $propan = Brand::create([
            'tenant_id' => $tokoDian->id,
            'name' => 'Propan',
        ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUCT TYPES - KERAMIK
        |--------------------------------------------------------------------------
        */

        $keramikLantai = ProductType::create([
            'tenant_id' => $tokoDian->id,
            'category_id' => $keramikCategory->id,
            'name' => 'Keramik Lantai',
        ]);

        $keramikDinding = ProductType::create([
            'tenant_id' => $tokoDian->id,
            'category_id' => $keramikCategory->id,
            'name' => 'Keramik Dinding',
        ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUCT TYPES - CAT
        |--------------------------------------------------------------------------
        */

        $catKayuDian = ProductType::create([
            'tenant_id' => $tokoDian->id,
            'category_id' => $catDianCategory->id,
            'name' => 'Cat Kayu',
        ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUCT - ROMAN KERAMIK LANTAI
        |--------------------------------------------------------------------------
        */

        $romanKeramik = Product::create([
            'tenant_id' => $tokoDian->id,
            'category_id' => $keramikCategory->id,
            'product_type_id' => $keramikLantai->id,
            'brand_id' => $roman->id,
            'name' => 'Roman Keramik Lantai',
            'sku' => 'KRM-RMN-001',
            'description' => 'Keramik lantai Roman untuk kebutuhan rumah dan bangunan.',
        ]);

        ProductVariant::create([
            'product_id' => $romanKeramik->id,
            'name' => '40x40 cm',
            'unit' => 'dus',
            'price' => 65000,
        ]);

        ProductVariant::create([
            'product_id' => $romanKeramik->id,
            'name' => '50x50 cm',
            'unit' => 'dus',
            'price' => 85000,
        ]);

        ProductVariant::create([
            'product_id' => $romanKeramik->id,
            'name' => '60x60 cm',
            'unit' => 'dus',
            'price' => 105000,
        ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUCT - PLATINUM KERAMIK LANTAI
        |--------------------------------------------------------------------------
        */

        $platinumKeramik = Product::create([
            'tenant_id' => $tokoDian->id,
            'category_id' => $keramikCategory->id,
            'product_type_id' => $keramikLantai->id,
            'brand_id' => $platinum->id,
            'name' => 'Platinum Keramik Lantai',
            'sku' => 'KRM-PLT-001',
            'description' => 'Keramik lantai Platinum untuk berbagai kebutuhan bangunan.',
        ]);

        ProductVariant::create([
            'product_id' => $platinumKeramik->id,
            'name' => '40x40 cm',
            'unit' => 'dus',
            'price' => 60000,
        ]);

        ProductVariant::create([
            'product_id' => $platinumKeramik->id,
            'name' => '60x60 cm',
            'unit' => 'dus',
            'price' => 98000,
        ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUCT - ROMAN KERAMIK DINDING
        |--------------------------------------------------------------------------
        */

        $romanKeramikDinding = Product::create([
            'tenant_id' => $tokoDian->id,
            'category_id' => $keramikCategory->id,
            'product_type_id' => $keramikDinding->id,
            'brand_id' => $roman->id,
            'name' => 'Roman Keramik Dinding',
            'sku' => 'KRD-RMN-001',
            'description' => 'Keramik dinding Roman untuk kebutuhan interior bangunan.',
        ]);

        ProductVariant::create([
            'product_id' => $romanKeramikDinding->id,
            'name' => '25x40 cm',
            'unit' => 'dus',
            'price' => 72000,
        ]);

        ProductVariant::create([
            'product_id' => $romanKeramikDinding->id,
            'name' => '30x60 cm',
            'unit' => 'dus',
            'price' => 92000,
        ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUCT - PROPAN CAT KAYU
        |--------------------------------------------------------------------------
        */

        $propanCatKayu = Product::create([
            'tenant_id' => $tokoDian->id,
            'category_id' => $catDianCategory->id,
            'product_type_id' => $catKayuDian->id,
            'brand_id' => $propan->id,
            'name' => 'Propan Cat Kayu',
            'sku' => 'CKY-PRP-001',
            'description' => 'Cat kayu Propan untuk finishing dan perlindungan permukaan kayu.',
        ]);

        ProductVariant::create([
            'product_id' => $propanCatKayu->id,
            'name' => '1 kg',
            'unit' => 'kaleng',
            'price' => 62000,
        ]);

        ProductVariant::create([
            'product_id' => $propanCatKayu->id,
            'name' => '2.5 kg',
            'unit' => 'kaleng',
            'price' => 145000,
        ]);
    }
}