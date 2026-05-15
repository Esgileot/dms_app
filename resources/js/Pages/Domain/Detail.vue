<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

interface ScheduleSetting {
    interval: number;
    timeout: number;
    method: string;
}

interface Domain {
    id: number;
    domain: string;
    next_check_at: string;
    schedule_setting: ScheduleSetting;
}

interface Log {
    id: number;
    is_up: boolean;
    status_code: number | null;
    response_time: number | null;
    error: string | null;
    checked_at: string;
}

interface Pagination<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

defineProps<{
    domain: Domain;
    logs: Pagination<Log>;
}>();

const expandedLog = ref<number | null>(null);
</script>

<template>
    <Head :title="domain.domain" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <Link
                        :href="route('web.dashboard.page')"
                        class="text-gray-500 hover:text-gray-700 text-sm"
                    >
                        ← Domains
                    </Link>
                    <h2 class="text-xl font-semibold text-gray-800">
                        {{ domain.domain }}
                    </h2>
                </div>
                <Link
                    :href="route('web.domains.update.page', domain.id)"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700"
                >
                    Edit
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">

                <!-- Domain info -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Domain Info</h3>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Method</p>
                            <p class="mt-1 text-sm font-medium text-gray-900">
                                {{ domain.schedule_setting?.method ?? '—' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Interval</p>
                            <p class="mt-1 text-sm font-medium text-gray-900">
                                {{ domain.schedule_setting?.interval ?? '—' }} min
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Timeout</p>
                            <p class="mt-1 text-sm font-medium text-gray-900">
                                {{ domain.schedule_setting?.timeout ?? '—' }} sec
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Next Check</p>
                            <p class="mt-1 text-sm font-medium text-gray-900">
                                {{ domain.next_check_at ? new Date(domain.next_check_at).toLocaleString() : '—' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Logs -->
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b">
                        <h3 class="text-lg font-medium text-gray-900">Check Logs</h3>
                    </div>

                    <!-- Empty -->
                    <div v-if="logs.data.length === 0" class="p-12 text-center">
                        <p class="text-gray-500 text-sm">No logs yet.</p>
                    </div>

                    <!-- Table -->
                    <table v-else class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Response Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Error</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Checked At</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="log in logs.data" :key="log.id">
                                <td class="px-6 py-4 text-sm">
                                    <span
                                        :class="log.is_up
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800'"
                                        class="px-2 py-1 rounded-full text-xs font-medium"
                                    >
                                        {{ log.is_up ? 'Up' : 'Down' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ log.status_code ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ log.response_time ? `${log.response_time}ms` : '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-red-500 max-w-xs">
                                    <span v-if="log.error">
                                        <span
                                            class="cursor-pointer inline-flex items-center gap-1"
                                            @click="expandedLog = expandedLog === log.id ? null : log.id"
                                        >
                                            <span :class="expandedLog === log.id ? '' : 'truncate max-w-[300px] block'">
                                                {{ log.error }}
                                            </span>
                                            <span class="text-xs text-gray-400 shrink-0">
                                                {{ expandedLog === log.id ? '▲ less' : '▼ more' }}
                                            </span>
                                        </span>
                                    </span>
                                    <span v-else>—</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ new Date(log.checked_at).toLocaleString() }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div v-if="logs.last_page > 1" class="px-6 py-4 border-t flex items-center justify-between">
                        <p class="text-sm text-gray-500">
                            Showing {{ logs.data.length }} of {{ logs.total }} logs
                        </p>
                        <div class="flex space-x-2">
                            <Link
                                v-for="page in logs.last_page"
                                :key="page"
                                :href="route('web.domains.show', { domain: domain.id, page })"
                                :class="page === logs.current_page
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
