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
    router.get('/', { category_id: categoryId.value, search: search.value }, {
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
</script>

<template>
    <div class="min-h-screen bg-slate-50">
        <!-- Навигация -->
        <nav class="bg-white shadow-sm sticky top-0 z-10 border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center">
                        <span class="text-2xl font-black text-indigo-600 tracking-tight">PRODUCT<span class="text-slate-900">CATALOG</span></span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <Link href="/login" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                            Вход в админку
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Заголовок и фильтры -->
            <div class="mb-12 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <h1 class="text-3xl font-bold text-slate-900 mb-8">Найти идеальный товар</h1>
                
                <div class="flex flex-col md:flex-row gap-6 items-end">
                    <div class="flex-1 min-w-0 w-full group">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 group-focus-within:text-indigo-600 transition-colors">Поиск по названию</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input
                                v-model="search"
                                type="text"
                                class="block w-full pl-10 pr-4 py-3 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-700 sm:text-sm rounded-xl bg-slate-50 transition-all"
                                placeholder="Начните вводить..."
                            />
                        </div>
                    </div>
                    <div class="flex-1 min-w-0 w-full group">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 group-focus-within:text-indigo-600 transition-colors">Категория</label>
                        <select
                            v-model="categoryId"
                            class="block w-full px-4 py-3 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-700 sm:text-sm rounded-xl bg-slate-50 transition-all appearance-none cursor-pointer"
                            style="background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22 fill=%22none%22 viewBox=%220 0 20 20%22%3E%3Cpath stroke=%22%2364748b%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%221.5%22 d=%22m6 8 4 4 4-4%22%2F%3E%3C%2Fsvg%3E'); background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.5em 1.5em;"
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

            <!-- Сетка товаров -->
            <div v-if="products.data.length > 0" class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                <div v-for="product in products.data" :key="product.id" class="group relative flex flex-col bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:shadow-indigo-100 transition-all duration-300">
                    <div class="w-full aspect-square bg-slate-100 relative overflow-hidden flex items-center justify-center">
                        <!-- Стилизованная иконка вместо пустого места -->
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-slate-50"></div>
                        <svg class="w-16 h-16 text-slate-300 relative z-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <div class="absolute bottom-4 left-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/80 backdrop-blur-sm text-indigo-600 shadow-sm border border-white">
                                {{ product.category.name }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">
                                <Link :href="`/product/${product.id}`">
                                    <span class="absolute inset-0"></span>
                                    {{ product.name }}
                                </Link>
                            </h3>
                        </div>
                        <p class="text-sm text-slate-500 line-clamp-2 mb-4 flex-1">
                            {{ product.description }}
                        </p>
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                            <span class="text-xl font-black text-slate-900">{{ product.price }} <span class="text-sm font-normal text-slate-400 italic">₽</span></span>
                            <span class="text-indigo-600 font-bold text-sm flex items-center group-hover:translate-x-1 transition-transform">
                                Подробнее
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Пустое состояние -->
            <div v-else class="text-center py-24 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                <div class="mx-auto h-24 w-24 text-slate-200">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-xl font-bold text-slate-900">Ничего не найдено</h3>
                <p class="mt-2 text-slate-500">Попробуйте изменить параметры поиска или фильтрации</p>
                <button @click="search = ''; categoryId = ''" class="mt-6 text-indigo-600 font-semibold hover:text-indigo-500">
                    Сбросить всё
                </button>
            </div>

            <!-- Пагинация -->
            <div v-if="products.meta.links.length > 3" class="mt-16 flex justify-center">
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

        <footer class="bg-white border-t border-slate-200 py-12 mt-12">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <p class="text-slate-400 text-sm">&copy; 2026 ProductCatalog. Все права защищены.</p>
            </div>
        </footer>
    </div>
</template>
