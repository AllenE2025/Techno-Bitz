<template>
    <AdminLayout>
        <div class="space-y-6">

            <!-- Header -->
            <div>
                <h1 class="font-display text-3xl font-bold text-white">Orders</h1>
                <p class="text-zinc-400 mt-1 text-sm">{{ orders.total }} total orders</p>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap items-center gap-3">
                <input v-model="search" @input="applyFilters" type="text"
                    placeholder="Search by order ID, name, or phone..."
                    class="bg-zinc-900 border border-zinc-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-zinc-500 focus:outline-none focus:border-red-500 transition-colors w-72" />
                <select v-model="statusFilter" @change="applyFilters"
                    class="bg-zinc-900 border border-zinc-700 text-zinc-300 text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-red-500 transition-colors">
                    <option value="">All Statuses</option>
                    <option v-for="status in statuses" :key="status" :value="status" class="capitalize">
                        {{ status }}
                    </option>
                </select>
            </div>

            <!-- Table -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
                <div v-if="orders.data.length === 0" class="text-center py-16 text-zinc-500 text-sm">
                    No orders found
                </div>
                <table v-else class="w-full">
                    <thead>
                        <tr
                            class="border-b border-zinc-800 text-xs font-semibold text-zinc-500 uppercase tracking-wider">
                            <th class="text-left px-5 py-3">Order</th>
                            <th class="text-left px-5 py-3 hidden md:table-cell">Customer</th>
                            <th class="text-left px-5 py-3 hidden lg:table-cell">Date</th>
                            <th class="text-right px-5 py-3">Total</th>
                            <th class="text-center px-5 py-3">Status</th>
                            <th class="text-right px-5 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800">
                        <tr v-for="order in orders.data" :key="order.id" class="hover:bg-zinc-800/50 transition-colors">

                            <!-- Order ID -->
                            <td class="px-5 py-4">
                                <p class="text-sm font-semibold text-white">#{{ order.id }}</p>
                                <p class="text-xs text-zinc-500 mt-0.5">{{ order.items_count }} item{{ order.items_count
                                    !== 1 ? 's' : '' }}</p>
                            </td>

                            <!-- Customer -->
                            <td class="px-5 py-4 hidden md:table-cell">
                                <p class="text-sm text-zinc-300">{{ order.full_name }}</p>
                                <p class="text-xs text-zinc-500">{{ order.phone }}</p>
                            </td>

                            <!-- Date -->
                            <td class="px-5 py-4 hidden lg:table-cell">
                                <p class="text-sm text-zinc-400">{{ formatDate(order.created_at) }}</p>
                            </td>

                            <!-- Total -->
                            <td class="px-5 py-4 text-right">
                                <span class="text-sm font-bold text-red-400">
                                    ₱{{ Number(order.total_amount).toLocaleString() }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-5 py-4 text-center">
                                <span
                                    :class="['px-2.5 py-1 rounded-lg text-xs font-semibold uppercase tracking-wide', statusClass(order.status)]">
                                    {{ order.status }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-4 text-right">
                                <Link :href="route('admin.orders.show', order.id)"
                                    class="p-1.5 text-zinc-400 hover:text-white hover:bg-zinc-700 rounded-lg transition-colors inline-flex">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="orders.last_page > 1" class="flex justify-center gap-2">
                <Link v-for="link in orders.links" :key="link.label" :href="link.url || ''"
                    :class="['px-3 py-2 rounded-lg text-sm transition-colors',
                        link.active ? 'bg-red-500 text-white font-medium' :
                            link.url ? 'bg-zinc-800 text-zinc-300 hover:bg-zinc-700' : 'bg-zinc-900 text-zinc-600 cursor-not-allowed']" v-html="link.label" />
            </div>
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps<{
    orders: {
        data: any[];
        total: number;
        last_page: number;
        links: { url: string | null; label: string; active: boolean; }[];
    };
    statuses: string[];
    filters: {
        search?: string;
        status?: string;
    };
}>();

const search = ref(props.filters.search ?? '');
const statusFilter = ref(props.filters.status ?? '');

const applyFilters = () => {
    router.get(route('admin.orders.index'), {
        search: search.value || undefined,
        status: statusFilter.value || undefined,
    }, { preserveState: true, replace: true });
};

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