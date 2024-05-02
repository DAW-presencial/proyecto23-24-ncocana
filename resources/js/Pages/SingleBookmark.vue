<template>

    <Head :title="book.title" />
    <AuthenticatedLayout>
        <div class="flex flex-col justify-center m-auto max-w-7xl h-screen items-center">
            <div class="container mx-4 h-3/4">
                <Card class="h-auto">
                    <div class="mt-3">
                        <InputLabel value="Title"></InputLabel>
                        <TextInput v-model="book.title"></TextInput>
                    </div>
                    <div class="mt-3">
                        <InputLabel value="Author"></InputLabel>
                        <TextInput v-model="book.author"></TextInput>
                    </div>
                    <div class="mt-3">
                        <InputLabel value="Language"></InputLabel>
                        <TextInput v-model="book.language"></TextInput>
                    </div>
                    <div class="mt-3">
                        <InputLabel value="Read Pages"></InputLabel>
                        <TextInput v-model="book.read_pages"></TextInput>
                    </div>
                    <div class="mt-3">
                        <InputLabel value="Total Pages"></InputLabel>
                        <TextInput v-model="book.total_pages"></TextInput>
                    </div>
                    <div class="mt-3">
                        <InputLabel value="Synopsis"></InputLabel>
                        <TextInput v-model="book.synopsis"></TextInput>
                    </div>
                    <div class="mt-3">
                        <InputLabel value="Notes"></InputLabel>
                        <TextInput v-model="book.notes"></TextInput>
                    </div>
                </Card>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/Card.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'


const book = ref({
    "id": 1,
    "title": "The Great Gatsby",
    "author": "F. Scott Fitzgerald",
    "language": "English",
    "read_pages": 150,
    "total_pages": 180,
    "synopsis": "The story of the fabulously wealthy Jay Gatsby and his love for the beautiful Daisy Buchanan.",
    "notes": "Amazing book, must-read!"
});

</script>
