<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    status: number;
}>();

const messages: Record<number, string> = {
    404: 'Page not found.',
    403: 'Forbidden.',
    500: 'Server error.',
};
</script>

<template>
    <Head :title="String(status)" />

    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <div class="text-center">
            <h1 class="text-6xl font-bold text-gray-900">{{ status }}</h1>
            <p class="mt-4 text-gray-500">{{ messages[status] ?? 'Something went wrong.' }}</p>
            <Link
                :href="route('web.dashboard.page')"
                class="mt-6 inline-block text-indigo-600 hover:underline"
            >
                Go home
            </Link>
        </div>
    </div>
</template>
