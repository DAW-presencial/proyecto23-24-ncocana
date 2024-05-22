<template>

    <Head title="{{$t('Search Advanced')}}" />

    <AuthenticatedLayout>
        <main class="flex-1 p-4">
            <div class="mx-auto max-w-7xl mt-6 gap-4">
                <div class="text-xl font-bold mx-auto my-4">
                    <h1>{{$t('Create Bookmark')}}</h1>
                </div>
                <!-- TYPES -->
                <label for="type" class="block text-sm font-medium leading-6 text-gray-900"> {{$t('type')}} </label>
                <div class="mt-2">
                    <select id="type"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        v-model="selected_type">
                        <option v-for="t in types" :key="t" :value="t"> {{ t }}</option>
                    </select>
                </div>

                <!-- DYNAMIC FIELDS -->
                <div v-if="selected_type">
                    <form @submit.prevent="enviar"> <!-- Added prevent modifier to prevent default form submission -->
                        <div v-for="f in fields[selected_type]" :key="f">
                            <label :for="f" class="block text-sm font-medium leading-6 text-gray-900">{{ f }}</label>
                            <div class="my-2">
                                <input type="text" :name="f" :id="f" :autocomplete="f" v-model="dataInput[f]"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    :required="true" /> <!-- Added required attribute -->
                            </div>
                        </div>
                        <PrimaryButton>{{$t('Send')}}</PrimaryButton>
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

const types = ["Book", "Movie", "Series", "Fanfic"];
const selected_type = ref(null);
const dataInput = ref({});

const fields = {
    Book: ["title", "author", "language", "read_pages", "total_pages", "synopsis", "notes", "tags"],
    Movie: ["title", "director", "actors", "release_date", "currently_at", "notes", "synopsis", "tags"],
    Series: ["title", "actors", "num_seasons", "num_episodes", "currently_at", "synopsis", "notes", "tags"],
    Fanfic: ["title", "author", "fandom", "language", "words", "read_chapters", "total_chapters", "relationships", "synopsis", "notes", "tags"]
};

const enviar = async () => {
    try {
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

        console.log('Sending data:', dataToSend);

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
