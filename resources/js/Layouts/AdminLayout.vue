<template>
    <div class="min-h-screen bg-zinc-950 text-zinc-100 font-body flex">

        <!-- Sidebar -->
        <aside class="hidden lg:flex flex-col w-60 bg-zinc-900 border-r border-zinc-800 shrink-0 sticky top-0 h-screen">

            <!-- Logo -->
            <div class="px-5 py-5 border-b border-zinc-800">
                <Link :href="route('admin.dashboard')" class="flex items-center gap-2">
                    <div class="w-7 h-7 bg-red-500 rounded flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="font-display text-base font-bold">TECHNO<span class="text-red-500">BITZ</span></span>
                </Link>
                <p class="text-xs text-zinc-500 mt-1 ml-9">Admin Panel</p>
            </div>

            <!-- Nav -->
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <Link v-for="item in navItems" :key="item.label" :href="item.href" :class="['flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors',
                    isActive(item.href)
                        ? 'bg-red-500/10 text-red-400 border border-red-500/20'
                        : 'text-zinc-400 hover:text-white hover:bg-zinc-800']">
                    <span v-html="item.icon" class="w-4 h-4 shrink-0"></span>
                    {{ item.label }}
                </Link>
            </nav>

            <!-- Bottom -->
            <div class="px-3 py-4 border-t border-zinc-800 space-y-1">
                <Link :href="route('home')"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-zinc-400 hover:text-white hover:bg-zinc-800 transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    View Store
                </Link>
                <Link :href="route('logout')" method="post" as="button"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-zinc-400 hover:text-white hover:bg-zinc-800 transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </Link>
            </div>
        </aside>

        <!-- Main -->
        <div class="flex-1 min-w-0 flex flex-col">

            <!-- Top Bar (mobile) -->
            <header
                class="lg:hidden sticky top-0 z-50 bg-zinc-900 border-b border-zinc-800 px-4 py-3 flex items-center justify-between">
                <Link :href="route('admin.dashboard')" class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-red-500 rounded flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="font-display text-sm font-bold">TECHNO<span class="text-red-500">BITZ</span> <span
                            class="text-zinc-500 font-normal">Admin</span></span>
                </Link>
                <button @click="mobileNavOpen = !mobileNavOpen"
                    class="p-2 text-zinc-400 hover:text-white rounded-lg hover:bg-zinc-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </header>

            <!-- Mobile Nav Drawer -->
            <div v-if="mobileNavOpen" class="lg:hidden fixed inset-0 z-40 bg-zinc-950/80 backdrop-blur"
                @click="mobileNavOpen = false">
                <div class="w-60 h-full bg-zinc-900 border-r border-zinc-800 p-4 space-y-1" @click.stop>
                    <Link v-for="item in navItems" :key="item.label" :href="item.href" @click="mobileNavOpen = false"
                        :class="['flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors',
                            isActive(item.href)
                                ? 'bg-red-500/10 text-red-400'
                                : 'text-zinc-400 hover:text-white hover:bg-zinc-800']">
                        <span v-html="item.icon" class="w-4 h-4 shrink-0"></span>
                        {{ item.label }}
                    </Link>
                </div>
            </div>

            <!-- Flash Messages -->
            <div v-if="$page.props.flash?.success || $page.props.flash?.error" class="px-6 pt-5">
                <div v-if="$page.props.flash?.success"
                    class="flex items-center gap-3 bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3 rounded-xl text-sm">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ $page.props.flash.success }}
                </div>
                <div v-if="$page.props.flash?.error"
                    class="flex items-center gap-3 bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl text-sm">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $page.props.flash.error }}
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const mobileNavOpen = ref(false);
const page = usePage();

const isActive = (href: string) => page.url.startsWith(href.replace(window.location.origin, ''));

const navItems = [
    {
        label: 'Dashboard',
        href: route('admin.dashboard'),
        icon: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>`,
    },
    {
        label: 'Products',
        href: route('admin.products.index'),
        icon: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>`,
    },
    {
        label: 'Categories',
        href: route('admin.categories.index'),
        icon: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>`,
    },
    {
        label: 'Orders',
        href: route('admin.orders.index'),
        icon: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>`,
    },
];
</script>