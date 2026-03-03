<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function index(): Response|RedirectResponse
    {
        $cart = Cart::with('items.product')
            ->where('user_id', auth()->id())
            ->first();

        // Redirect to cart if cart is empty
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return Inertia::render('Checkout/Index', [
            'cart'  => $cart,
            'total' => $cart->getTotal(),
            'user'  => auth()->user(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name'    => 'required|string|max:255',
            'phone'        => 'required|string|max:20',
            'address_line' => 'required|string|max:255',
            'city'         => 'required|string|max:100',
            'province'     => 'required|string|max:100',
            'postal_code'  => 'required|string|max:10',
            'notes'        => 'nullable|string|max:500',
        ]);

        $cart = Cart::with('items.product')
            ->where('user_id', auth()->id())
            ->first();

        // Redirect if cart is empty
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Wrap in a transaction so if anything fails, nothing is saved
        DB::transaction(function () use ($request, $cart) {
            $total = $cart->getTotal();

            // Create the order
            $order = Order::create([
                'user_id'        => auth()->id(),
                'total_amount'   => $total,
                'full_name'      => $request->full_name,
                'phone'          => $request->phone,
                'address_line'   => $request->address_line,
                'city'           => $request->city,
                'province'       => $request->province,
                'postal_code'    => $request->postal_code,
                'payment_method' => 'cod',
                'payment_status' => 'pending',
                'status'         => 'pending',
                'notes'          => $request->notes,
            ]);

            // Create order items and deduct stock
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->product->price, // snapshot price at time of order
                ]);

                // Deduct stock
                $item->product->decrement('stock', $item->quantity);
            }

            // Clear the cart after order is placed
            $cart->items()->delete();
        });

        return redirect()->route('orders.index')->with('success', 'Order placed successfully! We will confirm your order shortly.');
    }
}
