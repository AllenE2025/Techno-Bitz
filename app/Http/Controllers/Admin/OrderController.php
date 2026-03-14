<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    // Update the index() method in App\Http\Controllers\Admin\OrderController:

    public function index(Request $request): Response
    {
        $query = Order::with('user')
            ->withCount('items')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('id', $request->search)
                    ->orWhere('full_name', 'like', "%{$request->search}%")
                    ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        return Inertia::render('Admin/Orders/Index', [
            'orders'   => $query->paginate(15)->withQueryString(),
            'statuses' => Order::STATUSES,
            'filters'  => $request->only(['status', 'search']),
        ]);
    }

    public function show(Order $order): Response
    {
        $order->load('items.product', 'user');

        return Inertia::render('Admin/Orders/Show', [
            'order'    => $order,
            'statuses' => Order::STATUSES,
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', Order::STATUSES),
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated successfully.');
    }
}
