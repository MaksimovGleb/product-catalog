<script setup>
import { useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-700">Вход в админ-панель</h2>

            <form @submit.prevent="submit">
                <div>
                    <label class="block font-medium text-sm text-gray-700">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required
                        autofocus
                    />
                    <div v-if="form.errors.email" class="text-red-600 text-sm mt-1">
                        {{ form.errors.email }}
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block font-medium text-sm text-gray-700">Пароль</label>
                    <input
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required
                    />
                    <div v-if="form.errors.password" class="text-red-600 text-sm mt-1">
                        {{ form.errors.password }}
                    </div>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <Link href="/" class="text-sm text-gray-600 hover:text-gray-900 underline">
                        Вернуться на сайт
                    </Link>
                    <button
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150"
                        :disabled="form.processing"
                    >
                        Войти
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
