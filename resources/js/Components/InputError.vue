<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message">
        <p v-if="message" class="text-sm text-red-600">
            {{ $t(message) }}
        </p>
    </div>
</template>
