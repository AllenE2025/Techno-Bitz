<template>
    <MainLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Header -->
            <div class="mb-8">
                <h1 class="font-display text-3xl font-bold text-white">Checkout</h1>
                <p class="text-zinc-400 mt-1 text-sm">Fill in your shipping details to place your order</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Shipping Form -->
                <div class="lg:col-span-2">
                    <form @submit.prevent="submitOrder">
                        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 space-y-5">

                            <h2 class="font-display text-lg font-bold text-white">Shipping Information</h2>

                            <!-- Full Name -->
                            <div>
                                <label class="block text-sm font-medium text-zinc-300 mb-1.5">Full Name</label>
                                <input v-model="form.full_name" type="text" placeholder="Juan Dela Cruz"
                                    class="w-full bg-zinc-800 border rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none transition-colors"
                                    :class="form.errors.full_name ? 'border-red-500' : 'border-zinc-700 focus:border-red-500'" />
                                <p v-if="form.errors.full_name" class="text-red-400 text-xs mt-1">{{
                                    form.errors.full_name }}</p>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="block text-sm font-medium text-zinc-300 mb-1.5">Phone Number</label>
                                <input v-model="form.phone" type="tel" placeholder="09XX XXX XXXX"
                                    class="w-full bg-zinc-800 border rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none transition-colors"
                                    :class="form.errors.phone ? 'border-red-500' : 'border-zinc-700 focus:border-red-500'" />
                                <p v-if="form.errors.phone" class="text-red-400 text-xs mt-1">{{ form.errors.phone }}
                                </p>
                            </div>

                            <!-- Address -->
                            <div>
                                <label class="block text-sm font-medium text-zinc-300 mb-1.5">Address</label>
                                <input v-model="form.address_line" type="text" placeholder="House No., Street, Barangay"
                                    class="w-full bg-zinc-800 border rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none transition-colors"
                                    :class="form.errors.address_line ? 'border-red-500' : 'border-zinc-700 focus:border-red-500'" />
                                <p v-if="form.errors.address_line" class="text-red-400 text-xs mt-1">{{
                                    form.errors.address_line }}</p>
                            </div>

                            <!-- City & Province -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-zinc-300 mb-1.5">City /
                                        Municipality</label>
                                    <input v-model="form.city" type="text" placeholder="Quezon City"
                                        class="w-full bg-zinc-800 border rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none transition-colors"
                                        :class="form.errors.city ? 'border-red-500' : 'border-zinc-700 focus:border-red-500'" />
                                    <p v-if="form.errors.city" class="text-red-400 text-xs mt-1">{{ form.errors.city }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-zinc-300 mb-1.5">Province</label>
                                    <input v-model="form.province" type="text" placeholder="Metro Manila"
                                        class="w-full bg-zinc-800 border rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none transition-colors"
                                        :class="form.errors.province ? 'border-red-500' : 'border-zinc-700 focus:border-red-500'" />
                                    <p v-if="form.errors.province" class="text-red-400 text-xs mt-1">{{
                                        form.errors.province }}</p>
                                </div>
                            </div>

                            <!-- Postal Code -->
                            <div>
                                <label class="block text-sm font-medium text-zinc-300 mb-1.5">Postal Code</label>
                                <input v-model="form.postal_code" type="text" placeholder="1100"
                                    class="w-full bg-zinc-800 border rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none transition-colors"
                                    :class="form.errors.postal_code ? 'border-red-500' : 'border-zinc-700 focus:border-red-500'" />
                                <p v-if="form.errors.postal_code" class="text-red-400 text-xs mt-1">{{
                                    form.errors.postal_code }}</p>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label class="block text-sm font-medium text-zinc-300 mb-1.5">
                                    Order Notes <span class="text-zinc-500 font-normal">(optional)</span>
                                </label>
                                <textarea v-model="form.notes" rows="3"
                                    placeholder="Any special instructions for your order..."
                                    class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-red-500 transition-colors resize-none" />
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 mt-4">
                            <h2 class="font-display text-lg font-bold text-white mb-4">Payment Method</h2>
                            <div class="flex items-center gap-4 bg-zinc-800 border border-red-500/30 rounded-xl p-4">
                                <div
                                    class="w-10 h-10 bg-red-500/10 border border-red-500/20 rounded-lg flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-white">Cash on Delivery (COD)</p>
                                    <p class="text-xs text-zinc-400 mt-0.5">Pay when your order arrives at your doorstep
                                    </p>
                                </div>
                                <div class="ml-auto">
                                    <div
                                        class="w-5 h-5 rounded-full border-2 border-red-500 flex items-center justify-center">
                                        <div class="w-2.5 h-2.5 bg-red-500 rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button (mobile) -->
                        <button type="submit" :disabled="form.processing"
                            class="lg:hidden mt-4 w-full flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold py-3 rounded-xl transition-colors">
                            {{ form.processing ? 'Placing Order...' : `Place Order · ₱${Number(total).toLocaleString()}`
                            }}
                        </button>
                    </form>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 sticky top-24">
                        <h2 class="font-display text-lg font-bold text-white mb-5">Order Summary</h2>

                        <div class="space-y-3">
                            <div v-for="item in cart.items" :key="item.id" class="flex gap-3">
                                <div class="w-12 h-12 bg-zinc-800 rounded-lg overflow-hidden shrink-0">
                                    <img v-if="item.product.image" :src="`/storage/${item.product.image}`"
                                        :alt="item.product.name" class="w-full h-full object-cover" />
                                    <div v-else class="w-full h-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-zinc-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-zinc-100 line-clamp-2 leading-snug">{{ item.product.name }}
                                    </p>
                                    <p class="text-xs text-zinc-500 mt-0.5">Qty: {{ item.quantity }}</p>
                                </div>
                                <p class="text-xs font-semibold text-zinc-300 shrink-0">
                                    ₱{{ (Number(item.product.price) * item.quantity).toLocaleString() }}
                                </p>
                            </div>
                        </div>

                        <div class="border-t border-zinc-800 mt-5 pt-4 space-y-2 text-sm">
                            <div class="flex justify-between text-zinc-400">
                                <span>Subtotal</span>
                                <span>₱{{ Number(total).toLocaleString() }}</span>
                            </div>
                            <div class="flex justify-between text-zinc-400">
                                <span>Shipping</span>
                                <span class="text-green-400">Free</span>
                            </div>
                            <div class="flex justify-between text-zinc-400">
                                <span>Payment</span>
                                <span>COD</span>
                            </div>
                        </div>

                        <div class="border-t border-zinc-800 mt-4 pt-4 flex justify-between font-bold text-white">
                            <span>Total</span>
                            <span class="text-red-400 text-lg">₱{{ Number(total).toLocaleString() }}</span>
                        </div>

                        <!-- Submit Button (desktop) -->
                        <button @click="submitOrder" :disabled="form.processing"
                            class="hidden lg:flex mt-6 w-full items-center justify-center gap-2 bg-red-500 hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold py-3 rounded-xl transition-colors">
                            {{ form.processing ? 'Placing Order...' : 'Place Order' }}
                        </button>

                        <Link :href="route('cart.index')"
                            class="mt-3 w-full flex items-center justify-center text-zinc-400 hover:text-white text-sm transition-colors py-2">
                            ← Back to Cart
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps<{
    cart: {
        items: {
            id: number;
            quantity: number;
            product: {
                id: number;
                name: string;
                price: number;
                image?: string;
            };
        }[];
    };
    total: number;
    user: {
        name: string;
        email: string;
    };
}>();

const form = useForm({
    full_name: props.user?.name ?? '',
    phone: '',
    address_line: '',
    city: '',
    province: '',
    postal_code: '',
    notes: '',
});

const submitOrder = () => {
    form.post(route('checkout.store'));
};
</script>