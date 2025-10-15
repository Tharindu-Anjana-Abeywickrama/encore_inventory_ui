<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Inventory Routes
Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
Route::get('/inventory/{id}', [InventoryController::class, 'show'])->name('inventory.show');
Route::get('/inventory-data', [InventoryController::class, 'getInventoryData'])->name('inventory.data');
Route::get('/inventory/{id}/variants', [InventoryController::class, 'getProductVariants'])->name('inventory.variants');
Route::get('/inventory-export', [InventoryController::class, 'export'])->name('inventory.export');
Route::post('/inventory-import', [InventoryController::class, 'import'])->name('inventory.import');

// File Upload Routes
Route::post('/inventory/{productId}/upload-files', [InventoryController::class, 'uploadFiles'])->name('inventory.upload-files');
Route::delete('/files/{fileId}', [InventoryController::class, 'deleteFile'])->name('files.delete');
Route::post('/inventory/bulk-upload-files', [InventoryController::class, 'bulkUploadFiles'])->name('inventory.bulk-upload-files');
Route::get('/inventory/{productId}/files', [InventoryController::class, 'getProductFiles'])->name('inventory.get-files');
Route::post('/inventory/{productId}/set-main-image', [InventoryController::class, 'setMainImage'])->name('inventory.set-main-image');

// Debug route to check data
Route::get('/debug/inventory', function() {
    $products = \App\Models\Product::with(['stocks.category', 'stocks.vendor', 'variants.stocks', 'files'])->get();
    $categories = \App\Models\Category::where('status', 1)->get();
    $vendors = \App\Models\Vendor::where('status', 1)->get();
    
    return response()->json([
        'products_count' => $products->count(),
        'categories_count' => $categories->count(),
        'vendors_count' => $vendors->count(),
        'products' => $products->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'status' => $product->status,
                'stocks_count' => $product->stocks ? $product->stocks->count() : 0,
                'variants_count' => $product->variants ? $product->variants->count() : 0,
                'files_count' => $product->files ? $product->files->count() : 0,
            ];
        })
    ]);
});
