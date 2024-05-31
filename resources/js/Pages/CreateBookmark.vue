<template>

    <Head :title="$t('Create Bookmark')" />

    <AuthenticatedLayout>
        <main class="flex-1 p-4">
            <div class="mx-auto max-w-7xl mt-6 gap-4">
                <div class="pb-4">
                    <Breadcrumbs :items="['Home', $t('Bookmark'), $t('Create Bookmark')]"></Breadcrumbs>
                </div>
                <div class="text-xl font-bold mx-auto my-4">
                    <h1>{{ $t('Create Bookmark') }}</h1>
                </div>
                <!-- TYPES -->
                <label for="type" class="block text-sm font-medium leading-6 text-gray-900">{{ $t('Type') }}</label>
                <div class="mt-2">
                    <select id="type"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        v-model="selected_type">
                        <option value="default" disabled selected>{{ $t('Choose a bookmark type') }}</option>
                        <option v-for="(fields, type) in fields" :key="type" :value="type">{{ $t(type) }}</option>
                    </select>
                </div>

                <!-- DYNAMIC FIELDS -->
                <div v-if="selected_type !== 'default'">
                    <form @submit.prevent="enviar">
                        <div v-for="(label, field) in fields[selected_type]" :key="field">
                            <label :for="field" class="block text-sm font-medium leading-6 text-gray-900">{{ $t(label) }}</label>
                            <div class="my-2">
                                <input type="text" :name="field" :id="field" :autocomplete="field"
                                    v-model="dataInput[field]" :placeholder="$t(placeholders[selected_type][field])"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    :class="{ 'border-red-600': errors[field] }" required />
                                <p v-if="errors[field]" class="mt-2 text-sm text-red-600">{{ errors[field][0] }}</p>
                            </div>
                        </div>
                        <div>
                            <label for="tags" class="block text-sm font-medium leading-6 text-gray-900">{{ $t('Tags') }}</label>
                            <div class="my-2">
                                <input type="text" name="tags" id="tags" autocomplete="tags"
                                    v-model="dataInput.tags" :placeholder="$t(placeholders[selected_type].tags)"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    :class="{ 'border-red-600': errors['tags'] }" />
                                <p v-if="errors['tags']" class="mt-2 text-sm text-red-600">{{ errors['tags'] }}</p>
                            </div>
                        </div>
                        <PrimaryButton>{{ $t('Send') }}</PrimaryButton>
                    </form>
                </div>
            </div>
        </main>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import Breadcrumbs from '@/Components/Breadcrumbs.vue';
import { getParamsBookmark } from '@/utils/functions';
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from '@/config/axios-config';  // Ensure axios is imported correctly

const selected_type = ref('default');
const dataInput = ref({});
const errors = ref({});

const fields = {
    Book: {
        title: "Title",
        author: "Author",
        language: "Language",
        read_pages: "Read Pages",
        total_pages: "Total Pages",
        synopsis: "Synopsis",
        notes: "Notes",
    },
    Movie: {
        title: "Title",
        director: "Director",
        actors: "Actors",
        release_date: "Release Date",
        currently_at: "Currently At",
        synopsis: "Synopsis",
        notes: "Notes",
    },
    Series: {
        title: "Title",
        actors: "Actors",
        num_seasons: "Number of Seasons",
        num_episodes: "Number of Episodes",
        currently_at: "Currently At",
        synopsis: "Synopsis",
        notes: "Notes",
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
    }
};

const placeholders = {
    Book: {
        title: "Enter the book title",
        author: "Enter the author's name",
        language: "Enter the language",
        read_pages: "Enter the number of read pages",
        total_pages: "Enter the total number of pages",
        synopsis: "Enter a brief synopsis",
        notes: "Enter any additional notes",
        tags: "Enter any tags separated by commas: tag1, tag2, tag3"
    },
    Movie: {
        title: "Enter the movie title",
        director: "Enter the director's name",
        actors: "Enter the main actors",
        release_date: "Enter the release date: YYYY/MM/DD",
        currently_at: "Enter your current position: HH:MM:SS",
        notes: "Enter any additional notes",
        synopsis: "Enter a brief synopsis",
        tags: "Enter any tags separated by commas: tag1, tag2, tag3"
    },
    Series: {
        title: "Enter the series title",
        actors: "Enter the main actors",
        num_seasons: "Enter the number of seasons",
        num_episodes: "Enter the number of episodes",
        currently_at: "Enter your current position: Season 4, Episode 3",
        synopsis: "Enter a brief synopsis",
        notes: "Enter any additional notes",
        tags: "Enter any tags separated by commas: tag1, tag2, tag3"
    },
    Fanfic: {
        title: "Enter the fanfic title",
        author: "Enter the author's name",
        fandom: "Enter the fandom",
        language: "Enter the language",
        words: "Enter the word count",
        read_chapters: "Enter the number of read chapters",
        total_chapters: "Enter the total number of chapters",
        relationships: "Enter the relationships",
        synopsis: "Enter a brief synopsis",
        notes: "Enter any additional notes",
        tags: "Enter any tags separated by commas: tag1, tag2, tag3"
    }
};

const enviar = async () => {
    errors.value = {}; // Clear errors before submitting
    try {
        // console.log(dataInput.value);
        const tags = dataInput.value.tags;
        const tagsSeparados = tags ? tags.split(',') : [];
        const bookmarkableParams = await getParamsBookmark(selected_type.value, dataInput.value);
        const dataToSend = {
            data: {
                type: "bookmarks",
                attributes: {
                    title: dataInput.value.title,
                    synopsis: dataInput.value.synopsis,
                    notes: dataInput.value.notes,
                    bookmarkable: bookmarkableParams,
                    bookmarkable_type: selected_type.value,
                    tags: tagsSeparados
                }
            }
        };

        const response = await axios.post('api/v1/bookmarks', dataToSend);
        // console.log('Response:', response);

        if (response.status === 201 || response.status === 200) {
            window.location.href = "/bookmarks";
        } else {
            console.error('Error: Unexpected response status', response.status);
        }
    } catch (error) {
        if (error.response && error.response.status === 422) {
            const responseErrors = error.response.data.errors;
            responseErrors.forEach((errorItem) => {
                const pointer = errorItem.source.pointer.replace('/data/attributes/bookmarkable/', '').replace('/', '.');
                const errorDetail = errorItem.detail.replace('data.attributes.bookmarkable.', '');
                if (errors.value[pointer]) {
                    errors.value[pointer].push(errorDetail);
                } else {
                    errors.value[pointer] = [errorDetail];
                }
            });
            console.log(errors);
        } else if (error.response && error.response.status === 400) {
            if(error.response.data.message) {
                errors.value['tags'] = error.response.data.message;
            }
            console.log(errors);
        } else {
            console.error('Error submitting form:', error);
        }
    }
};
</script>
