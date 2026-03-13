<template>
    <AdminLayout>
        <div class="space-y-6">

            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="font-display text-3xl font-bold text-white">Categories</h1>
                    <p class="text-zinc-400 mt-1 text-sm">{{ categories.total }} total categories</p>
                </div>
                <Link :href="route('admin.categories.create')"
                    class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2.5 rounded-xl transition-colors text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Category
                </Link>
            </div>

            <!-- Table -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden">
                <div v-if="categories.data.length === 0" class="text-center py-16 text-zinc-500 text-sm">
                    No categories yet
                </div>
                <table v-else class="w-full">
                    <thead>
                        <tr
                            class="border-b border-zinc-800 text-xs font-semibold text-zinc-500 uppercase tracking-wider">
                            <th class="text-left px-5 py-3">Category</th>
                            <th class="text-left px-5 py-3 hidden md:table-cell">Slug</th>
                            <th class="text-left px-5 py-3 hidden lg:table-cell">Description</th>
                            <th class="text-right px-5 py-3">Products</th>
                            <th class="text-right px-5 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800">
                        <tr v-for="category in categories.data" :key="category.id"
                            class="hover:bg-zinc-800/50 transition-colors">

                            <!-- Category -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-zinc-800 rounded-lg overflow-hidden shrink-0">
                                        <img v-if="category.image" :src="`/storage/${category.image}`"
                                            :alt="category.name" class="w-full h-full object-cover" />
                                        <div v-else
                                            class="w-full h-full flex items-center justify-center text-zinc-500 font-bold text-sm">
                                            {{ category.name.charAt(0).toUpperCase() }}
                                        </div>
                                    </div>
                                    <p class="text-sm font-medium text-zinc-100">{{ category.name }}</p>
                                </div>
                            </td>

                            <!-- Slug -->
                            <td class="px-5 py-4 hidden md:table-cell">
                                <span class="text-xs font-mono text-zinc-500 bg-zinc-800 px-2 py-1 rounded-lg">
                                    {{ category.slug }}
                                </span>
                            </td>

                            <!-- Description -->
                            <td class="px-5 py-4 hidden lg:table-cell">
                                <p class="text-sm text-zinc-400 truncate max-w-[200px]">
                                    {{ category.description ?? '—' }}
                                </p>
                            </td>

                            <!-- Products Count -->
                            <td class="px-5 py-4 text-right">
                                <span class="text-sm font-semibold text-zinc-300">
                                    {{ category.products_count }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <Link :href="route('admin.categories.edit', category.id)"
                                        class="p-1.5 text-zinc-400 hover:text-white hover:bg-zinc-700 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </Link>
                                    <button @click="deleteCategory(category)" :disabled="category.products_count > 0"
                                        class="p-1.5 text-zinc-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
                                        :title="category.products_count > 0 ? 'Cannot delete category with products' : 'Delete category'">
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
            <div v-if="categories.last_page > 1" class="flex justify-center gap-2">
                <Link v-for="link in categories.links" :key="link.label" :href="link.url || ''"
                    :class="['px-3 py-2 rounded-lg text-sm transition-colors',
                        link.active ? 'bg-red-500 text-white font-medium' :
                            link.url ? 'bg-zinc-800 text-zinc-300 hover:bg-zinc-700' : 'bg-zinc-900 text-zinc-600 cursor-not-allowed']" v-html="link.label" />
            </div>
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps<{
    categories: {
        data: any[];
        total: number;
        last_page: number;
        links: { url: string | null; label: string; active: boolean; }[];
    };
}>();

const deleteCategory = (category: any) => {
    if (!confirm(`Delete "${category.name}"? This cannot be undone.`)) return;
    router.delete(route('admin.categories.destroy', category.id));
};
</script>