<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <div class="flex items-center justify-between">
        <label v-if="value" class="block text-sm font-medium leading-6 text-gray-900">{{ value }}</label>
        <slot>
            
        </slot>
    </div>
</template>
