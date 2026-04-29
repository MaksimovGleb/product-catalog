<script setup>
import { useForm, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    categories: Array,
});

const editingCategory = ref(null);

const form = useForm({
    name: '',
    description: '',
});

const submit = () => {
    if (editingCategory.value) {
        form.put(`/admin/categories/${editingCategory.value.id}`, {
            onSuccess: () => {
                editingCategory.value = null;
                form.reset();
            },
        });
    } else {
        form.post('/admin/categories', {
            onSuccess: () => form.reset(),
        });
    }
};

const editCategory = (category) => {
    editingCategory.value = category;
    form.name = category.name;
    form.description = category.description;
};

const cancelEdit = () => {
    editingCategory.value = null;
    form.reset();
};

const deleteCategory = (id) => {
    if (confirm('Вы уверены? Удаление невозможно, если в категории есть товары.')) {
        router.delete(`/admin/categories/${id}`);
    }
};
</script>

<template>
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">Управление категориями</h1>
            <div class="flex space-x-4">
                <Link href="/admin/products" class="text-indigo-600 hover:text-indigo-900 self-center">К товарам</Link>
                <form action="/logout" method="POST" class="inline">
                    <button type="submit" class="text-red-600 hover:text-red-900">Выйти</button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Список -->
            <div class="md:col-span-2">
                <div class="bg-white shadow overflow-hidden sm:rounded-md">
                    <ul role="list" class="divide-y divide-gray-200">
                        <li v-for="category in categories" :key="category.id">
                            <div class="px-4 py-4 flex items-center sm:px-6">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-indigo-600 truncate">{{ category.name }}</p>
                                    <p class="text-sm text-gray-500">{{ category.description }}</p>
                                </div>
                                <div class="ml-5 flex-shrink-0 flex space-x-4">
                                    <button
                                        @click="editCategory(category)"
                                        class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                                    >
                                        Редактировать
                                    </button>
                                    <button
                                        @click="deleteCategory(category.id)"
                                        class="text-red-600 hover:text-red-900 text-sm font-medium"
                                    >
                                        Удалить
                                    </button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Форма -->
            <div>
                <div class="bg-white shadow sm:rounded-lg px-6 py-8">
                    <h2 class="text-lg font-bold mb-4">
                        {{ editingCategory ? 'Редактировать категорию' : 'Добавить категорию' }}
                    </h2>
                    <form @submit.prevent="submit">
                        <div class="grid grid-cols-1 gap-y-4">
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
                                <label class="block text-sm font-medium text-gray-700">Описание</label>
                                <textarea
                                    v-model="form.description"
                                    rows="3"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                ></textarea>
                                <div v-if="form.errors.description" class="text-red-600 text-sm mt-1">{{ form.errors.description }}</div>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button
                                    v-if="editingCategory"
                                    type="button"
                                    @click="cancelEdit"
                                    class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300"
                                >
                                    Отмена
                                </button>
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                                    :disabled="form.processing"
                                >
                                    {{ editingCategory ? 'Сохранить' : 'Создать' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
