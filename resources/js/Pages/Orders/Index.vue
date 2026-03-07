<template>
    <MainLayout>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Header -->
            <div class="mb-8">
                <h1 class="font-display text-3xl font-bold text-white">My Orders</h1>
                <p class="text-zinc-400 mt-1 text-sm">Track and manage your orders</p>
            </div>

            <!-- Empty State -->
            <div v-if="orders.data.length === 0" class="text-center py-24">
                <div
                    class="w-20 h-20 bg-zinc-900 border border-zinc-800 rounded-2xl flex items-center justify-center mx-auto mb-5">
                    <svg class="w-10 h-10 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <p class="text-zinc-300 font-medium text-lg">No orders yet</p>
                <p class="text-zinc-500 text-sm mt-1">Your order history will appear here</p>
                <Link :href="route('shop.index')"
                    class="inline-flex items-center gap-2 mt-6 bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-3 rounded-xl transition-colors">
                    Start Shopping
                </Link>
            </div>

            <!-- Orders List -->
            <div v-else class="space-y-4">
                <Link v-for="order in orders.data" :key="order.id" :href="route('orders.show', order.id)"
                    class="block bg-zinc-900 border border-zinc-800 rounded-xl p-5 hover:border-zinc-600 transition-all duration-200">

                    <!-- Order Header -->
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-white">Order #{{ order.id }}</p>
                            <p class="text-xs text-zinc-500 mt-0.5">{{ formatDate(order.created_at) }}</p>
                        </div>
                        <span
                            :class="['px-2.5 py-1 rounded-lg text-xs font-semibold uppercase tracking-wide shrink-0', statusClass(order.status)]">
                            {{ order.status }}
                        </span>
                    </div>

                    <!-- Order Items Preview -->
                    <div class="flex items-center gap-2 mt-4">
                        <div v-for="item in order.items.slice(0, 4)" :key="item.id"
                            class="w-12 h-12 bg-zinc-800 rounded-lg overflow-hidden shrink-0">
                            <img v-if="item.product?.image" :src="`/storage/${item.product.image}`"
                                :alt="item.product.name" class="w-full h-full object-cover" />
                            <div v-else class="w-full h-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-zinc-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <div v-if="order.items.length > 4"
                            class="w-12 h-12 bg-zinc-800 rounded-lg flex items-center justify-center shrink-0">
                            <span class="text-xs text-zinc-400 font-medium">+{{ order.items.length - 4 }}</span>
                        </div>
                        <div class="ml-auto text-right shrink-0">
                            <p class="text-xs text-zinc-500">{{ order.items.length }} {{ order.items.length === 1 ?
                                'item' : 'items' }}</p>
                            <p class="text-sm font-bold text-red-400 mt-0.5">₱{{
                                Number(order.total_amount).toLocaleString() }}</p>
                        </div>
                    </div>
                </Link>
            </div>

            <!-- Pagination -->
            <div v-if="orders.last_page > 1" class="mt-8 flex justify-center gap-2">
                <Link v-for="link in orders.links" :key="link.label" :href="link.url || ''"
                    :class="['px-3 py-2 rounded-lg text-sm transition-colors',
                        link.active ? 'bg-red-500 text-white font-medium' :
                            link.url ? 'bg-zinc-800 text-zinc-300 hover:bg-zinc-700' : 'bg-zinc-900 text-zinc-600 cursor-not-allowed']" v-html="link.label" />
            </div>
        </div>
    </MainLayout>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

defineProps<{
    orders: {
        data: any[];
        last_page: number;
        links: { url: string | null; label: string; active: boolean; }[];
    };
}>();

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