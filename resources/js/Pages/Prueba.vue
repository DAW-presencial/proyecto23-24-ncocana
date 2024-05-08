<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>

    <Head title="Prueba" />
    <AuthenticatedLayout>
        <v-btn>
            Button
        </v-btn>
    </AuthenticatedLayout>
</template>
