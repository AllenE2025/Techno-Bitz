<template>
    <AdminLayout>
        <div class="max-w-4xl space-y-6">

            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link :href="route('admin.orders.index')"
                        class="p-2 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </Link>
                    <div>
                        <h1 class="font-display text-3xl font-bold text-white">Order #{{ order.id }}</h1>
                        <p class="text-zinc-400 mt-1 text-sm">Placed on {{ formatDate(order.created_at) }}</p>
                    </div>
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
                                <div class="w-14 h-14 bg-zinc-800 rounded-lg overflow-hidden shrink-0">
                                    <img v-if="item.product?.image" :src="`/storage/${item.product.image}`"
                                        :alt="item.product?.name" class="w-full h-full object-cover" />
                                    <div v-else class="w-full h-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-zinc-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-zinc-100">
                                        {{ item.product ? item.product.name : 'Product no longer available' }}
                                    </p>
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

                    <!-- Customer & Shipping -->
                    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5">
                        <h2 class="font-semibold text-white mb-4">Customer & Shipping</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div class="space-y-2">
                                <div class="flex gap-3">
                                    <span class="text-zinc-500 w-20 shrink-0">Name</span>
                                    <span class="text-zinc-300">{{ order.full_name }}</span>
                                </div>
                                <div class="flex gap-3">
                                    <span class="text-zinc-500 w-20 shrink-0">Phone</span>
                                    <span class="text-zinc-300">{{ order.phone }}</span>
                                </div>
                                <div class="flex gap-3">
                                    <span class="text-zinc-500 w-20 shrink-0">Email</span>
                                    <span class="text-zinc-300">{{ order.user?.email ?? '—' }}</span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex gap-3">
                                    <span class="text-zinc-500 w-20 shrink-0">Address</span>
                                    <span class="text-zinc-300">{{ order.address_line }}</span>
                                </div>
                                <div class="flex gap-3">
                                    <span class="text-zinc-500 w-20 shrink-0">City</span>
                                    <span class="text-zinc-300">{{ order.city }}</span>
                                </div>
                                <div class="flex gap-3">
                                    <span class="text-zinc-500 w-20 shrink-0">Province</span>
                                    <span class="text-zinc-300">{{ order.province }} {{ order.postal_code }}</span>
                                </div>
                            </div>
                        </div>
                        <div v-if="order.notes" class="mt-4 pt-4 border-t border-zinc-800">
                            <p class="text-xs text-zinc-500 mb-1">Customer Notes</p>
                            <p class="text-sm text-zinc-300">{{ order.notes }}</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-5">

                    <!-- Update Status -->
                    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5">
                        <h2 class="font-semibold text-white mb-4">Update Status</h2>
                        <div class="space-y-2">
                            <button v-for="status in statuses" :key="status" @click="updateStatus(status)"
                                :disabled="order.status === status || updatingStatus"
                                :class="['w-full text-left px-4 py-2.5 rounded-xl text-sm font-medium capitalize transition-all border',
                                    order.status === status
                                        ? 'bg-red-500/10 border-red-500/30 text-red-400 cursor-default'
                                        : 'bg-zinc-800 border-zinc-700 text-zinc-400 hover:text-white hover:border-zinc-500 disabled:opacity-40 disabled:cursor-not-allowed']">
                                <div class="flex items-center justify-between">
                                    <span>{{ status }}</span>
                                    <svg v-if="order.status === status" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-5">
                        <h2 class="font-semibold text-white mb-4">Payment</h2>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between text-zinc-400">
                                <span>Method</span>
                                <span class="uppercase font-medium text-zinc-300">{{ order.payment_method }}</span>
                            </div>
                            <div class="flex justify-between text-zinc-400">
                                <span>Status</span>
                                <span :class="['font-medium capitalize',
                                    order.payment_status === 'paid' ? 'text-green-400' :
                                        order.payment_status === 'failed' ? 'text-red-400' : 'text-yellow-400']">
                                    {{ order.payment_status }}
                                </span>
                            </div>
                            <div class="border-t border-zinc-800 pt-2 mt-2 flex justify-between font-bold text-white">
                                <span>Total</span>
                                <span class="text-red-400">₱{{ Number(order.total_amount).toLocaleString() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps<{
    order: any;
    statuses: string[];
}>();

const updatingStatus = ref(false);

const updateStatus = (status: string) => {
    if (props.order.status === status) return;
    updatingStatus.value = true;
    router.patch(route('admin.orders.updateStatus', props.order.id), { status }, {
        preserveScroll: true,
        onFinish: () => { updatingStatus.value = false; },
    });
};

const formatDate = (date: string) =>
    new Date(date).toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' });

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