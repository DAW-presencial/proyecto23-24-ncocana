<script setup>
import FirstLayout from '@/Layouts/FirstLayout.vue'
import Authenticated from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});


import { ref } from 'vue'
import { Dialog, DialogPanel } from '@headlessui/vue'
import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const mobileMenuOpen = ref(false);


onMounted(() => {
    document.title = 'Welcome | MyBookMarks';
});
</script>

<template>
    <div v-if="canLogin">
        <AuthenticatedLayout></AuthenticatedLayout>
    </div>
    <div v-else>
        <FirstLayout></FirstLayout>
    </div>

</template>
