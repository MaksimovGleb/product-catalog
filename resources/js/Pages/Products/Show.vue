<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    product: Object,
});
</script>

<template>
    <div class="min-h-screen bg-slate-50">
        <!-- Навигация -->
        <nav class="bg-white shadow-sm border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <Link href="/" class="flex items-center">
                        <span class="text-2xl font-black text-indigo-600 tracking-tight">PRODUCT<span class="text-slate-900">CATALOG</span></span>
                    </Link>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <!-- Хлебные крошки -->
            <nav class="mb-8">
                <ol class="flex items-center space-x-2 text-sm text-slate-500 font-medium">
                    <li>
                        <Link href="/" class="hover:text-indigo-600 transition-colors">Каталог</Link>
                    </li>
                    <li class="flex items-center space-x-2">
                        <svg class="h-4 w-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                        </svg>
                        <span class="text-slate-900">{{ product.data.name }}</span>
                    </li>
                </ol>
            </nav>

            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="lg:grid lg:grid-cols-2 lg:gap-x-0">
                    <!-- Изображение -->
                    <div class="bg-slate-100 flex items-center justify-center p-12 lg:p-24 relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-slate-50"></div>
                        <svg class="w-48 h-48 text-slate-200 relative z-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>

                    <!-- Инфо -->
                    <div class="p-8 lg:p-16 flex flex-col justify-center">
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 mb-4 uppercase tracking-widest">
                                {{ product.data.category.name }}
                            </span>
                            <h1 class="text-4xl font-black text-slate-900 mb-4">{{ product.data.name }}</h1>
                            <p class="text-3xl font-black text-indigo-600 mb-8">{{ product.data.price }} <span class="text-lg font-normal text-slate-400">₽</span></p>
                        </div>

                        <div class="prose prose-slate mb-12">
                            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Описание товара</h3>
                            <p class="text-lg text-slate-600 leading-relaxed">{{ product.data.description }}</p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <Link 
                                href="/" 
                                class="inline-flex items-center justify-center px-8 py-4 bg-slate-900 border border-transparent rounded-2xl font-bold text-white shadow-lg shadow-slate-200 hover:bg-slate-800 transition-all active:scale-95 group"
                            >
                                <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Назад в каталог
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
