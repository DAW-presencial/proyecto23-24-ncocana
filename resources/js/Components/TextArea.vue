<template>
    <textarea
        class="flex-grow w-full min-h-[3rem] rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
</template>

<script setup>
import InputLabel from '@/Components/InputLabel.vue'
defineProps({
    value: {
        type: String,
    },
});
</script>
