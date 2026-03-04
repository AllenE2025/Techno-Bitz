<template>
    <MainLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="font-display text-3xl font-bold text-white">Shop</h1>
                <p class="text-zinc-400 mt-1 text-sm">{{ products.total }} products found</p>
            </div>

            <div class="flex gap-8">

                <!-- Sidebar Filters -->
                <aside class="hidden lg:block w-56 shrink-0">
                    <div class="sticky top-24 space-y-6">

                        <!-- Categories -->
                        <div>
                            <h3 class="text-xs font-semibold text-zinc-400 uppercase tracking-widest mb-3">Category</h3>
                            <div class="space-y-1">
                                <button @click="setFilter('category', '')"
                                    :class="['w-full text-left px-3 py-2 rounded-lg text-sm transition-colors',
                                        !filters.category ? 'bg-red-500/10 text-red-400 font-medium' : 'text-zinc-400 hover:text-white hover:bg-zinc-800']">
                                    All Categories
                                </button>
                                <button v-for="cat in categories" :key="cat.id" @click="setFilter('category', cat.slug)"
                                    :class="['w-full text-left px-3 py-2 rounded-lg text-sm transition-colors',
                                        filters.category === cat.slug ? 'bg-red-500/10 text-red-400 font-medium' : 'text-zinc-400 hover:text-white hover:bg-zinc-800']">
                                    {{ cat.name }}
                                </button>
                            </div>
                        </div>

                        <!-- Brands -->
                        <div v-if="brands.length">
                            <h3 class="text-xs font-semibold text-zinc-400 uppercase tracking-widest mb-3">Brand</h3>
                            <div class="space-y-1">
                                <button @click="setFilter('brand', '')"
                                    :class="['w-full text-left px-3 py-2 rounded-lg text-sm transition-colors',
                                        !filters.brand ? 'bg-red-500/10 text-red-400 font-medium' : 'text-zinc-400 hover:text-white hover:bg-zinc-800']">
                                    All Brands
                                </button>
                                <button v-for="brand in brands" :key="brand" @click="setFilter('brand', brand)"
                                    :class="['w-full text-left px-3 py-2 rounded-lg text-sm transition-colors',
                                        filters.brand === brand ? 'bg-red-500/10 text-red-400 font-medium' : 'text-zinc-400 hover:text-white hover:bg-zinc-800']">
                                    {{ brand }}
                                </button>
                            </div>
                        </div>

                        <!-- Condition -->
                        <div>
                            <h3 class="text-xs font-semibold text-zinc-400 uppercase tracking-widest mb-3">Condition
                            </h3>
                            <div class="space-y-1">
                                <button v-for="cond in ['', 'new', 'used', 'refurbished']" :key="cond"
                                    @click="setFilter('condition', cond)"
                                    :class="['w-full text-left px-3 py-2 rounded-lg text-sm transition-colors capitalize',
                                        filters.condition === cond ? 'bg-red-500/10 text-red-400 font-medium' : 'text-zinc-400 hover:text-white hover:bg-zinc-800']">
                                    {{ cond || 'All Conditions' }}
                                </button>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <h3 class="text-xs font-semibold text-zinc-400 uppercase tracking-widest mb-3">Price Range
                            </h3>
                            <div class="flex gap-2">
                                <input v-model="priceMin" type="number" placeholder="Min"
                                    class="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-3 py-2 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-red-500 transition-colors" />
                                <input v-model="priceMax" type="number" placeholder="Max"
                                    class="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-3 py-2 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-red-500 transition-colors" />
                            </div>
                            <button @click="applyPriceFilter"
                                class="mt-2 w-full bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-sm py-2 rounded-lg transition-colors">
                                Apply
                            </button>
                        </div>

                        <!-- Clear Filters -->
                        <button @click="clearFilters"
                            class="w-full text-xs text-zinc-500 hover:text-red-400 transition-colors py-2">
                            Clear all filters
                        </button>
                    </div>
                </aside>

                <!-- Main Content -->
                <div class="flex-1 min-w-0">

                    <!-- Top Bar -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-2">
                            <!-- Mobile filter toggle -->
                            <button @click="mobileFiltersOpen = !mobileFiltersOpen"
                                class="lg:hidden flex items-center gap-2 px-3 py-2 bg-zinc-800 text-zinc-300 rounded-lg text-sm hover:bg-zinc-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                                </svg>
                                Filters
                            </button>
                        </div>

                        <!-- Sort -->
                        <select v-model="sortValue" @change="setFilter('sort', sortValue)"
                            class="bg-zinc-900 border border-zinc-700 text-zinc-300 text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-red-500 transition-colors">
                            <option value="newest">Newest</option>
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                            <option value="name_asc">Name: A-Z</option>
                        </select>
                    </div>

                    <!-- Empty State -->
                    <div v-if="products.data.length === 0" class="text-center py-24">
                        <div class="w-16 h-16 bg-zinc-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <p class="text-zinc-400 font-medium">No products found</p>
                        <p class="text-zinc-600 text-sm mt-1">Try adjusting your filters</p>
                        <button @click="clearFilters"
                            class="mt-4 text-sm text-red-400 hover:text-red-300 transition-colors">
                            Clear filters
                        </button>
                    </div>

                    <!-- Product Grid -->
                    <div v-else class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                        <Link v-for="product in products.data" :key="product.id"
                            :href="route('shop.show', product.slug)"
                            class="group bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden hover:border-zinc-600 transition-all duration-200 hover:-translate-y-0.5">

                            <!-- Image -->
                            <div class="aspect-square bg-zinc-800 overflow-hidden relative">
                                <img v-if="product.image" :src="`/storage/${product.image}`" :alt="product.name"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                <div v-else class="w-full h-full flex items-center justify-center">
                                    <svg class="w-12 h-12 text-zinc-600" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>

                                <!-- Badges -->
                                <div class="absolute top-2 left-2 flex flex-col gap-1">
                                    <span v-if="product.is_featured"
                                        class="px-2 py-0.5 bg-red-500 text-white text-xs font-bold rounded-md">
                                        FEATURED
                                    </span>
                                    <span v-if="product.stock === 0"
                                        class="px-2 py-0.5 bg-zinc-900/90 text-zinc-400 text-xs rounded-md">
                                        OUT OF STOCK
                                    </span>
                                    <span v-if="product.condition !== 'new'"
                                        class="px-2 py-0.5 bg-zinc-900/90 text-zinc-300 text-xs rounded-md capitalize">
                                        {{ product.condition }}
                                    </span>
                                </div>
                            </div>

                            <!-- Info -->
                            <div class="p-3">
                                <p v-if="product.brand" class="text-xs text-zinc-500 mb-0.5">{{ product.brand }}</p>
                                <p class="text-sm font-medium text-zinc-100 line-clamp-2 leading-snug">{{ product.name
                                    }}</p>
                                <p class="text-red-400 font-bold mt-2 text-sm">₱{{
                                    Number(product.price).toLocaleString() }}</p>
                            </div>
                        </Link>
                    </div>

                    <!-- Pagination -->
                    <div v-if="products.last_page > 1" class="mt-8 flex justify-center gap-2">
                        <Link v-for="link in products.links" :key="link.label" :href="link.url || ''"
                            :class="['px-3 py-2 rounded-lg text-sm transition-colors',
                                link.active ? 'bg-red-500 text-white font-medium' :
                                    link.url ? 'bg-zinc-800 text-zinc-300 hover:bg-zinc-700' : 'bg-zinc-900 text-zinc-600 cursor-not-allowed']" v-html="link.label" />
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps<{
    products: {
        data: any[];
        total: number;
        last_page: number;
        links: { url: string | null; label: string; active: boolean; }[];
    };
    categories: any[];
    brands: string[];
    filters: {
        search?: string;
        category?: string;
        brand?: string;
        condition?: string;
        min_price?: string;
        max_price?: string;
        sort?: string;
    };
}>();

const sortValue = ref(props.filters.sort || 'newest');
const priceMin = ref(props.filters.min_price || '');
const priceMax = ref(props.filters.max_price || '');
const mobileFiltersOpen = ref(false);

const setFilter = (key: string, value: string) => {
    router.get(route('shop.index'), {
        ...props.filters,
        [key]: value || undefined,
    }, { preserveState: true, replace: true });
};

const applyPriceFilter = () => {
    router.get(route('shop.index'), {
        ...props.filters,
        min_price: priceMin.value || undefined,
        max_price: priceMax.value || undefined,
    }, { preserveState: true, replace: true });
};

const clearFilters = () => {
    priceMin.value = '';
    priceMax.value = '';
    router.get(route('shop.index'), {}, { replace: true });
};
</script>