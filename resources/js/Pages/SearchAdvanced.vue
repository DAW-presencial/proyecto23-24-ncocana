<template>


    <Head :title="$t('Advanced Search')" />
    <AuthenticatedLayout>
        <main class="flex-1 p-4">
            <div class="mx-auto max-w-7xl mt-6 gap-4">

                <div class="pb-4">
                    <Breadcrumbs :items="['Home', 'Advanced Search']"></Breadcrumbs>
                </div>
                <div class="text-xl font-bold mx-auto my-4">
                    <h1>{{ $t('Bookmark Advanced Search') }}</h1>

                </div>
                <!-- FORM -->
                <form @submit.prevent="enviar">
                    <!-- DYNAMIC FIELDS -->
                    <div v-for="(label, field) in fields" :key="field">
                        <label :for="field" class="block text-sm font-medium leading-6 text-gray-900">{{ label
                            }}</label>
                        <div class="my-2">
                            <div v-if="field == 'bookmarkable_type'">
                                <div class="mt-2">
                                    <select :id="field" :name="field" :autocomplete="field" v-model="dataInput[field]"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option v-for="t in types" :key="t" :value="t">{{ t }}</option>
                                    </select>
                                </div>
                            </div>
                            <div v-else-if="field == 'created_at' || field == 'updated_at'">
                                <input type="month" :name="field" :id="field" :autocomplete="field"
                                    v-model="dataInput[field]"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                            <div v-else>
                                <input type="text" :name="field" :id="field" :autocomplete="field"
                                    v-model="dataInput[field]"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>
                    </div>
                    <PrimaryButton>Send</PrimaryButton>
                </form>

                <div v-if="resultados.length">
                    <h2 class="text-2xl font-bold my-12">Resultados:</h2>
                    <Card v-for="(resultado, index) in resultados" :key="index" class="ml-0 my-4">
                        <!-- Aquí muestra los datos del resultado en la tarjeta -->

                        <!-- Type -->
                        <h1 class="text-2xl font-medium mb-4"
                            v-if="resultado.attributes.bookmarkable_type === 'App\\Models\\Movie'">
                            Movie</h1>
                        <h1 class="text-2xl font-medium mb-4"
                            v-if="resultado.attributes.bookmarkable_type === 'App\\Models\\Series'">Series</h1>
                        <h1 class="text-2xl font-medium mb-4"
                            v-if="resultado.attributes.bookmarkable_type === 'App\\Models\\Book'">
                            Book</h1>
                        <h1 class="text-2xl font-medium mb-4"
                            v-if="resultado.attributes.bookmarkable_type === 'App\\Models\\Fanfic'">Fanfic</h1>

                        <p><strong>Title:</strong> {{ resultado.attributes.title }}</p>

                        <!-- Campos específicos dependiendo del tipo de bookmark -->

                        <div v-if="resultado.attributes.bookmarkable">

                            <!-- BOOK -->
                            <template v-if="resultado.attributes.bookmarkable_type === 'App\\Models\\Book'">
                                <p><strong>Author:</strong> {{ resultado.attributes.bookmarkable.author }}</p>
                                <p><strong>Language:</strong> {{ resultado.attributes.bookmarkable.language }}</p>
                                <p><strong>Read Pages:</strong> {{ resultado.attributes.bookmarkable.read_pages }}</p>
                                <p><strong>Total Pages:</strong> {{ resultado.attributes.bookmarkable.total_pages }}</p>
                            </template>

                            <!-- MOVIE -->
                            <template v-else-if="resultado.attributes.bookmarkable_type === 'App\\Models\\Movie'">
                                <p><strong>Director:</strong> {{ resultado.attributes.bookmarkable.director }}</p>
                                <p><strong>Actors:</strong> {{ resultado.attributes.bookmarkable.actors }}</p>
                                <p><strong>Release Date:</strong> {{
        formatDate(resultado.attributes.bookmarkable.release_date) }}
                                </p>
                                <p><strong>Currently at:</strong> {{ resultado.attributes.bookmarkable.currently_at }}
                                </p>
                            </template>

                            <!-- SERIES -->
                            <template v-else-if="resultado.attributes.bookmarkable_type === 'App\\Models\\Series'">
                                <p><strong>Actors:</strong> {{ resultado.attributes.bookmarkable.actors }}</p>
                                <p><strong>Number of Seasons:</strong> {{
        resultado.attributes.bookmarkable.num_seasons }}</p>
                                <p><strong>Number of Episodes:</strong> {{
        resultado.attributes.bookmarkable.num_episodes }}</p>
                                <p><strong>Currently at:</strong> {{ resultado.attributes.bookmarkable.currently_at
                                    }}</p>
                            </template>

                            <!-- FANFICS -->
                            <template v-else-if="resultado.attributes.bookmarkable_type === 'App\\Models\\Fanfic'">
                                <p><strong>Author:</strong> {{ resultado.attributes.bookmarkable.author }}</p>
                                <p><strong>Fandom:</strong> {{
        resultado.attributes.bookmarkable.fandom }}</p>
                                <p><strong>Relationships:</strong> {{
        resultado.attributes.bookmarkable.relationships }}</p>
                                <p><strong>Language:</strong> {{ resultado.attributes.bookmarkable.language
                                    }}</p>
                                <p><strong>Words:</strong> {{ resultado.attributes.bookmarkable.words }}</p>
                                <p><strong>Read Chapters:</strong> {{ resultado.attributes.bookmarkable.read_chapters }}
                                </p>
                                <p><strong>Total Chapters:</strong> {{ resultado.attributes.bookmarkable.total_chapters
                                    }}</p>
                            </template>
                            <!-- Agrega más condicionales según los tipos de bookmark disponibles -->
                        </div>
                        <p><strong>Notes:</strong> {{ resultado.attributes.notes }}</p>
                        <p><strong>Synopsis:</strong> {{ resultado.attributes.synopsis }}</p>

                        <!-- TAGS -->
                        <div v-if="resultado.attributes.tags.length">
                            <p><strong>Tags:</strong></p>
                            <ul>
                                <li v-for="tag in resultado.attributes.tags" :key="tag.id">
                                    {{ tag.name }}
                                </li>
                            </ul>
                        </div>
                    </Card>
                    <div class="mt-6">
                        <v-pagination v-model="currentPage" :length="lastPage" @click="enviar"></v-pagination>
                    </div>
                </div>
                <!-- <div v-else class="text-3xl m-auto">
                    <h1>No results found</h1>
                </div> -->
            </div>
        </main>
    </AuthenticatedLayout>
