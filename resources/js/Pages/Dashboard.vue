<template>

    <Head title="My Account" />
    <MainLayout>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Header -->
            <div class="mb-8">
                <h1 class="font-display text-3xl font-bold text-white">
                    Welcome back, {{ $page.props.auth.user.name }}
                </h1>
                <p class="text-zinc-400 mt-1 text-sm">Here's a summary of your account</p>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5">
                    <p class="text-xs font-semibold text-zinc-500 uppercase tracking-widest mb-2">Total Orders</p>
                    <p class="text-3xl font-bold text-white">{{ stats.totalOrders }}</p>
                </div>
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5">
                    <p class="text-xs font-semibold text-zinc-500 uppercase tracking-widest mb-2">Pending</p>
                    <p class="text-3xl font-bold text-yellow-400">{{ stats.pendingOrders }}</p>
                </div>
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5">
                    <p class="text-xs font-semibold text-zinc-500 uppercase tracking-widest mb-2">Delivered</p>
                    <p class="text-3xl font-bold text-green-400">{{ stats.deliveredOrders }}</p>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden mb-6">
                <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800">
                    <h2 class="font-semibold text-white">Recent Orders</h2>
                    <Link :href="route('orders.index')"
                        class="text-xs text-red-400 hover:text-red-300 transition-colors">
                        View all →
                    </Link>
                </div>

                <div v-if="recentOrders.length === 0" class="px-6 py-12 text-center">
                    <p class="text-zinc-500 text-sm">You haven't placed any orders yet</p>
                    <Link :href="route('shop.index')"
                        class="inline-flex items-center gap-2 mt-4 bg-red-500 hover:bg-red-600 text-white font-semibold px-5 py-2.5 rounded-xl transition-colors text-sm">
                        Start Shopping
                    </Link>
                </div>

                <div v-else class="divide-y divide-zinc-800">
                    <Link v-for="order in recentOrders" :key="order.id" :href="route('orders.show', order.id)"
                        class="flex items-center justify-between px-6 py-4 hover:bg-zinc-800/50 transition-colors">
                        <div>
                            <p class="text-sm font-medium text-white">Order #{{ order.id }}</p>
                            <p class="text-xs text-zinc-500 mt-0.5">{{ formatDate(order.created_at) }}</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <span
                                :class="['px-2.5 py-1 rounded-lg text-xs font-semibold uppercase tracking-wide', statusClass(order.status)]">
                                {{ order.status }}
                            </span>
                            <p class="text-sm font-bold text-red-400">
                                ₱{{ Number(order.total_amount).toLocaleString() }}
                            </p>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <Link :href="route('shop.index')"
                    class="flex items-center gap-3 p-4 bg-zinc-900 border border-zinc-800 hover:border-zinc-600 rounded-xl transition-all group">
                    <div class="w-9 h-9 bg-red-500/10 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-zinc-300 group-hover:text-white transition-colors">Browse
                        Shop</span>
                </Link>

                <Link :href="route('orders.index')"
                    class="flex items-center gap-3 p-4 bg-zinc-900 border border-zinc-800 hover:border-zinc-600 rounded-xl transition-all group">
                    <div class="w-9 h-9 bg-blue-500/10 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-zinc-300 group-hover:text-white transition-colors">My
                        Orders</span>
                </Link>

                <Link :href="route('profile.edit')"
                    class="flex items-center gap-3 p-4 bg-zinc-900 border border-zinc-800 hover:border-zinc-600 rounded-xl transition-all group">
                    <div class="w-9 h-9 bg-purple-500/10 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-zinc-300 group-hover:text-white transition-colors">Edit
                        Profile</span>
                </Link>
            </div>
        </div>
    </MainLayout>
</template>

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

defineProps<{
    stats: {
        totalOrders: number;
        pendingOrders: number;
        deliveredOrders: number;
    };
    recentOrders: any[];
}>();

const formatDate = (date: string) =>
    new Date(date).toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' });

const statusClass = (status: string) => {
    const map: Record<string, string> = {
        pending: 'bg-yellow-500/10 text-yellow-400',
        confirmed: 'bg-blue-500/10 text-blue-400',
        processing: 'bg-purple-500/10 text-purple-400',
        shipped: 'bg-cyan-500/10 text-cyan-400',
        delivered: 'bg-green-500/10 text-green-400',
        cancelled: 'bg-zinc-500/10 text-zinc-400',
    };
    return map[status] ?? 'bg-zinc-800 text-zinc-400';
};
</script>