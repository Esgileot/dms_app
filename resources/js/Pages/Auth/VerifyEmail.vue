<script setup lang="ts">
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    status?: string;
}>();

const form = useForm({});

const submit = () => {
    form.post(route('web.verification.send'));
};

const verificationLinkSent = computed(() =>
    props.status === 'verification-link-sent'
);
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <div class="max-w-md mx-auto">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                    Verify your email
                </h2>
                <p class="mt-3 text-gray-600">
                    Thanks for signing up! 🎉
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <div class="text-sm text-gray-600 leading-relaxed">
                    Before getting started, could you verify your email address by
                    clicking on the link we just sent to you?
                </div>

                <div
                    v-if="verificationLinkSent"
                    class="mt-6 rounded-xl bg-green-50 border border-green-100 p-4 text-sm font-medium text-green-700"
                >
                    A new verification link has been sent to your email address.
                </div>

                <form @submit.prevent="submit" class="mt-8">
                    <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                        <PrimaryButton
                            :disabled="form.processing"
                            :class="{ 'opacity-75 cursor-not-allowed': form.processing }"
                            class="w-full sm:w-auto"
                        >
                            {{ form.processing ? 'Sending...' : 'Resend Verification Email' }}
                        </PrimaryButton>

                        <Link
                            :href="route('web.logout')"
                            method="post"
                            as="button"
                            class="text-sm text-gray-500 hover:text-gray-700 underline underline-offset-4 transition-colors"
                        >
                            Log out
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>
