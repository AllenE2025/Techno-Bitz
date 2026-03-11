<template>
    <AdminLayout>
        <div class="max-w-3xl space-y-6">

            <!-- Header -->
            <div class="flex items-center gap-4">
                <Link :href="route('admin.products.index')"
                    class="p-2 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </Link>
                <div>
                    <h1 class="font-display text-3xl font-bold text-white">Edit Product</h1>
                    <p class="text-zinc-400 mt-1 text-sm truncate max-w-md">{{ product.name }}</p>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-5">

                <!-- Basic Info -->
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 space-y-5">
                    <h2 class="font-semibold text-white">Basic Information</h2>

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1.5">Product Name <span
                                class="text-red-400">*</span></label>
                        <input v-model="form.name" type="text"
                            class="w-full bg-zinc-800 border rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none transition-colors"
                            :class="form.errors.name ? 'border-red-500' : 'border-zinc-700 focus:border-red-500'" />
                        <p v-if="form.errors.name" class="text-red-400 text-xs mt-1">{{ form.errors.name }}</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1.5">Description</label>
                        <textarea v-model="form.description" rows="4"
                            class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-red-500 transition-colors resize-none" />
                    </div>

                    <!-- Brand & Category -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-1.5">Brand</label>
                            <input v-model="form.brand" type="text"
                                class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-red-500 transition-colors" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-1.5">Category <span
                                    class="text-red-400">*</span></label>
                            <select v-model="form.category_id"
                                class="w-full bg-zinc-800 border rounded-xl px-4 py-3 text-sm text-white focus:outline-none transition-colors"
                                :class="form.errors.category_id ? 'border-red-500' : 'border-zinc-700 focus:border-red-500'">
                                <option value="">Select category</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                            </select>
                            <p v-if="form.errors.category_id" class="text-red-400 text-xs mt-1">{{
                                form.errors.category_id }}</p>
                        </div>
                    </div>

                    <!-- Condition -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1.5">Condition <span
                                class="text-red-400">*</span></label>
                        <div class="flex gap-3">
                            <button v-for="cond in conditions" :key="cond" type="button" @click="form.condition = cond"
                                :class="['flex-1 py-2.5 rounded-xl text-sm font-medium capitalize transition-colors border',
                                    form.condition === cond
                                        ? 'bg-red-500/10 border-red-500/50 text-red-400'
                                        : 'bg-zinc-800 border-zinc-700 text-zinc-400 hover:text-white hover:border-zinc-600']">
                                {{ cond }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Pricing & Stock -->
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 space-y-5">
                    <h2 class="font-semibold text-white">Pricing & Stock</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-1.5">Price (₱) <span
                                    class="text-red-400">*</span></label>
                            <input v-model="form.price" type="number" min="0" step="0.01"
                                class="w-full bg-zinc-800 border rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none transition-colors"
                                :class="form.errors.price ? 'border-red-500' : 'border-zinc-700 focus:border-red-500'" />
                            <p v-if="form.errors.price" class="text-red-400 text-xs mt-1">{{ form.errors.price }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-1.5">Stock <span
                                    class="text-red-400">*</span></label>
                            <input v-model="form.stock" type="number" min="0"
                                class="w-full bg-zinc-800 border rounded-xl px-4 py-3 text-sm text-white placeholder-zinc-600 focus:outline-none transition-colors"
                                :class="form.errors.stock ? 'border-red-500' : 'border-zinc-700 focus:border-red-500'" />
                            <p v-if="form.errors.stock" class="text-red-400 text-xs mt-1">{{ form.errors.stock }}</p>
                        </div>
                    </div>
                </div>

                <!-- Image -->
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 space-y-4">
                    <h2 class="font-semibold text-white">Product Image</h2>

                    <!-- Current or Preview Image -->
                    <div v-if="imagePreview || product.image"
                        class="relative w-40 h-40 rounded-xl overflow-hidden border border-zinc-700">
                        <img :src="imagePreview ?? `/storage/${product.image}`" alt="Preview"
                            class="w-full h-full object-cover" />
                        <button type="button" @click="removeImage"
                            class="absolute top-2 right-2 w-6 h-6 bg-zinc-900/80 hover:bg-red-500 text-white rounded-full flex items-center justify-center transition-colors">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <label v-else
                        class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-zinc-700 hover:border-red-500/50 rounded-xl cursor-pointer transition-colors group">
                        <svg class="w-8 h-8 text-zinc-600 group-hover:text-red-400 transition-colors mb-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm text-zinc-500 group-hover:text-zinc-300 transition-colors">Click to upload new
                            image</p>
                        <p class="text-xs text-zinc-600 mt-1">PNG, JPG, WEBP up to 2MB</p>
                        <input type="file" accept="image/*" class="hidden" @change="handleImage" />
                    </label>

                    <!-- Replace image button -->
                    <label v-if="imagePreview || product.image"
                        class="inline-flex items-center gap-2 text-xs text-zinc-500 hover:text-zinc-300 cursor-pointer transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Replace image
                        <input type="file" accept="image/*" class="hidden" @change="handleImage" />
                    </label>

                    <p v-if="form.errors.image" class="text-red-400 text-xs">{{ form.errors.image }}</p>
                </div>

                <!-- Visibility -->
                <div class="bg-zinc-900 border border-zinc-800 rounded-xl p-6 space-y-4">
                    <h2 class="font-semibold text-white">Visibility</h2>
                    <div class="space-y-3">
                        <label class="flex items-center justify-between cursor-pointer">
                            <div>
                                <p class="text-sm font-medium text-zinc-300">Active</p>
                                <p class="text-xs text-zinc-500">Product is visible in the store</p>
                            </div>
                            <button type="button" @click="form.is_active = !form.is_active" :class="['relative w-11 h-6 rounded-full transition-colors',
                                form.is_active ? 'bg-red-500' : 'bg-zinc-700']">
                                <span :class="['absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform',
                                    form.is_active ? 'translate-x-5' : 'translate-x-0']" />
                            </button>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <div>
                                <p class="text-sm font-medium text-zinc-300">Featured</p>
                                <p class="text-xs text-zinc-500">Highlight this product on the homepage</p>
                            </div>
                            <button type="button" @click="form.is_featured = !form.is_featured" :class="['relative w-11 h-6 rounded-full transition-colors',
                                form.is_featured ? 'bg-red-500' : 'bg-zinc-700']">
                                <span :class="['absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform',
                                    form.is_featured ? 'translate-x-5' : 'translate-x-0']" />
                            </button>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pb-6">
                    <Link :href="route('admin.products.index')"
                        class="px-5 py-2.5 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white text-sm font-medium rounded-xl transition-colors">
                        Cancel
                    </Link>
                    <button type="submit" :disabled="form.processing"
                        class="px-5 py-2.5 bg-red-500 hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-semibold rounded-xl transition-colors">
                        {{ form.processing ? 'Saving...' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps<{
    product: any;
    categories: any[];
    conditions: string[];
}>();

const imagePreview = ref<string | null>(null);

const form = useForm({
    name: props.product.name,
    description: props.product.description ?? '',
    brand: props.product.brand ?? '',
    category_id: props.product.category_id,
    condition: props.product.condition,
    price: props.product.price,
    stock: props.product.stock,
    is_active: props.product.is_active,
    is_featured: props.product.is_featured,
    image: null as File | null,
    _method: 'PATCH',
});

const handleImage = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (!file) return;
    form.image = file;
    imagePreview.value = URL.createObjectURL(file);
};

const removeImage = () => {
    form.image = null;
    imagePreview.value = null;
};

const submit = () => {
    form.post(route('admin.products.update', props.product.id), {
        forceFormData: true,
    });
};
</script>