<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    products: Object,
    categories: [Array, Object],
    filters: Object,
});

const categoryId = ref(props.filters.category_id || '');
const search = ref(props.filters.search || '');
let timeout = null;

const updateFilters = () => {
    router.get('/admin/products', { category_id: categoryId.value, search: search.value }, {
        preserveState: true,
        replace: true,
    });
};

watch(categoryId, () => {
    updateFilters();
});

watch(search, () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        updateFilters();
    }, 300);
});

const deleteProduct = (id) => {
    if (confirm('Вы уверены, что хотите удалить этот товар?')) {
        router.delete(`/admin/products/${id}`);
    }
};
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex flex-col">
        <!-- Навигация админки -->
        <nav class="bg-slate-900 shadow-sm sticky top-0 z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center">
                        <span class="text-xl font-black text-white tracking-tight italic">ADMIN<span class="text-indigo-400">PANEL</span></span>
                    </div>
                    <div class="flex items-center space-x-6">
                        <Link
                            href="/logout"
                            method="post"
                            as="button"
                            class="text-slate-300 hover:text-red-400 text-sm font-medium transition-colors"
                        >
                            Выйти
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 flex-1 w-full">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-black text-slate-900">Управление товарами</h1>
                    <p class="text-slate-500 mt-1">Всего товаров в каталоге: {{ products.meta.total }}</p>
                </div>
                <Link
                    href="/admin/products/create"
                    class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all active:scale-95"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Добавить товар
                </Link>
            </div>

            <!-- Фильтры -->
            <div class="mb-8 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <div class="flex flex-col md:flex-row gap-6 items-end">
                    <div class="flex-1 min-w-0 w-full group">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 group-focus-within:text-indigo-600 transition-colors">Быстрый поиск</label>
                        <input
                            v-model="search"
                            type="text"
                            class="block w-full px-4 py-3 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-700 sm:text-sm rounded-xl bg-slate-50 transition-all"
                            placeholder="Название товара..."
                        />
                    </div>
                    <div class="flex-1 min-w-0 w-full group">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 group-focus-within:text-indigo-600 transition-colors">Категория</label>
                        <select
                            v-model="categoryId"
                            class="block w-full px-4 py-3 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-700 sm:text-sm rounded-xl bg-slate-50 transition-all appearance-none cursor-pointer"
                        >
                            <option value="">Все категории</option>
                            <option v-for="category in categories.data" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                    </div>
                    <div class="flex-none">
                        <button 
                            @click="search = ''; categoryId = ''"
                            :disabled="!search && !categoryId"
                            class="inline-flex items-center px-6 py-3 border text-sm font-bold rounded-xl transition-all active:scale-95 shadow-sm whitespace-nowrap"
                            :class="[
                                (search || categoryId) 
                                ? 'border-slate-200 text-slate-600 bg-white hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-100' 
                                : 'border-slate-100 text-slate-300 bg-slate-50 cursor-not-allowed opacity-60'
                            ]"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Сбросить
                        </button>
                    </div>
                </div>
            </div>

            <!-- Таблица товаров -->
            <div class="bg-white shadow-sm border border-slate-200 rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Товар</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Категория</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Цена</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            <tr v-for="product in products.data" :key="product.id" class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-slate-900">{{ product.name }}</div>
                                            <div class="text-xs text-slate-500 truncate max-w-[200px]">{{ product.description }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700">
                                        {{ product.category.name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-slate-900">
                                    {{ product.price }} ₽
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-3">
                                        <Link
                                            :href="`/admin/products/${product.id}/edit`"
                                            class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                            title="Редактировать"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </Link>
                                        <button
                                            @click="deleteProduct(product.id)"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Удалить"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Пагинация -->
            <div v-if="products.meta.links.length > 3" class="mt-8 flex justify-center">
                <nav class="inline-flex bg-white p-1 rounded-xl shadow-sm border border-slate-200" aria-label="Pagination">
                    <Link
                        v-for="(link, k) in products.meta.links"
                        :key="k"
                        :href="link.url || '#'"
                        v-html="link.label"
                        class="relative inline-flex items-center px-4 py-2 text-sm font-bold rounded-lg transition-all"
                        :class="[
                            link.active ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'text-slate-500 hover:bg-slate-50',
                            !link.url ? 'opacity-30 cursor-not-allowed' : ''
                        ]"
                    />
                </nav>
            </div>
        </main>
    </div>
</template>
