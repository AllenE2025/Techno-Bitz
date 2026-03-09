<template>
    <AdminLayout>
        <div class="space-y-8">

            <!-- Header -->
            <div>
                <h1 class="font-display text-3xl font-bold text-white">Dashboard</h1>
                <p class="text-zinc-400 mt-1 text-sm">Welcome back, {{ $page.props.auth.user.name }}</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                <div v-for="stat in stats" :key="stat.label"
                    class="bg-zinc-900 border border-zinc-800 rounded-xl p-5 hover:border-zinc-700 transition-colors">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-zinc-500 uppercase tracking-widest">{{ stat.label }}</p>
                        <div :class="['w-8 h-8 rounded-lg flex items-center justify-center', stat.iconBg]">
                            <component :is="stat.icon" class="w-4 h-4" :class="stat.iconColor" />
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-white">{{ stat.value }}</p>
                    <p class="text-xs text-zinc-500 mt-1">{{ stat.sub }}</p>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800">
                    <h2 class="font-semibold text-white">Recent Orders</h2>
                    <Link :href="route('admin.orders.index')"
                        class="text-xs text-red-400 hover:text-red-300 transition-colors">
                        View all →
                    </Link>
                </div>
                <div v-if="recentOrders.length === 0" class="px-6 py-12 text-center text-zinc-500 text-sm">
                    No orders yet
                </div>
                <div v-else class="divide-y divide-zinc-800">
                    <div v-for="order in recentOrders" :key="order.id"
                        class="flex items-center justify-between px-6 py-4 hover:bg-zinc-800/50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div>
                                <p class="text-sm font-medium text-white">Order #{{ order.id }}</p>
                                <p class="text-xs text-zinc-500">{{ order.full_name }} · {{ formatDate(order.created_at)
                                    }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span
                                :class="['px-2.5 py-1 rounded-lg text-xs font-semibold uppercase tracking-wide', statusClass(order.status)]">
                                {{ order.status }}
                            </span>
                            <p class="text-sm font-bold text-red-400 w-24 text-right">
                                ₱{{ Number(order.total_amount).toLocaleString() }}
                            </p>
                            <Link :href="route('admin.orders.show', order.id)"
                                class="text-zinc-500 hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Low Stock -->
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800">
                        <h2 class="font-semibold text-white">Low Stock</h2>
                        <Link :href="route('admin.products.index')"
                            class="text-xs text-red-400 hover:text-red-300 transition-colors">
                            Manage →
                        </Link>
                    </div>
                    <div v-if="lowStockProducts.length === 0" class="px-6 py-12 text-center text-zinc-500 text-sm">
                        All products are well stocked
                    </div>
                    <div v-else class="divide-y divide-zinc-800">
                        <div v-for="product in lowStockProducts" :key="product.id"
                            class="flex items-center justify-between px-6 py-3 hover:bg-zinc-800/50 transition-colors">
                            <p class="text-sm text-zinc-300 truncate max-w-[200px]">{{ product.name }}</p>
                            <span
                                :class="['text-xs font-bold px-2 py-1 rounded-lg',
                                    product.stock === 0 ? 'bg-red-500/10 text-red-400' : 'bg-yellow-500/10 text-yellow-400']">
                                {{ product.stock === 0 ? 'Out of stock' : `${product.stock} left` }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6">
                    <h2 class="font-semibold text-white mb-4">Quick Actions</h2>
                    <div class="grid grid-cols-2 gap-3">
                        <Link :href="route('admin.products.create')"
                            class="flex items-center gap-3 p-4 bg-zinc-800 hover:bg-zinc-700 rounded-xl transition-colors group">
                            <div class="w-8 h-8 bg-red-500/10 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-zinc-300 group-hover:text-white transition-colors">Add
                                Product</span>
                        </Link>
                        <Link :href="route('admin.categories.create')"
                            class="flex items-center gap-3 p-4 bg-zinc-800 hover:bg-zinc-700 rounded-xl transition-colors group">
                            <div class="w-8 h-8 bg-blue-500/10 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-zinc-300 group-hover:text-white transition-colors">Add
                                Category</span>
                        </Link>
                        <Link :href="route('admin.orders.index')"
                            class="flex items-center gap-3 p-4 bg-zinc-800 hover:bg-zinc-700 rounded-xl transition-colors group">
                            <div class="w-8 h-8 bg-green-500/10 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <span
                                class="text-sm font-medium text-zinc-300 group-hover:text-white transition-colors">View
                                Orders</span>
                        </Link>
                        <Link :href="route('admin.products.index')"
                            class="flex items-center gap-3 p-4 bg-zinc-800 hover:bg-zinc-700 rounded-xl transition-colors group">
                            <div class="w-8 h-8 bg-purple-500/10 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-purple-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-zinc-300 group-hover:text-white transition-colors">All
                                Products</span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps<{
    totalOrders: number;
    totalRevenue: number;
    totalProducts: number;
    totalCategories: number;
    recentOrders: any[];
    lowStockProducts: any[];
}>();

const stats = computed(() => [
    {
        label: 'Total Orders',
        value: props.totalOrders,
        sub: 'All time orders',
        iconBg: 'bg-red-500/10',
        iconColor: 'text-red-400',
        icon: 'svg-orders',
    },
    {
        label: 'Total Revenue',
        value: `₱${Number(props.totalRevenue).toLocaleString()}`,
        sub: 'From delivered orders',
        iconBg: 'bg-green-500/10',
        iconColor: 'text-green-400',
        icon: 'svg-revenue',
    },
    {
        label: 'Products',
        value: props.totalProducts,
        sub: 'Active listings',
        iconBg: 'bg-blue-500/10',
        iconColor: 'text-blue-400',
        icon: 'svg-products',
    },
    {
        label: 'Categories',
        value: props.totalCategories,
        sub: 'Product categories',
        iconBg: 'bg-purple-500/10',
        iconColor: 'text-purple-400',
        icon: 'svg-categories',
    },
]);

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