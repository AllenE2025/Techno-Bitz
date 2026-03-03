<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Product::with('category')
            ->where('is_active', true);

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by brand
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // Filter by condition
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Search by name or brand
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('brand', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        // Sort
        $sortOptions = ['price_asc', 'price_desc', 'newest', 'name_asc'];
        $sort = in_array($request->sort, $sortOptions) ? $request->sort : 'newest';

        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name_asc'   => $query->orderBy('name', 'asc'),
            default      => $query->latest(),
        };

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::all();
        $brands     = Product::where('is_active', true)
            ->whereNotNull('brand')
            ->distinct()
            ->pluck('brand');

        return Inertia::render('Shop/Index', [
            'products'   => $products,
            'categories' => $categories,
            'brands'     => $brands,
            'filters'    => $request->only(['search', 'category', 'brand', 'condition', 'min_price', 'max_price', 'sort']),
        ]);
    }

    public function show(Product $product): Response
    {
        // Redirect if product is inactive
        abort_if(!$product->is_active, 404);

        $product->load('category');

        // Related products from the same category
        $related = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return Inertia::render('Shop/Show', [
            'product' => $product,
            'related' => $related,
        ]);
    }
}
