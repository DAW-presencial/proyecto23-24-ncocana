<template>

    <Head title="Search Advanced" />
    <AuthenticatedLayout>
        <main class="flex-1 p-4">
            <div class="mx-auto max-w-7xl mt-6 gap-4">
                <div class="text-xl font-bold mx-auto my-4">
                    <h1>Create Bookmark</h1>
                </div>
                <!-- TYPES -->
                <label for="type" class="block text-sm font-medium leading-6 text-gray-900">Type</label>
                <div class="mt-2">
                    <select id="type"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        v-model="selected_type">
                        <option v-for="(fields, type) in fields" :key="type" :value="type">{{ type }}</option>
                    </select>
                </div>

                <!-- DYNAMIC FIELDS -->
                <div v-if="selected_type">
                    <form @submit.prevent="enviar">
                        <div v-for="(label, field) in fields[selected_type]" :key="field">
                            <label :for="field" class="block text-sm font-medium leading-6 text-gray-900">{{ label
                                }}</label>
                            <div class="my-2">
                                <input type="text" :name="field" :id="field" :autocomplete="field"
                                    v-model="dataInput[field]"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    :required="true" />
                            </div>
                        </div>
                        <PrimaryButton>Send</PrimaryButton>
                    </form>
                </div>
            </div>
        </main>
    </AuthenticatedLayout>
</template>

<script setup>
import { getParamsBookmark } from '@/utils/functions';
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref } from "vue";
import axios from 'axios';

const selected_type = ref(null);
const dataInput = ref({});

const fields = {
    Book: {
        title: "Title",
        author: "Author",
        language: "Language",
        read_pages: "Read Pages",
        total_pages: "Total Pages",
        synopsis: "Synopsis",
        notes: "Notes",
        tags: "Tags"
    },
    Movie: {
        title: "Title",
        director: "Director",
        actors: "Actors",
        release_date: "Release Date",
        currently_at: "Currently At",
        notes: "Notes",
        synopsis: "Synopsis",
        tags: "Tags"
    },
    Series: {
        title: "Title",
        actors: "Actors",
        num_seasons: "Number of Seasons",
        num_episodes: "Number of Episodes",
        currently_at: "Currently At",
        synopsis: "Synopsis",
        notes: "Notes",
        tags: "Tags"
    },
    Fanfic: {
        title: "Title",
        author: "Author",
        fandom: "Fandom",
        language: "Language",
        words: "Words",
        read_chapters: "Read Chapters",
        total_chapters: "Total Chapters",
        relationships: "Relationships",
        synopsis: "Synopsis",
        notes: "Notes",
        tags: "Tags"
    }
};

const enviar = async () => {
    try {
        console.log(dataInput.value);
        const tags = dataInput.value.tags;
        const tagsSeparados = tags.split(',');
        const bookmarkableParams = await getParamsBookmark(selected_type.value, dataInput.value);
        const dataToSend = {
            data: {
                type: "bookmarks",
                attributes:
                {
                    title: dataInput.value.title,
                    synopsis: dataInput.value.synopsis,
                    notes: dataInput.value.notes,
                    bookmarkable: bookmarkableParams,
                    bookmarkable_type: selected_type.value,
                    tags: tagsSeparados
                }
            }
        };

        const response = await axios.post('/bookmarks/', dataToSend);
        console.log('Response:', response);

        if (response.status === 201 || response.status === 200) {
            window.location.href = "/bookmarks";
        } else {
            console.error('Error: Unexpected response status', response.status);
        }
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};
</script>
