<template>
    <AdminLayout>
        <div class="space-y-6">

            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="font-display text-3xl font-bold text-white">Products</h1>
                    <p class="text-zinc-400 mt-1 text-sm">{{ products.total }} total products</p>
                </div>
                <Link :href="route('admin.products.create')"
                    class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2.5 rounded-xl transition-colors text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Product
                </Link>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap items-center gap-3">
                <input v-model="search" @input="applyFilters" type="text" placeholder="Search products..."
                    class="bg-zinc-900 border border-zinc-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-zinc-500 focus:outline-none focus:border-red-500 transition-colors w-64" />
                <select v-model="categoryFilter" @change="applyFilters"
                    class="bg-zinc-900 border border-zinc-700 text-zinc-300 text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-red-500 transition-colors">
                    <option value="">All Categories</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
                <select v-model="stockFilter" @change="applyFilters"
                    class="bg-zinc-900 border border-zinc-700 text-zinc-300 text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-red-500 transition-colors">
                    <option value="">All Stock</option>
                    <option value="in_stock">In Stock</option>
                    <option value="low_stock">Low Stock (≤5)</option>
                    <option value="out_of_stock">Out of Stock</option>
                </select>
            </div>

            <!-- Table -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
                <div v-if="products.data.length === 0" class="text-center py-16 text-zinc-500 text-sm">
                    No products found
                </div>
                <table v-else class="w-full">
                    <thead>
                        <tr
                            class="border-b border-zinc-800 text-xs font-semibold text-zinc-500 uppercase tracking-wider">
                            <th class="text-left px-5 py-3">Product</th>
                            <th class="text-left px-5 py-3 hidden md:table-cell">Category</th>
                            <th class="text-left px-5 py-3 hidden lg:table-cell">Brand</th>
                            <th class="text-right px-5 py-3">Price</th>
                            <th class="text-right px-5 py-3 hidden sm:table-cell">Stock</th>
                            <th class="text-center px-5 py-3 hidden lg:table-cell">Status</th>
                            <th class="text-right px-5 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800">
                        <tr v-for="product in products.data" :key="product.id"
                            class="hover:bg-zinc-800/50 transition-colors">

                            <!-- Product -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-zinc-800 rounded-lg overflow-hidden shrink-0">
                                        <img v-if="product.image" :src="`/storage/${product.image}`" :alt="product.name"
                                            class="w-full h-full object-cover" />
                                        <div v-else class="w-full h-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-zinc-600" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-zinc-100 truncate max-w-[180px]">{{
                                            product.name }}</p>
                                        <p class="text-xs text-zinc-500 capitalize">{{ product.condition }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="px-5 py-4 hidden md:table-cell">
                                <span class="text-sm text-zinc-400">{{ product.category?.name ?? '—' }}</span>
                            </td>

                            <!-- Brand -->
                            <td class="px-5 py-4 hidden lg:table-cell">
                                <span class="text-sm text-zinc-400">{{ product.brand ?? '—' }}</span>
                            </td>

                            <!-- Price -->
                            <td class="px-5 py-4 text-right">
                                <span class="text-sm font-semibold text-red-400">₱{{
                                    Number(product.price).toLocaleString() }}</span>
                            </td>

                            <!-- Stock -->
                            <td class="px-5 py-4 text-right hidden sm:table-cell">
                                <span :class="['text-xs font-bold px-2 py-1 rounded-lg',
                                    product.stock === 0 ? 'bg-red-500/10 text-red-400' :
                                        product.stock <= 5 ? 'bg-yellow-500/10 text-yellow-400' :
                                            'bg-green-500/10 text-green-400']">
                                    {{ product.stock }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-5 py-4 text-center hidden lg:table-cell">
                                <span
                                    :class="['text-xs font-semibold px-2 py-1 rounded-lg',
                                        product.is_active ? 'bg-green-500/10 text-green-400' : 'bg-zinc-700 text-zinc-500']">
                                    {{ product.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <Link :href="route('admin.products.edit', product.id)"
                                        class="p-1.5 text-zinc-400 hover:text-white hover:bg-zinc-700 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </Link>
                                    <button @click="deleteProduct(product)"
                                        class="p-1.5 text-zinc-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="products.last_page > 1" class="flex justify-center gap-2">
                <Link v-for="link in products.links" :key="link.label" :href="link.url || ''"
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
    products: {
        data: any[];
        total: number;
        last_page: number;
        links: { url: string | null; label: string; active: boolean; }[];
    };
    categories: any[];
    filters: {
        search?: string;
        category?: string;
        stock?: string;
    };
}>();

const search = ref(props.filters.search ?? '');
const categoryFilter = ref(props.filters.category ?? '');
const stockFilter = ref(props.filters.stock ?? '');

const applyFilters = () => {
    router.get(route('admin.products.index'), {
        search: search.value || undefined,
        category: categoryFilter.value || undefined,
        stock: stockFilter.value || undefined,
    }, { preserveState: true, replace: true });
};

const deleteProduct = (product: any) => {
    if (!confirm(`Delete "${product.name}"? This cannot be undone.`)) return;
    router.delete(route('admin.products.destroy', product.id));
};
</script>