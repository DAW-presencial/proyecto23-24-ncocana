<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },

    href: {
        type: String,
        default: undefined // Haciendo el prop href opcional
    }
});
</script>

<template>
    <!-- Usando la directiva v-if para renderizar el elemento <a> solo si href está definido -->
    <a v-if="href !== undefined" :href="href" :type="type"
        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 disabled:opacity-25 transition ease-in-out duration-150">
        <slot />
    </a>
    <!-- Usando la directiva v-else para renderizar un botón en su lugar si href no está definido -->
    <button v-else :type="type"
        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 disabled:opacity-25 transition ease-in-out duration-150">
        <slot />
    </button>
</template>
