<script setup lang="ts">
import { onMounted, ref } from 'vue';

const model = defineModel<string>({ required: true });

const input = ref<HTMLInputElement | null>(null);

onMounted(() => {
    if (input.value?.hasAttribute('autofocus')) {
        input.value?.focus();
    }
});

defineExpose({ focus: () => input.value?.focus() });
</script>

<template>
    <input
        ref="input"
        v-model="model"
        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3
               text-gray-900 placeholder:text-gray-400
               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
               transition-all duration-200 outline-none
               disabled:bg-gray-100 disabled:cursor-not-allowed"
        :class="$attrs.class"
    />
</template>
