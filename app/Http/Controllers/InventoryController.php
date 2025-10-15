<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Variant;
use App\Models\Vendor;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    public function index()
    {
        // Load products 
        $products = Product::with([
            'stocks.category', 
            'stocks.vendor', 
            'variants.stocks', 
            'files'
        ])->get();
        
        $categories = Category::where('status', 1)->get();
        $vendors = Vendor::where('status', 1)->get();
        
        // Debug: Check if we have products
        // Log::info('Products loaded: ' . $products->count());
        // Log::info('Categories loaded: ' . $categories->count());
        // Log::info('Vendors loaded: ' . $vendors->count());
        
        return view('inventory.index', compact('products', 'categories', 'vendors'));
    }
    
    /**
     * Get inventory 
     */
    public function getInventoryData(Request $request)
    {
        $query = Product::with([
            'stocks.category', 
            'stocks.vendor', 
            'variants.stocks', 
            'files'
        ]);
        
        //  status 
        if ($request->has('status_filter') && $request->status_filter !== 'all') {
            switch ($request->status_filter) {
                case 'active':
                    $query->where('status', 1);
                    break;
                case 'draft':
                    $query->where('status', 0);
                    break;
                case 'achieved':
                    // Assuming achieved means archived or some other status
                    $query->where('status', 2);
                    break;
            }
        }
        
        
       
        
        // Apply total filter
        if ($request->has('total_filter') && $request->total_filter) {
            switch ($request->total_filter) {
                case 'active':
                    $query->where('status', 1);
                    break;
                case 'draft':
                    $query->where('status', 0);
                    break;
                case 'archived':
                    $query->where('status', 2);
                    break;
            }
        }
        
        // Get total count before pagination
        $totalRecords = $query->count();
        
        // Apply DataTable parameters
        $start = $request->input('start', 0);
        $length = $request->input('length', 50);
        $searchValue = $request->input('search.value', '');
        
        // Apply search
        if (!empty($searchValue)) {
            $query->where(function($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                  ->orWhere('sku', 'like', "%{$searchValue}%")
                  ->orWhere('description', 'like', "%{$searchValue}%");
            });
        }
        
        // Get filtered count
        $filteredRecords = $query->count();
        
        // Apply ordering
        $orderColumn = $request->input('order.0.column', 1);
        $orderDirection = $request->input('order.0.dir', 'asc');
        
        $columns = ['id', 'name', 'status', 'inventory', 'sales_channels', 'markets', 'category', 'vendor'];
        if (isset($columns[$orderColumn])) {
            $query->orderBy($columns[$orderColumn], $orderDirection);
        }
        
        // Apply pagination
        $products = $query->skip($start)->take($length)->get();
        
        // Prepare data for DataTable
        $data = [];
        foreach ($products as $product) {
            $stockCount = $product->stocks ? $product->stocks->sum('quantity') : 0;
            $variantCount = $product->variants ? $product->variants->count() : 0;
            $salesChannels = $product->stocks ? $product->stocks->sum('sales_channel') : 0;
            $markets = $product->stocks ? $product->stocks->count() : 0;
            
            // Get main image
            $mainImage = null;
            if ($product->files && $product->files->count() > 0) {
                $mainImageFile = $product->files->where('file_name', 'like', 'main_image_%')->first();
                if (!$mainImageFile) {
                    $mainImageFile = $product->files->first();
                }
                $mainImage = $mainImageFile ? asset('storage/' . $mainImageFile->file_path) : null;
            }
            
            // Get categories
            $categories = $product->stocks ? $product->stocks->pluck('category')->unique()->filter()->values() : collect();
            
            // Get vendors
            $vendors = $product->stocks ? $product->stocks->pluck('vendor')->unique()->filter()->values() : collect();
            
            $data[] = [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'status' => $product->status,
                'main_image' => $mainImage,
                'total_stock' => $stockCount,
                'variant_count' => $variantCount,
                'sales_channels' => $salesChannels,
                'markets' => $markets,
                'categories' => $categories,
                'vendors' => $vendors,
            ];
        }
        
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }
    
    /**
     * Get variants for a specific product
     */
    public function getProductVariants($id)
    {
        $product = Product::with(['variants.stocks', 'variants.files'])->findOrFail($id);
        
        $variants = [];
        foreach ($product->variants as $variant) {
            $stock = $variant->stocks ? $variant->stocks->first() : null;
            $quantity = $stock ? ($stock->quantity ?? 0) : ($variant->quantity ?? 0);
            
            // Get variant image if exists
            $variantImage = null;
            if ($variant->files && $variant->files->count() > 0) {
                $variantImageFile = $variant->files->first();
                $variantImage = $variantImageFile ? asset('storage/' . $variantImageFile->file_path) : null;
            }
            
            $variants[] = [
                'id' => $variant->id,
                'name' => $variant->name,
                'color' => $variant->color,
                'size' => $variant->size,
                'price' => $variant->price,
                'stock' => $quantity,
                'image' => $variantImage,
            ];
        }
        
        return response()->json([
            'success' => true,
            'variants' => $variants
        ]);
    }
    
    public function show($id)
    {
        $product = Product::with(['stocks.category', 'stocks.vendor', 'variants.stocks', 'files'])->findOrFail($id);
        
        return view('inventory.show', compact('product'));
    }
    
    /**
     * Upload files for a product
     */
    public function uploadFiles(Request $request, $productId)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'files' => 'required|array|max:10' // Max 10 files
        ]);
        
        $product = Product::findOrFail($productId);
        $uploadedFiles = [];
        
        foreach ($request->file('files') as $file) {
            // Generate unique filename
            $extension = $file->getClientOriginalExtension();
            $filename = 'product_' . $productId . '_' . time() . '_' . Str::random(8) . '.' . $extension;
            
            // Store file
            $path = $file->storeAs('products', $filename, 'public');
            
            // Create file record
            $fileRecord = File::create([
                'file_path' => $path,
                'file_type' => $file->getMimeType(),
                'file_name' => $file->getClientOriginalName(),
                'fileable_id' => $product->id,
                'fileable_type' => Product::class
            ]);
            
            $uploadedFiles[] = $fileRecord;
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Files uploaded successfully',
            'files' => $uploadedFiles
        ]);
    }
    
    /**
     * I havent't engugh time to implement this
     */
    // public function deleteFile($fileId)
    // {
    //     $file = File::findOrFail($fileId);
        
    //     // Delete physical file
    //     if (Storage::disk('public')->exists($file->file_path)) {
    //         Storage::disk('public')->delete($file->file_path);
    //     }
        
    //     // Delete database record
    //     $file->delete();
        
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'File deleted successfully'
    //     ]);
    // }
    
   
    
    /**
     * Get files for a product
     */
    public function getProductFiles($productId)
    {
        $product = Product::with('files')->findOrFail($productId);
        
        return response()->json([
            'success' => true,
            'files' => $product->files
        ]);
    }
    
    /**
     * Set main image for a product
     */
    public function setMainImage(Request $request, $productId)
    {
        $request->validate([
            'file_id' => 'required|exists:files,id'
        ]);
        
        $product = Product::findOrFail($productId);
        $file = File::findOrFail($request->file_id);
        
        // Ensure the file belongs to this product
        if ($file->fileable_id != $product->id || $file->fileable_type != Product::class) {
            return response()->json([
                'success' => false,
                'message' => 'File does not belong to this product'
            ], 400);
        }
        
        // Update file name to indicate it's the main image
        $file->update(['file_name' => 'main_image_' . $file->file_name]);
        
        return response()->json([
            'success' => true,
            'message' => 'Main image set successfully'
        ]);
    }
    
    /**
     * Export inventory data to CSV
     */
    public function export(Request $request)
    {
        $query = Product::with([
            'stocks.category', 
            'stocks.vendor', 
            'variants.stocks', 
            'files'
        ]);
        
        // Apply filters
        if ($request->has('status') && $request->status !== 'all') {
            switch ($request->status) {
                case 'active':
                    $query->where('status', 1);
                    break;
                case 'draft':
                    $query->where('status', 0);
                    break;
                case 'achieved':
                    $query->where('status', 2);
                    break;
            }
        }
        
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('sku', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }
        
        $products = $query->get();
        
        $filename = 'inventory_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID',
                'Product Name',
                'SKU',
                'Status',
                'Description',
                'Price',
                'Total Stock',
                'Variant Count',
                'Categories',
                'Vendors',
                'Sales Channels',
                'Markets',
                'Created At'
            ]);
            
            // CSV Data
            foreach ($products as $product) {
                $stockCount = $product->stocks ? $product->stocks->sum('quantity') : 0;
                $variantCount = $product->variants ? $product->variants->count() : 0;
                $salesChannels = $product->stocks ? $product->stocks->sum('sales_channel') : 0;
                $markets = $product->stocks ? $product->stocks->count() : 0;
                
                $categories = $product->stocks ? 
                    $product->stocks->pluck('category')->unique()->filter()->pluck('name')->implode(', ') : '';
                
                $vendors = $product->stocks ? 
                    $product->stocks->pluck('vendor')->unique()->filter()->pluck('name')->implode(', ') : '';
                
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->sku,
                    $product->status ? 'Active' : 'Inactive',
                    $product->description,
                    $product->price,
                    $stockCount,
                    $variantCount,
                    $categories,
                    $vendors,
                    $salesChannels,
                    $markets,
                    $product->created_at
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Import inventory data from CSV/Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240' // 10MB max
        ]);
        
        try {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            
            if ($extension === 'csv') {
                return $this->importCsv($file);
            } elseif (in_array($extension, ['xlsx', 'xls'])) {
                return $this->importExcel($file);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unsupported file format. Please use CSV, XLSX, or XLS files.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Import CSV data
     */
    private function importCsv($file)
    {
        $handle = fopen($file->getPathname(), 'r');
        $header = fgetcsv($handle); // Skip header row
        $imported = 0;
        $errors = [];
        
        while (($data = fgetcsv($handle)) !== false) {
            try {
                if (count($data) >= 6) { // Ensure we have minimum required columns
                    Product::create([
                        'name' => $data[1] ?? 'Imported Product',
                        'sku' => $data[2] ?? null,
                        'status' => isset($data[3]) ? (strtolower($data[3]) === 'active' ? 1 : 0) : 0,
                        'description' => $data[4] ?? null,
                        'price' => isset($data[5]) ? floatval($data[5]) : 0,
                    ]);
                    $imported++;
                }
            } catch (\Exception $e) {
                $errors[] = 'Row ' . ($imported + count($errors) + 1) . ': ' . $e->getMessage();
            }
        }
        
        fclose($handle);
        
        $message = "Successfully imported {$imported} products.";
        if (!empty($errors)) {
            $message .= " " . count($errors) . " rows had errors.";
        }
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'imported' => $imported,
            'errors' => $errors
        ]);
    }
    
    /**
     * I havent't engugh time to implement this
     */
    // private function importExcel($file)
    // {
    //     // This would require installing PhpSpreadsheet package
    //     // For now, return a message indicating Excel support needs to be implemented
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Excel import functionality requires PhpSpreadsheet package. Please use CSV format for now.'
    //     ]);
    // }
}