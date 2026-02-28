<script setup lang="ts">
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const mobileMenuOpen = ref(false);
</script>

<template>
    <div class="min-h-screen bg-gray-50">

        <!-- NAVBAR -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">

                    <!-- LEFT SIDE -->
                    <div class="flex items-center space-x-8">

                        <!-- Logo -->
                        <Link :href="route('dashboard')" class="text-2xl font-bold text-indigo-600 tracking-wide">
                            TechnoBitz
                        </Link>

                        <!-- Desktop Links -->
                        <div class="hidden md:flex space-x-6 text-sm font-medium">

                            <Link :href="route('dashboard')" class="text-gray-700 hover:text-indigo-600 transition"
                                :class="{ 'text-indigo-600 font-semibold': route().current('dashboard') }">
                                Dashboard
                            </Link>

                            <Link href="/products" class="text-gray-700 hover:text-indigo-600 transition">
                                Products
                            </Link>

                            <Link href="/orders" class="text-gray-700 hover:text-indigo-600 transition">
                                Orders
                            </Link>

                        </div>
                    </div>

                    <!-- RIGHT SIDE -->
                    <div class="hidden md:flex items-center space-x-6">

                        <!-- Cart -->
                        <Link href="/cart" class="relative text-gray-700 hover:text-indigo-600 transition">
                            🛒
                        </Link>

                        <!-- User Dropdown -->
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button
                                    class="flex items-center space-x-2 text-sm font-medium text-gray-700 hover:text-indigo-600 focus:outline-none">
                                    <span>{{ $page.props.auth.user.name }}</span>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.edit')">
                                    Profile
                                </DropdownLink>

                                <DropdownLink :href="route('logout')" method="post" as="button">
                                    Logout
                                </DropdownLink>
                            </template>
                        </Dropdown>

                    </div>

                    <!-- Mobile Button -->
                    <div class="md:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 focus:outline-none">
                            ☰
                        </button>
                    </div>

                </div>
            </div>

            <!-- Mobile Menu -->
            <div v-if="mobileMenuOpen" class="md:hidden border-t border-gray-200 bg-white">
                <div class="px-4 py-3 space-y-3 text-sm">

                    <Link :href="route('dashboard')" class="block text-gray-700">
                        Dashboard
                    </Link>

                    <Link href="/products" class="block text-gray-700">
                        Products
                    </Link>

                    <Link href="/orders" class="block text-gray-700">
                        Orders
                    </Link>

                    <Link href="/cart" class="block text-gray-700">
                        Cart
                    </Link>

                    <div class="border-t pt-3">
                        <div class="font-medium text-gray-800">
                            {{ $page.props.auth.user.name }}
                        </div>
                        <div class="text-gray-500 text-xs">
                            {{ $page.props.auth.user.email }}
                        </div>

                        <Link :href="route('profile.edit')" class="block mt-2 text-gray-700">
                            Profile
                        </Link>

                        <Link :href="route('logout')" method="post" as="button" class="block mt-1 text-red-600">
                            Logout
                        </Link>
                    </div>

                </div>
            </div>

        </nav>

        <!-- HEADER SLOT -->
        <header v-if="$slots.header" class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <slot />
        </main>

    </div>
</template>