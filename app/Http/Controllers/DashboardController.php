<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', 1)->count();
        $totalCategories = Category::count();
        $totalVendors = Vendor::count();
        
        $recentProducts = Product::with(['stocks.category', 'stocks.vendor', 'files'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Get category statistics
        $categoryStats = DB::table('stocks')
            ->join('categories', 'stocks.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('count(*) as product_count'))
            ->groupBy('categories.name')
            ->orderBy('product_count', 'desc')
            ->get();
            
        $categoryNames = $categoryStats->pluck('name')->toArray();
        $categoryProductCounts = $categoryStats->pluck('product_count')->toArray();
        
        return view('dashboard.index', compact(
            'totalProducts', 
            'activeProducts', 
            'totalCategories', 
            'totalVendors', 
            'recentProducts',
            'categoryNames',
            'categoryProductCounts'
        ));
    }
}