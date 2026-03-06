<template>
    <MainLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Header -->
            <div class="mb-8">
                <h1 class="font-display text-3xl font-bold text-white">Your Cart</h1>
                <p class="text-zinc-400 mt-1 text-sm">{{ count }} {{ count === 1 ? 'item' : 'items' }}</p>
            </div>

            <!-- Empty Cart -->
            <div v-if="!cart.items || cart.items.length === 0" class="text-center py-24">
                <div
                    class="w-20 h-20 bg-zinc-900 border border-zinc-800 rounded-2xl flex items-center justify-center mx-auto mb-5">
                    <svg class="w-10 h-10 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <p class="text-zinc-300 font-medium text-lg">Your cart is empty</p>
                <p class="text-zinc-500 text-sm mt-1">Add some products to get started</p>
                <Link :href="route('shop.index')"
                    class="inline-flex items-center gap-2 mt-6 bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-3 rounded-xl transition-colors">
                    Browse Shop
                </Link>
            </div>

            <!-- Cart Content -->
            <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-3">
                    <div v-for="item in cart.items" :key="item.id"
                        class="flex gap-4 bg-zinc-900 border border-zinc-800 rounded-xl p-4 hover:border-zinc-700 transition-colors">

                        <!-- Product Image -->
                        <Link :href="route('shop.show', item.product.slug)"
                            class="w-20 h-20 bg-zinc-800 rounded-lg overflow-hidden shrink-0">
                            <img v-if="item.product.image" :src="`/storage/${item.product.image}`"
                                :alt="item.product.name" class="w-full h-full object-cover" />
                            <div v-else class="w-full h-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-zinc-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </Link>

                        <!-- Product Info -->
                        <div class="flex-1 min-w-0">
                            <p v-if="item.product.brand" class="text-xs text-zinc-500">{{ item.product.brand }}</p>
                            <Link :href="route('shop.show', item.product.slug)"
                                class="text-sm font-medium text-zinc-100 hover:text-red-400 transition-colors line-clamp-2">
                                {{ item.product.name }}
                            </Link>
                            <p class="text-red-400 font-bold text-sm mt-1">
                                ₱{{ Number(item.product.price).toLocaleString() }}
                            </p>

                            <!-- Quantity & Remove -->
                            <div class="flex items-center justify-between mt-3">
                                <div
                                    class="flex items-center bg-zinc-800 border border-zinc-700 rounded-lg overflow-hidden">
                                    <button @click="updateQuantity(item, item.quantity - 1)"
                                        :disabled="item.quantity <= 1"
                                        class="px-3 py-1.5 text-zinc-400 hover:text-white hover:bg-zinc-700 disabled:opacity-30 disabled:cursor-not-allowed transition-colors text-sm font-medium">
                                        −
                                    </button>
                                    <span class="px-3 text-white text-sm font-medium min-w-[2rem] text-center">
                                        {{ item.quantity }}
                                    </span>
                                    <button @click="updateQuantity(item, item.quantity + 1)"
                                        :disabled="item.quantity >= item.product.stock"
                                        class="px-3 py-1.5 text-zinc-400 hover:text-white hover:bg-zinc-700 disabled:opacity-30 disabled:cursor-not-allowed transition-colors text-sm font-medium">
                                        +
                                    </button>
                                </div>

                                <div class="flex items-center gap-3">
                                    <p class="text-sm font-semibold text-zinc-300">
                                        ₱{{ (Number(item.product.price) * item.quantity).toLocaleString() }}
                                    </p>
                                    <button @click="removeItem(item)"
                                        class="p-1.5 text-zinc-600 hover:text-red-400 transition-colors rounded-lg hover:bg-zinc-800">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 sticky top-24">
                        <h2 class="font-display text-lg font-bold text-white mb-5">Order Summary</h2>

                        <div class="space-y-3 text-sm">
                            <div v-for="item in cart.items" :key="item.id" class="flex justify-between text-zinc-400">
                                <span class="truncate max-w-[160px]">{{ item.product.name }} × {{ item.quantity
                                    }}</span>
                                <span class="shrink-0 ml-2">₱{{ (Number(item.product.price) *
                                    item.quantity).toLocaleString() }}</span>
                            </div>
                        </div>

                        <div class="border-t border-zinc-800 mt-4 pt-4 space-y-2">
                            <div class="flex justify-between text-sm text-zinc-400">
                                <span>Subtotal</span>
                                <span>₱{{ Number(total).toLocaleString() }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-zinc-400">
                                <span>Shipping</span>
                                <span class="text-green-400">Free</span>
                            </div>
                            <div class="flex justify-between text-sm text-zinc-400">
                                <span>Payment</span>
                                <span>Cash on Delivery</span>
                            </div>
                        </div>

                        <div class="border-t border-zinc-800 mt-4 pt-4 flex justify-between font-bold text-white">
                            <span>Total</span>
                            <span class="text-red-400 text-lg">₱{{ Number(total).toLocaleString() }}</span>
                        </div>

                        <Link :href="route('checkout.index')"
                            class="mt-6 w-full flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white font-semibold py-3 rounded-xl transition-colors">
                            Proceed to Checkout
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>

                        <Link :href="route('shop.index')"
                            class="mt-3 w-full flex items-center justify-center gap-2 text-zinc-400 hover:text-white text-sm transition-colors py-2">
                            ← Continue Shopping
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps<{
    cart: {
        items: {
            id: number;
            quantity: number;
            product: {
                id: number;
                name: string;
                slug: string;
                brand?: string;
                price: number;
                stock: number;
                image?: string;
            };
        }[];
    };
    total: number;
    count: number;
}>();

const updateQuantity = (item: any, newQuantity: number) => {
    if (newQuantity < 1 || newQuantity > item.product.stock) return;

    router.patch(route('cart.update', item.id), {
        quantity: newQuantity,
    }, { preserveScroll: true });
};

const removeItem = (item: any) => {
    router.delete(route('cart.destroy', item.id), {
        preserveScroll: true,
    });
};
</script>