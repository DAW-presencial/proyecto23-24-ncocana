<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>

    <Head title="Prueba" />
    <AuthenticatedLayout>
        <v-btn color="#6366f1" class="m-5"> Button </v-btn>
    </AuthenticatedLayout>
</template>
