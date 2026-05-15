<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex flex-col items-center pt-6 sm:pt-12">
        <!-- Логотип -->
        <div>
            <Link href="/">
                <ApplicationLogo class="h-20 w-20 text-gray-700" />
            </Link>
        </div>

        <!-- Контейнер формы -->
        <div class="mt-8 w-full max-w-md px-4 sm:px-0">
            <div class="bg-white shadow-xl shadow-gray-200/50 rounded-2xl px-8 py-10">
                <slot />
            </div>
        </div>
    </div>
</template>
