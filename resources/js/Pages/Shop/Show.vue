<template>
    <MainLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-sm text-zinc-500 mb-8">
                <Link :href="route('shop.index')" class="hover:text-white transition-colors">Shop</Link>
                <span>/</span>
                <Link :href="route('shop.index', { category: product.category.slug })"
                    class="hover:text-white transition-colors">
                    {{ product.category.name }}
                </Link>
                <span>/</span>
                <span class="text-zinc-300 truncate max-w-xs">{{ product.name }}</span>
            </nav>

            <!-- Product Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

                <!-- Image -->
                <div class="aspect-square bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden">
                    <img v-if="product.image" :src="`/storage/${product.image}`" :alt="product.name"
                        class="w-full h-full object-cover" />
                    <div v-else class="w-full h-full flex items-center justify-center">
                        <svg class="w-24 h-24 text-zinc-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

                <!-- Details -->
                <div class="flex flex-col">

                    <!-- Badges -->
                    <div class="flex items-center gap-2 mb-3">
                        <span v-if="product.is_featured"
                            class="px-2 py-0.5 bg-red-500/10 border border-red-500/30 text-red-400 text-xs font-bold rounded-md">
                            FEATURED
                        </span>
                        <span class="px-2 py-0.5 bg-zinc-800 text-zinc-400 text-xs rounded-md capitalize">
                            {{ product.condition }}
                        </span>
                        <span :class="['px-2 py-0.5 text-xs rounded-md', product.stock > 0
                            ? 'bg-green-500/10 border border-green-500/30 text-green-400'
                            : 'bg-zinc-800 text-zinc-500']">
                            {{ product.stock > 0 ? `${product.stock} in stock` : 'Out of stock' }}
                        </span>
                    </div>

                    <!-- Brand & Name -->
                    <p v-if="product.brand" class="text-sm text-zinc-500 mb-1">{{ product.brand }}</p>
                    <h1 class="font-display text-2xl lg:text-3xl font-bold text-white leading-tight">
                        {{ product.name }}
                    </h1>

                    <!-- Price -->
                    <p class="text-3xl font-bold text-red-400 mt-4">
                        ₱{{ Number(product.price).toLocaleString() }}
                    </p>

                    <!-- Description -->
                    <p v-if="product.description" class="text-zinc-400 text-sm leading-relaxed mt-4">
                        {{ product.description }}
                    </p>

                    <!-- Add to Cart -->
                    <div class="mt-8 space-y-3">
                        <div v-if="product.stock > 0" class="flex gap-3">
                            <!-- Quantity -->
                            <div
                                class="flex items-center bg-zinc-900 border border-zinc-700 rounded-xl overflow-hidden">
                                <button @click="quantity = Math.max(1, quantity - 1)"
                                    class="px-4 py-3 text-zinc-400 hover:text-white hover:bg-zinc-800 transition-colors text-lg font-medium">
                                    −
                                </button>
                                <span class="px-4 text-white font-medium min-w-[3rem] text-center">{{ quantity }}</span>
                                <button @click="quantity = Math.min(product.stock, quantity + 1)"
                                    class="px-4 py-3 text-zinc-400 hover:text-white hover:bg-zinc-800 transition-colors text-lg font-medium">
                                    +
                                </button>
                            </div>

                            <!-- Add to Cart Button -->
                            <button @click="addToCart" :disabled="adding"
                                class="flex-1 flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold py-3 px-6 rounded-xl transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                {{ adding ? 'Adding...' : 'Add to Cart' }}
                            </button>
                        </div>

                        <div v-else
                            class="flex items-center justify-center gap-2 bg-zinc-800 text-zinc-500 font-medium py-3 px-6 rounded-xl cursor-not-allowed">
                            Out of Stock
                        </div>

                        <!-- Login prompt -->
                        <p v-if="!$page.props.auth.user" class="text-xs text-zinc-500 text-center">
                            <Link :href="route('login')" class="text-red-400 hover:text-red-300">Login</Link>
                            to add items to your cart
                        </p>
                    </div>

                    <!-- Meta Info -->
                    <div class="mt-8 pt-8 border-t border-zinc-800 space-y-2">
                        <div class="flex items-center gap-3 text-sm">
                            <span class="text-zinc-500 w-24">Category</span>
                            <Link :href="route('shop.index', { category: product.category.slug })"
                                class="text-zinc-300 hover:text-red-400 transition-colors">
                                {{ product.category.name }}
                            </Link>
                        </div>
                        <div v-if="product.brand" class="flex items-center gap-3 text-sm">
                            <span class="text-zinc-500 w-24">Brand</span>
                            <span class="text-zinc-300">{{ product.brand }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <span class="text-zinc-500 w-24">Condition</span>
                            <span class="text-zinc-300 capitalize">{{ product.condition }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <div v-if="related.length" class="mt-16">
                <h2 class="font-display text-xl font-bold text-white mb-6">Related Products</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <Link v-for="item in related" :key="item.id" :href="route('shop.show', item.slug)"
                        class="group bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden hover:border-zinc-600 transition-all duration-200 hover:-translate-y-0.5">
                        <div class="aspect-square bg-zinc-800 overflow-hidden">
                            <img v-if="item.image" :src="`/storage/${item.image}`" :alt="item.name"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                            <div v-else class="w-full h-full flex items-center justify-center">
                                <svg class="w-10 h-10 text-zinc-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="p-3">
                            <p v-if="item.brand" class="text-xs text-zinc-500 mb-0.5">{{ item.brand }}</p>
                            <p class="text-sm font-medium text-zinc-100 line-clamp-2">{{ item.name }}</p>
                            <p class="text-red-400 font-bold mt-1 text-sm">₱{{ Number(item.price).toLocaleString() }}
                            </p>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps<{
    product: any;
    related: any[];
}>();

const quantity = ref(1);
const adding = ref(false);
const page = usePage();

const addToCart = () => {
    if (!page.props.auth.user) {
        router.visit(route('login'));
        return;
    }

    adding.value = true;
    router.post(route('cart.store'), {
        product_id: props.product.id,
        quantity: quantity.value,
    }, {
        onFinish: () => { adding.value = false; },
    });
};
</script>