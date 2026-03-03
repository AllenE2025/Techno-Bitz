<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    // Get or create cart for the authenticated user
    private function getOrCreateCart(): Cart
    {
        return Cart::firstOrCreate(['user_id' => auth()->id()]);
    }

    public function index(): Response
    {
        $cart = $this->getOrCreateCart();
        $cart->load('items.product.category');

        return Inertia::render('Cart/Index', [
            'cart'  => $cart,
            'total' => $cart->getTotal(),
            'count' => $cart->getItemCount(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1|max:99',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if product is active and in stock
        abort_if(!$product->is_active, 404);
        abort_if(!$product->isInStock(), back()->with('error', 'This product is out of stock.'));

        $cart     = $this->getOrCreateCart();
        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            // If item already in cart, increment quantity
            $newQuantity = $cartItem->quantity + $request->quantity;

            // Make sure we don't exceed stock
            $cartItem->update([
                'quantity' => min($newQuantity, $product->stock),
            ]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity'   => min($request->quantity, $product->stock),
            ]);
        }

        return back()->with('success', 'Item added to cart.');
    }

    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        // Make sure the cart item belongs to the authenticated user
        abort_if($cartItem->cart->user_id !== auth()->id(), 403);

        $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $cartItem->update([
            'quantity' => min($request->quantity, $cartItem->product->stock),
        ]);

        return back()->with('success', 'Cart updated.');
    }

    public function destroy(CartItem $cartItem): RedirectResponse
    {
        // Make sure the cart item belongs to the authenticated user
        abort_if($cartItem->cart->user_id !== auth()->id(), 403);

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }
}
