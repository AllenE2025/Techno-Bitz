<template>
    <div class="min-h-screen bg-zinc-950 text-zinc-100 font-body">

        <!-- Navbar -->
        <nav class="sticky top-0 z-50 bg-zinc-950/90 backdrop-blur border-b border-zinc-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">

                    <!-- Logo -->
                    <Link :href="route('home')" class="flex items-center gap-2 group">
                        <div class="w-8 h-8 bg-red-500 rounded flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="font-display text-xl font-bold tracking-tight">
                            TECHNO<span class="text-red-500">BITZ</span>
                        </span>
                    </Link>

                    <!-- Center Nav Links -->
                    <div class="hidden md:flex items-center gap-6">
                        <Link :href="route('shop.index')"
                            class="text-sm font-medium text-zinc-400 hover:text-white transition-colors"
                            :class="{ 'text-white': $page.url.startsWith('/shop') }">
                            Shop
                        </Link>
                        <Link :href="route('shop.index', { category: 'gaming' })"
                            class="text-sm font-medium text-zinc-400 hover:text-white transition-colors">
                            Gaming
                        </Link>
                        <Link :href="route('shop.index', { category: 'peripherals' })"
                            class="text-sm font-medium text-zinc-400 hover:text-white transition-colors">
                            Peripherals
                        </Link>
                        <Link :href="route('shop.index', { category: 'components' })"
                            class="text-sm font-medium text-zinc-400 hover:text-white transition-colors">
                            Components
                        </Link>
                    </div>

                    <!-- Right Side -->
                    <div class="flex items-center gap-3">

                        <!-- Search -->
                        <button @click="searchOpen = !searchOpen"
                            class="p-2 text-zinc-400 hover:text-white transition-colors rounded-lg hover:bg-zinc-800">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>

                        <!-- Cart -->
                        <Link v-if="$page.props.auth.user" :href="route('cart.index')"
                            class="relative p-2 text-zinc-400 hover:text-white transition-colors rounded-lg hover:bg-zinc-800">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span v-if="cartCount > 0"
                                class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">
                                {{ cartCount > 9 ? '9+' : cartCount }}
                            </span>
                        </Link>

                        <!-- Auth -->
                        <template v-if="$page.props.auth.user">
                            <div class="relative" ref="userMenuRef">
                                <button @click="userMenuOpen = !userMenuOpen"
                                    class="flex items-center gap-2 p-2 text-zinc-400 hover:text-white transition-colors rounded-lg hover:bg-zinc-800">
                                    <div
                                        class="w-7 h-7 bg-red-500/20 border border-red-500/30 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-bold text-red-400">
                                            {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                                        </span>
                                    </div>
                                </button>

                                <!-- Dropdown -->
                                <div v-if="userMenuOpen"
                                    class="absolute right-0 mt-2 w-48 bg-zinc-900 border border-zinc-700 rounded-xl shadow-xl overflow-hidden">
                                    <div class="px-4 py-3 border-b border-zinc-800">
                                        <p class="text-sm font-medium text-white truncate">{{ $page.props.auth.user.name
                                            }}</p>
                                        <p class="text-xs text-zinc-500 truncate">{{ $page.props.auth.user.email }}</p>
                                    </div>
                                    <div class="py-1">
                                        <Link :href="route('orders.index')"
                                            class="flex items-center gap-2 px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-800 hover:text-white transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            My Orders
                                        </Link>
                                        <Link :href="route('profile.edit')"
                                            class="flex items-center gap-2 px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-800 hover:text-white transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            Profile
                                        </Link>
                                        <Link v-if="$page.props.auth.user.role === 'admin'"
                                            :href="route('admin.dashboard')"
                                            class="flex items-center gap-2 px-4 py-2 text-sm text-red-400 hover:bg-zinc-800 hover:text-red-300 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Admin Panel
                                        </Link>
                                        <hr class="border-zinc-800 my-1" />
                                        <Link :href="route('logout')" method="post" as="button"
                                            class="flex items-center gap-2 w-full px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-800 hover:text-white transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Logout
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template v-else>
                            <Link :href="route('login')"
                                class="text-sm font-medium text-zinc-400 hover:text-white transition-colors px-3 py-1.5">
                                Login
                            </Link>
                            <Link :href="route('register')"
                                class="text-sm font-medium bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg transition-colors">
                                Register
                            </Link>
                        </template>

                        <!-- Mobile Menu Button -->
                        <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="md:hidden p-2 text-zinc-400 hover:text-white transition-colors rounded-lg hover:bg-zinc-800">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div v-if="searchOpen" class="border-t border-zinc-800 bg-zinc-950 px-4 py-3">
                <form @submit.prevent="submitSearch" class="max-w-2xl mx-auto relative">
                    <input v-model="searchQuery" type="text" placeholder="Search products, brands..."
                        class="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-4 py-2.5 pr-10 text-sm text-white placeholder-zinc-500 focus:outline-none focus:border-red-500 transition-colors"
                        autofocus />
                    <button type="submit"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-red-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Mobile Menu -->
            <div v-if="mobileMenuOpen" class="md:hidden border-t border-zinc-800 bg-zinc-950 px-4 py-3 space-y-1">
                <Link :href="route('shop.index')"
                    class="block px-3 py-2 text-sm text-zinc-300 hover:text-white hover:bg-zinc-800 rounded-lg transition-colors">
                    Shop
                </Link>
                <Link :href="route('shop.index', { category: 'gaming' })"
                    class="block px-3 py-2 text-sm text-zinc-300 hover:text-white hover:bg-zinc-800 rounded-lg transition-colors">
                    Gaming
                </Link>
                <Link :href="route('shop.index', { category: 'peripherals' })"
                    class="block px-3 py-2 text-sm text-zinc-300 hover:text-white hover:bg-zinc-800 rounded-lg transition-colors">
                    Peripherals
                </Link>
                <Link :href="route('shop.index', { category: 'components' })"
                    class="block px-3 py-2 text-sm text-zinc-300 hover:text-white hover:bg-zinc-800 rounded-lg transition-colors">
                    Components
                </Link>
            </div>
        </nav>

        <!-- Flash Messages -->
        <div v-if="$page.props.flash?.success || $page.props.flash?.error"
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
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
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="mt-24 border-t border-zinc-800 bg-zinc-950">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="flex flex-col md:flex-row justify-between items-start gap-8">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-7 h-7 bg-red-500 rounded flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <span class="font-display text-lg font-bold">TECHNO<span
                                    class="text-red-500">BITZ</span></span>
                        </div>
                        <p class="text-sm text-zinc-500 max-w-xs">Your one-stop shop for the latest tech, gaming gear,
                            and
                            peripherals.</p>
                    </div>
                    <div class="flex gap-12 text-sm">
                        <div>
                            <p class="font-medium text-zinc-300 mb-3">Shop</p>
                            <div class="space-y-2 text-zinc-500">
                                <Link :href="route('shop.index')" class="block hover:text-white transition-colors">All
                                    Products
                                </Link>
                                <Link :href="route('shop.index', { category: 'gaming' })"
                                    class="block hover:text-white transition-colors">Gaming</Link>
                                <Link :href="route('shop.index', { category: 'peripherals' })"
                                    class="block hover:text-white transition-colors">Peripherals</Link>
                            </div>
                        </div>
                        <div>
                            <p class="font-medium text-zinc-300 mb-3">Account</p>
                            <div class="space-y-2 text-zinc-500">
                                <Link v-if="$page.props.auth.user" :href="route('orders.index')"
                                    class="block hover:text-white transition-colors">My Orders</Link>
                                <Link v-if="$page.props.auth.user" :href="route('profile.edit')"
                                    class="block hover:text-white transition-colors">Profile</Link>
                                <Link v-else :href="route('login')" class="block hover:text-white transition-colors">
                                    Login
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-8 pt-8 border-t border-zinc-800 text-center text-xs text-zinc-600">
                    © {{ new Date().getFullYear() }} TechnoBitz. All rights reserved.
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const searchOpen = ref(false);
const searchQuery = ref('');
const userMenuOpen = ref(false);
const mobileMenuOpen = ref(false);
const userMenuRef = ref<HTMLElement | null>(null);

const cartCount = ref(0);

const submitSearch = () => {
    if (searchQuery.value.trim()) {
        window.location.href = route('shop.index') + '?search=' + encodeURIComponent(searchQuery.value);
        searchOpen.value = false;
        searchQuery.value = '';
    }
};

// Close user menu when clicking outside
const handleClickOutside = (e: MouseEvent) => {
    if (userMenuRef.value && !userMenuRef.value.contains(e.target as Node)) {
        userMenuOpen.value = false;
    }
};

onMounted(() => document.addEventListener('click', handleClickOutside));
onUnmounted(() => document.removeEventListener('click', handleClickOutside));
</script>