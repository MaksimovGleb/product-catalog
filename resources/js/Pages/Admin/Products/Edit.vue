<script setup>
import { useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    product: Object,
    categories: [Array, Object],
});

const form = useForm({
    name: props.product.data.name,
    category_id: props.product.data.category_id,
    price: props.product.data.price,
    description: props.product.data.description,
});

const submit = () => {
    form.put(`/admin/products/${props.product.data.id}`);
};
</script>

<template>
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Редактировать товар</h1>
                <Link href="/admin/products" class="text-indigo-600 hover:text-indigo-900">Назад к списку</Link>
            </div>

            <div class="bg-white shadow sm:rounded-lg px-6 py-8">
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Название</label>
                            <input
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required
                            />
                            <div v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Категория</label>
                            <select
                                v-model="form.category_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required
                            >
                                <option value="">Выберите категорию</option>
                                <option v-for="category in categories.data" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                            <div v-if="form.errors.category_id" class="text-red-600 text-sm mt-1">{{ form.errors.category_id }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Цена (₽)</label>
                            <input
                                v-model="form.price"
                                type="number"
                                step="0.01"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required
                            />
                            <div v-if="form.errors.price" class="text-red-600 text-sm mt-1">{{ form.errors.price }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Описание</label>
                            <textarea
                                v-model="form.description"
                                rows="4"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            ></textarea>
                            <div v-if="form.errors.description" class="text-red-600 text-sm mt-1">{{ form.errors.description }}</div>
                        </div>

                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                :disabled="form.processing"
                            >
                                Сохранить изменения
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
