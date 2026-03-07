<template>
    <MainLayout>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <Link :href="route('orders.index')"
                        class="text-sm text-zinc-500 hover:text-white transition-colors flex items-center gap-1 mb-2">
                        ← My Orders
                    </Link>
                    <h1 class="font-display text-3xl font-bold text-white">Order #{{ order.id }}</h1>
                    <p class="text-zinc-400 mt-1 text-sm">Placed on {{ formatDate(order.created_at) }}</p>
                </div>
                <span
                    :class="['px-3 py-1.5 rounded-lg text-xs font-semibold uppercase tracking-wide', statusClass(order.status)]">
                    {{ order.status }}
                </span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-5">

                    <!-- Order Items -->
                    <div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
                        <div class="px-5 py-4 border-b border-zinc-800">
                            <h2 class="font-semibold text-white">Items Ordered</h2>
                        </div>
                        <div class="divide-y divide-zinc-800">
                            <div v-for="item in order.items" :key="item.id" class="flex gap-4 p-5">
                                <div class="w-16 h-16 bg-zinc-800 rounded-lg overflow-hidden shrink-0">
                                    <img v-if="item.product?.image" :src="`/storage/${item.product.image}`"
                                        :alt="item.product?.name" class="w-full h-full object-cover" />
                                    <div v-else class="w-full h-full flex items-center justify-center">
                                        <svg class="w-7 h-7 text-zinc-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-zinc-100">{{ item.product?.name ?? 'Product
                                        unavailable' }}</p>
                                    <p class="text-xs text-zinc-500 mt-0.5">
                                        ₱{{ Number(item.price).toLocaleString() }} × {{ item.quantity }}
                                    </p>
                                </div>
                                <p class="text-sm font-bold text-zinc-300 shrink-0">
                                    ₱{{ (Number(item.price) * item.quantity).toLocaleString() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Info -->
                    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5">
                        <h2 class="font-semibold text-white mb-4">Shipping Details</h2>
                        <div class="space-y-2 text-sm">
                            <div class="flex gap-3">
                                <span class="text-zinc-500 w-28 shrink-0">Name</span>
                                <span class="text-zinc-300">{{ order.full_name }}</span>
                            </div>
                            <div class="flex gap-3">
                                <span class="text-zinc-500 w-28 shrink-0">Phone</span>
                                <span class="text-zinc-300">{{ order.phone }}</span>
                            </div>
                            <div class="flex gap-3">
                                <span class="text-zinc-500 w-28 shrink-0">Address</span>
                                <span class="text-zinc-300">
                                    {{ order.address_line }}, {{ order.city }}, {{ order.province }} {{
                                    order.postal_code }}
                                </span>
                            </div>
                            <div v-if="order.notes" class="flex gap-3">
                                <span class="text-zinc-500 w-28 shrink-0">Notes</span>
                                <span class="text-zinc-300">{{ order.notes }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Cancel Button -->
                    <div v-if="canCancel">
                        <button @click="cancelOrder" :disabled="cancelling"
                            class="flex items-center gap-2 px-4 py-2.5 bg-zinc-800 hover:bg-red-500/10 border border-zinc-700 hover:border-red-500/30 text-zinc-400 hover:text-red-400 text-sm font-medium rounded-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            {{ cancelling ? 'Cancelling...' : 'Cancel Order' }}
                        </button>
                        <p class="text-xs text-zinc-600 mt-2">Orders can only be cancelled while pending or confirmed.
                        </p>
                    </div>
                </div>

                <!-- Right Column - Summary -->
                <div class="space-y-5">

                    <!-- Payment Summary -->
                    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5">
                        <h2 class="font-semibold text-white mb-4">Payment Summary</h2>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between text-zinc-400">
                                <span>Subtotal</span>
                                <span>₱{{ Number(order.total_amount).toLocaleString() }}</span>
                            </div>
                            <div class="flex justify-between text-zinc-400">
                                <span>Shipping</span>
                                <span class="text-green-400">Free</span>
                            </div>
                            <div class="flex justify-between text-zinc-400">
                                <span>Payment</span>
                                <span class="uppercase">{{ order.payment_method }}</span>
                            </div>
                        </div>
                        <div class="border-t border-zinc-800 mt-4 pt-4 flex justify-between font-bold text-white">
                            <span>Total</span>
                            <span class="text-red-400">₱{{ Number(order.total_amount).toLocaleString() }}</span>
                        </div>
                    </div>

                    <!-- Order Status -->
                    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5">
                        <h2 class="font-semibold text-white mb-4">Order Status</h2>
                        <div class="space-y-3">
                            <div v-for="(step, index) in statusSteps" :key="step.key" class="flex items-center gap-3">
                                <div :class="['w-7 h-7 rounded-full flex items-center justify-center shrink-0 text-xs font-bold transition-colors',
                                    isCompleted(step.key) ? 'bg-red-500 text-white' :
                                        isActive(step.key) ? 'bg-red-500/20 border-2 border-red-500 text-red-400' :
                                            'bg-zinc-800 text-zinc-600']">
                                    <svg v-if="isCompleted(step.key)" class="w-3.5 h-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span v-else>{{ index + 1 }}</span>
                                </div>
                                <span :class="['text-sm transition-colors',
                                    isActive(step.key) ? 'text-white font-medium' :
                                        isCompleted(step.key) ? 'text-zinc-400' : 'text-zinc-600']">
                                    {{ step.label }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps<{
    order: any;
}>();

const cancelling = ref(false);

const statusSteps = [
    { key: 'pending', label: 'Order Placed' },
    { key: 'confirmed', label: 'Confirmed' },
    { key: 'processing', label: 'Processing' },
    { key: 'shipped', label: 'Shipped' },
    { key: 'delivered', label: 'Delivered' },
];

const stepOrder = statusSteps.map(s => s.key);

const currentIndex = computed(() =>
    stepOrder.indexOf(props.order.status)
);

const isCompleted = (key: string) =>
    stepOrder.indexOf(key) < currentIndex.value;

const isActive = (key: string) =>
    key === props.order.status;

const canCancel = computed(() =>
    ['pending', 'confirmed'].includes(props.order.status)
);

const cancelOrder = () => {
    if (!confirm('Are you sure you want to cancel this order?')) return;
    cancelling.value = true;
    router.patch(route('orders.cancel', props.order.id), {}, {
        onFinish: () => { cancelling.value = false; },
    });
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-PH', {
        year: 'numeric', month: 'long', day: 'numeric'
    });
};

const statusClass = (status: string) => {
    const map: Record<string, string> = {
        pending: 'bg-yellow-500/10 text-yellow-400 border border-yellow-500/20',
        confirmed: 'bg-blue-500/10 text-blue-400 border border-blue-500/20',
        processing: 'bg-purple-500/10 text-purple-400 border border-purple-500/20',
        shipped: 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20',
        delivered: 'bg-green-500/10 text-green-400 border border-green-500/20',
        cancelled: 'bg-zinc-500/10 text-zinc-400 border border-zinc-500/20',
    };
    return map[status] ?? 'bg-zinc-800 text-zinc-400';
};
</script>