</template>

<script setup>
import Breadcrumbs from '@/Components/Breadcrumbs.vue';
import { formatDate } from '@/utils/functions';
import { Head } from "@inertiajs/vue3";
import Card from '@/Components/Card.vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from "axios";
import { ref } from "vue";
import moment from 'moment';


const currentPage = ref(1);
const lastPage = ref(null);
const dataInput = ref({
    bookmarkable_type: '',
    title: '',
    created_at: '',
    updated_at: ''

});

const types = ["", "Book", "Movie", "Series", "Fanfic"];

const fields = {
    bookmarkable_type: "Type",
    title: "Title",
    notes: "Notes",
    synopsis: "Synopsis",
    created_at: "Created at",
    updated_at: "Updated at"
};

const resultados = ref([]);

const enviar = async () => {
    let params = {
        'filter[bookmarkable_type]': dataInput.value.bookmarkable_type,
        'filter[title]': dataInput.value.title,
        'filter[notes]': dataInput.value.notes,
        'filter[synopsis]': dataInput.value.synopsis,
        'page[size]': 2,
        'page[number]': currentPage.value
    };

    // Verificar si hay fecha de creación
    if (dataInput.value.created_at) {
        let monthCreate = moment(dataInput.value.created_at).format('MM');
        let yearCreate = moment(dataInput.value.created_at).format('YYYY');
        params['filter[monthCreate]'] = monthCreate;
        params['filter[yearCreate]'] = yearCreate;
    }

    // Verificar si hay fecha de actualización
    if (dataInput.value.updated_at) {
        let monthUpdate = moment(dataInput.value.updated_at).format('MM');
        let yearUpdate = moment(dataInput.value.updated_at).format('YYYY');
        params['filter[monthUpdate]'] = monthUpdate;
        params['filter[yearUpdate]'] = yearUpdate;
    }

    try {
        const response = await axios.get('api/v1/bookmarks', { params });
        lastPage.value = response.data.meta.last_page;
        currentPage.value = response.data.meta.current_page;
        resultados.value = response.data.data;
    } catch (error) {
        console.error("Error fetching bookmarks:", error);
    }
};
</script>
