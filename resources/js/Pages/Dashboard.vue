<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

interface ScheduleSetting {
    interval: number;
    timeout: number;
    method: string;
}

interface Domain {
    id: number;
    domain: string;
    schedule_setting: ScheduleSetting;
    next_check_at: string;
    created_at: string;
}

interface Pagination<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

defineProps<{
    domains: Pagination<Domain>;
}>();

const deleteDomain = (id: number) => {
    if (!confirm('Are you sure you want to delete this domain?')) return;

    useForm({}).delete(route('web.domains.delete', id));
};
</script>

<template>
    <Head title="Domains" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Domains
                </h2>
                <Link
                    :href="route('web.domains.create.page')"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700"
                >
                    Add Domain
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">

                    <!-- Empty state -->
                    <div v-if="domains.data.length === 0" class="p-12 text-center">
                        <p class="text-gray-500 text-sm">No domains added yet.</p>
                        <Link
                            :href="route('web.domains.create.page')"
                            class="mt-4 inline-block text-indigo-600 hover:underline text-sm"
                        >
                            Add your first domain
                        </Link>
                    </div>

                    <!-- Table -->
                    <table v-else class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Domain</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Next check</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Interval</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Timeout</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="domain in domains.data" :key="domain.id">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ domain.domain }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ new Date(domain.next_check_at).toLocaleString() ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ domain.schedule_setting?.method ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ domain.schedule_setting?.interval ?? '—' }} min
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ domain.schedule_setting?.timeout ?? '—' }} sec
                                </td>
                                <td class="px-6 py-4 text-sm text-right space-x-3">
                                    <Link
                                        :href="route('web.domains.details.page', domain.id)"
                                        class="text-indigo-600 hover:text-indigo-900"
                                    >
                                        Details
                                    </Link>

                                    <Link
                                        :href="route('web.domains.update.page', domain.id)"
                                        class="text-indigo-600 hover:text-indigo-900"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        @click="deleteDomain(domain.id)"
                                        class="text-red-600 hover:text-red-900"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div v-if="domains.last_page > 1" class="px-6 py-4 border-t flex items-center justify-between">
                        <p class="text-sm text-gray-500">
                            Showing {{ domains.data.length }} of {{ domains.total }} domains
                        </p>
                        <div class="flex space-x-2">
                            <Link
                                v-for="page in domains.last_page"
                                :key="page"
                                :href="route('web.dashboard.page', { page })"
                                :class="page === domains.current_page
                                    ? 'bg-indigo-600 text-white'
                                    : 'bg-white text-gray-700 hover:bg-gray-50'"
                                class="px-3 py-1 text-sm rounded border"
                            >
                                {{ page }}
                            </Link>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
