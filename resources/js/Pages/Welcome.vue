<template>

    <Head title="TechnoBitz — Tech & Gaming Store" />
    <MainLayout>
        <div>

            <!-- Hero Section -->
            <section class="relative overflow-hidden bg-zinc-950 border-b border-zinc-800">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-red-500/5 via-transparent to-transparent pointer-events-none" />
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
                    <div class="max-w-2xl">
                        <div
                            class="inline-flex items-center gap-2 bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-semibold px-3 py-1.5 rounded-full mb-6">
                            <span class="w-1.5 h-1.5 bg-red-400 rounded-full animate-pulse"></span>
                            New arrivals every week
                        </div>
                        <h1 class="font-display text-5xl lg:text-6xl font-bold text-white leading-tight tracking-tight">
                            Level Up Your
                            <span class="text-red-500"> Setup</span>
                        </h1>
                        <p class="mt-5 text-lg text-zinc-400 leading-relaxed">
                            Shop the latest tech, gaming gear, and peripherals. From consoles to components — everything
                            you need in one place.
                        </p>
                        <div class="mt-8 flex items-center gap-4">
                            <Link :href="route('shop.index')"
                                class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-3 rounded-xl transition-colors">
                                Shop Now
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </Link>
                            <Link :href="route('shop.index')"
                                class="inline-flex items-center gap-2 text-zinc-400 hover:text-white font-medium px-6 py-3 rounded-xl border border-zinc-700 hover:border-zinc-500 transition-colors">
                                Browse Catalog
                            </Link>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Categories Section -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="font-display text-2xl font-bold text-white">Shop by Category</h2>
                    <Link :href="route('shop.index')" class="text-sm text-red-400 hover:text-red-300 transition-colors">
                        View all →
                    </Link>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <Link v-for="cat in categories" :key="cat.id" :href="route('shop.index', { category: cat.slug })"
                        class="group relative bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden hover:border-zinc-600 transition-all duration-200 hover:-translate-y-0.5 aspect-video flex items-center justify-center">
                        <img v-if="cat.image" :src="`/storage/${cat.image}`" :alt="cat.name"
                            class="absolute inset-0 w-full h-full object-cover opacity-40 group-hover:opacity-60 transition-opacity" />
                        <div class="relative text-center px-4">
                            <p class="font-display font-bold text-white text-lg">{{ cat.name }}</p>
                            <p v-if="cat.description" class="text-zinc-400 text-xs mt-1 line-clamp-1">{{ cat.description
                                }}</p>
                        </div>
                    </Link>
                </div>
            </section>

            <!-- Featured Products -->
            <section v-if="featuredProducts && featuredProducts.length" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-16">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="font-display text-2xl font-bold text-white">Featured Products</h2>
                    <Link :href="route('shop.index')" class="text-sm text-red-400 hover:text-red-300 transition-colors">
                        View all →
                    </Link>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <Link v-for="product in featuredProducts" :key="product.id" :href="route('shop.show', product.slug)"
                        class="group bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden hover:border-zinc-600 transition-all duration-200 hover:-translate-y-0.5">
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
                            <div class="absolute top-2 left-2">
                                <span class="px-2 py-0.5 bg-red-500 text-white text-xs font-bold rounded-md">
                                    FEATURED
                                </span>
                            </div>
                        </div>
                        <div class="p-3">
                            <p v-if="product.brand" class="text-xs text-zinc-500 mb-0.5">{{ product.brand }}</p>
                            <p class="text-sm font-medium text-zinc-100 line-clamp-2 leading-snug">{{ product.name }}
                            </p>
                            <p class="text-red-400 font-bold mt-2 text-sm">₱{{ Number(product.price).toLocaleString() }}
                            </p>
                        </div>
                    </Link>
                </div>
            </section>

            <!-- Why TechnoBitz -->
            <section class="border-t border-zinc-800 bg-zinc-900/50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    <h2 class="font-display text-2xl font-bold text-white text-center mb-12">Why TechnoBitz?</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div v-for="feature in features" :key="feature.title" class="text-center">
                            <div
                                :class="['w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4', feature.iconBg]">
                                <span v-html="feature.icon" :class="feature.iconColor"></span>
                            </div>
                            <h3 class="font-semibold text-white mb-2">{{ feature.title }}</h3>
                            <p class="text-sm text-zinc-400 leading-relaxed">{{ feature.description }}</p>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </MainLayout>
</template>

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion: string;
    phpVersion: string;
    categories?: any[];
    featuredProducts?: any[];
}>();

const features = [
    {
        title: 'Cash on Delivery',
        description: 'Pay when your order arrives — no need for a card or online payment.',
        iconBg: 'bg-green-500/10',
        iconColor: 'text-green-400',
        icon: `<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>`,
    },
    {
        title: 'Wide Selection',
        description: 'From gaming peripherals to PC components — find everything tech in one store.',
        iconBg: 'bg-blue-500/10',
        iconColor: 'text-blue-400',
        icon: `<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>`,
    },
    {
        title: 'Order Tracking',
        description: 'Track your order from confirmation to delivery right from your account.',
        iconBg: 'bg-red-500/10',
        iconColor: 'text-red-400',
        icon: `<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>`,
    },
];
</script>