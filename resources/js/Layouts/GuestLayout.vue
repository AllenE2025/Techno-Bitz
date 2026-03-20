<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-zinc-950 flex flex-col items-center justify-center px-4 py-12">

        <!-- Logo -->
        <Link :href="route('home')" class="flex items-center gap-2 mb-8">
            <div class="w-8 h-8 bg-red-500 rounded flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span class="font-display text-xl font-bold tracking-tight text-white">
                TECHNO<span class="text-red-500">BITZ</span>
            </span>
        </Link>

        <!-- Card -->
        <div class="w-full max-w-md bg-zinc-900 border border-zinc-800 rounded-2xl shadow-xl px-8 py-8">
            <slot />
        </div>

    </div>
</template>