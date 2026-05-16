<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    domain: '',
    interval: '60',
    timeout: '10',
    method: 'GET',
});

const submit = () => {
    form.post(route('web.domains.create'));
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Add Domain" />

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg p-8">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">Add Domain</h2>
                        <p class="mt-1 text-sm text-gray-600">Add a new domain to monitor.</p>
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="domain" value="Domain" />
                            <TextInput
                                id="domain"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.domain"
                                placeholder="example.com"
                                required
                                autofocus
                            />
                            <InputError class="mt-2" :message="form.errors.domain" />
                        </div>

                        <div>
                            <InputLabel for="method" value="Check Method" />
                            <select
                                id="method"
                                v-model="form.method"
                                class="mt-1 w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none transition-all duration-200"
                            >
                                <option value="GET">GET</option>
                                <option value="HEAD">HEAD</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.method" />
                        </div>

                        <div>
                            <InputLabel for="interval" value="Check Interval (minutes)" />
                            <TextInput
                                id="interval"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="form.interval"
                                min="1"
                                max="1440"
                            />
                            <InputError class="mt-2" :message="form.errors.interval" />
                        </div>

                        <div>
                            <InputLabel for="timeout" value="Timeout (seconds)" />
                            <TextInput
                                id="timeout"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="form.timeout"
                                min="1"
                                max="60"
                            />
                            <InputError class="mt-2" :message="form.errors.timeout" />
                        </div>

                        <div class="flex items-center justify-between">
                            <Link
                                :href="route('web.dashboard.page')"
                                class="text-sm text-gray-600 hover:text-gray-900 underline"
                            >
                                Cancel
                            </Link>

                            <PrimaryButton
                                :class="{ 'opacity-75 cursor-not-allowed': form.processing }"
                                :disabled="form.processing"
                            >
                                {{ form.processing ? 'Adding...' : 'Add Domain' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
