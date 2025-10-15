<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Category;
use App\Models\Vendor;
use App\Models\Stock;
use App\Models\File;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Categories
        $categories = [
            ['name' => 'Performance Shirts', 'status' => 1],
            ['name' => 'Tank Tops', 'status' => 1],
            ['name' => 'Jackets', 'status' => 1],
            ['name' => 'Athletic Wear', 'status' => 1],
        ];

        $createdCategories = [];
        foreach ($categories as $categoryData) {
            $createdCategories[] = Category::create($categoryData);
        }

        // Create Vendors
        $vendors = [
            ['name' => 'Encore Athletics', 'status' => 1],
            ['name' => 'Pro Sports Supply', 'status' => 1],
            ['name' => 'Athletic Gear Co', 'status' => 1],
        ];

        $createdVendors = [];
        foreach ($vendors as $vendorData) {
            $createdVendors[] = Vendor::create($vendorData);
        }

        // Create Products with Variants and Stocks
        $products = [
            [
                'name' => "Men's Pro Long Sleeve Performance Shirt",
                'description' => 'High-performance long sleeve shirt for men',
                'price' => 68.00,
                'sku' => 'MEN-LS-PRO-001',
                'status' => 0, // Draft status
                'variants' => [
                    ['name' => 'Black', 'color' => 'Black', 'size' => 'M', 'price' => 68.00, 'quantity' => 0],
                    ['name' => 'White', 'color' => 'White', 'size' => 'L', 'price' => 68.00, 'quantity' => 0],
                ]
            ],
            [
                'name' => "Women's Pro Long Sleeve Performance Shirt",
                'description' => 'High-performance long sleeve shirt for women',
                'price' => 68.00,
                'sku' => 'WOM-LS-PRO-001',
                'status' => 1, // Active status
                'variants' => [
                    ['name' => 'Aqua', 'color' => 'Aqua', 'size' => 'S', 'price' => 68.00, 'quantity' => 23],
                    ['name' => 'Blue', 'color' => 'Blue', 'size' => 'M', 'price' => 68.00, 'quantity' => 10],
                    ['name' => 'Black', 'color' => 'Black', 'size' => 'L', 'price' => 68.00, 'quantity' => 10],
                    ['name' => 'White', 'color' => 'White', 'size' => 'XL', 'price' => 68.00, 'quantity' => 20],
                    ['name' => 'Green', 'color' => 'Green', 'size' => 'M', 'price' => 68.00, 'quantity' => 10],
                ]
            ],
            [
                'name' => "Men's Short Sleeve Performance Shirt",
                'description' => 'High-performance short sleeve shirt for men',
                'price' => 55.00,
                'sku' => 'MEN-SS-PRO-001',
                'status' => 1, // Active status
                'variants' => [
                    ['name' => 'Navy', 'color' => 'Navy', 'size' => 'S', 'price' => 55.00, 'quantity' => 15],
                    ['name' => 'Gray', 'color' => 'Gray', 'size' => 'M', 'price' => 55.00, 'quantity' => 12],
                    ['name' => 'Black', 'color' => 'Black', 'size' => 'L', 'price' => 55.00, 'quantity' => 18],
                    ['name' => 'White', 'color' => 'White', 'size' => 'XL', 'price' => 55.00, 'quantity' => 23],
                ]
            ],
            [
                'name' => "Women's Tank Top",
                'description' => 'Comfortable tank top for women',
                'price' => 35.00,
                'sku' => 'WOM-TANK-001',
                'status' => 1, // Active status
                'variants' => [
                    ['name' => 'Pink', 'color' => 'Pink', 'size' => 'S', 'price' => 35.00, 'quantity' => 8],
                    ['name' => 'Purple', 'color' => 'Purple', 'size' => 'M', 'price' => 35.00, 'quantity' => 6],
                    ['name' => 'Blue', 'color' => 'Blue', 'size' => 'L', 'price' => 35.00, 'quantity' => 10],
                ]
            ],
            [
                'name' => "Men's Full Zip Windbreaker Jacket",
                'description' => 'Lightweight windbreaker jacket with full zip',
                'price' => 89.00,
                'sku' => 'MEN-JACKET-WB-001',
                'status' => 1, // Active status
                'variants' => [
                    ['name' => 'Black', 'color' => 'Black', 'size' => 'M', 'price' => 89.00, 'quantity' => 12],
                    ['name' => 'Navy', 'color' => 'Navy', 'size' => 'L', 'price' => 89.00, 'quantity' => 8],
                    ['name' => 'Gray', 'color' => 'Gray', 'size' => 'XL', 'price' => 89.00, 'quantity' => 14],
                ]
            ],
            [
                'name' => "Women's Lightweight Performance Top",
                'description' => 'Lightweight performance top for women',
                'price' => 45.00,
                'sku' => 'WOM-LIGHT-001',
                'status' => 1, // Active status
                'variants' => [
                    ['name' => 'Coral', 'color' => 'Coral', 'size' => 'S', 'price' => 45.00, 'quantity' => 6],
                    ['name' => 'Teal', 'color' => 'Teal', 'size' => 'M', 'price' => 45.00, 'quantity' => 8],
                    ['name' => 'Lavender', 'color' => 'Lavender', 'size' => 'L', 'price' => 45.00, 'quantity' => 4],
                ]
            ]
        ];

        foreach ($products as $productData) {
            $variants = $productData['variants'];
            unset($productData['variants']);
            
            $product = Product::create($productData);
            
            // Create variants for this product
            foreach ($variants as $variantData) {
                $variantData['product_id'] = $product->id;
                $variant = Variant::create($variantData);
                
                // Create stock records for each variant
                Stock::create([
                    'product_id' => $product->id,
                    'variant_id' => $variant->id,
                    'category_id' => $createdCategories[0]->id, // Performance Shirts category
                    'vendor_id' => $createdVendors[0]->id, // Encore Athletics
                    'quantity' => $variantData['quantity'],
                    'sales_channel' => 1,
                    'market' => 'Online Store'
                ]);
            }
            
            // Create some sample files for products
            File::create([
                'file_path' => 'products/product_' . $product->id . '_main.jpg',
                'file_type' => 'image/jpeg',
                'file_name' => 'main_image.jpg',
                'fileable_id' => $product->id,
                'fileable_type' => Product::class
            ]);
        }

        // Create some additional stock records without variants (direct product stock)
        Stock::create([
            'product_id' => 2, // Women's Pro Long Sleeve
            'variant_id' => null,
            'category_id' => $createdCategories[0]->id,
            'vendor_id' => $createdVendors[1]->id, // Pro Sports Supply
            'quantity' => 50,
            'sales_channel' => 2,
            'market' => 'Retail Store'
        ]);

        Stock::create([
            'product_id' => 3, // Men's Short Sleeve
            'variant_id' => null,
            'category_id' => $createdCategories[0]->id,
            'vendor_id' => $createdVendors[2]->id, // Athletic Gear Co
            'quantity' => 75,
            'sales_channel' => 3,
            'market' => 'Wholesale'
        ]);
    }
}
