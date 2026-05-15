<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

interface ScheduleSetting {
    interval: string;
    timeout: string;
    method: string;
}

interface Domain {
    id: number;
    domain: string;
    schedule_setting: ScheduleSetting;
}

const props = defineProps<{
    domain: Domain;
}>();

const form = useForm({
    domain: props.domain.domain,
    interval: props.domain.schedule_setting?.interval,
    timeout: props.domain.schedule_setting?.timeout,
    method: props.domain.schedule_setting?.method,
});

const submit = () => {
    form.put(route('web.domains.update', props.domain.id));
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Edit Domain" />

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg p-8">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">Edit Domain</h2>
                        <p class="mt-1 text-sm text-gray-600">Update domain monitoring settings.</p>
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
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
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
                                {{ form.processing ? 'Saving...' : 'Save Changes' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
