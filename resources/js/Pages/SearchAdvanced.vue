<template>

    <Head :title="$t('Advanced Search')" />
    <AuthenticatedLayout>
        <main class="flex-1 p-4">
            <div class="mx-auto max-w-7xl sm:m-6 gap-4">
                <div class="pb-4 mx-0">
                    <Breadcrumbs :items="[$t('Home'), $t('Advanced Search')]"></Breadcrumbs>
                </div>
                <div class="bg-[#C2CFD8] p-6 rounded-lg py-3">
                    <div class="text-xl font-bold mx-auto mb-2 sm:m-4">
                        <h1>{{ $t('Bookmark Advanced Search') }}</h1>
                    </div>
                    <!-- FORM -->
                    <form @submit.prevent="enviar">
                        <!-- DYNAMIC FIELDS -->
                        <div v-for="(label, field) in fields" :key="field">
                            <label :for="field" class="block text-sm font-medium leading-6 text-gray-900">{{ $t(label)
                                }}</label>
                            <div class="my-2">
                                <div v-if="field == 'bookmarkable_type'">
                                    <div class="mt-2 bg-neutral-100 rounded-lg">
                                        <select :id="field" :name="field" :autocomplete="field"
                                            v-model="dataInput[field]"
                                            class="bg-neutral-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            <option v-for="t in types" :key="t" :value="t">{{ $t(t) }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div v-else-if="field == 'created_at' || field == 'updated_at'">
                                    <input type="month" :name="field" :id="field" :autocomplete="field"
                                        v-model="dataInput[field]"
                                        class="bg-neutral-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                </div>
                                <div v-else>
                                    <input type="text" :name="field" :id="field" :autocomplete="field"
                                        v-model="dataInput[field]"
                                        class="bg-neutral-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 pt-3 pb-6">
                            <PrimaryButton>{{ $t('Send') }}</PrimaryButton>
                        </div>
                    </form>
                </div>

                <div v-if="isLoading == true" id="empty" class="text-3xl my-8">
                    <h1>{{ $t('Cargando Marcadores...') }}</h1>
                </div>
                <div v-if="resultados.length">
                    <h2 class="text-2xl font-bold m-6 mx-0 sm:m-12">{{ $t('Resultados') }}:</h2>
                    <div class="flex flex-col gap-y-4">
                        <Card v-for="(resultado, index) in resultados" :key="index" class="ml-0 my-2 bg-neutral-100"
                            :modifyLink="'/bookmarks/' + resultado.id" :id="resultado.id" nameButton="SHOW"
                            candelete=true>
                            <!-- Aquí muestra los datos del resultado en la tarjeta -->

                            <!-- Type -->
                            <p class="bg-red-100 inline-block rounded-lg px-5 shadow-lg  mb-2"
                                v-if="resultado.attributes.bookmarkable_type === 'App\\Models\\Movie'">
                                {{ $t('Movie') }}</p>
                            <p class="bg-cyan-100 inline-block rounded-lg px-5 shadow-lg mb-2"
                                v-if="resultado.attributes.bookmarkable_type === 'App\\Models\\Series'">{{ $t('Series')
                                }}
                            </p>
                            <p class="bg-lime-100 inline-block rounded-lg px-5 shadow-lg mb-2"
                                v-if="resultado.attributes.bookmarkable_type === 'App\\Models\\Book'">
                                {{ $t('Book') }}</p>
                            <p class="bg-amber-100 inline-block rounded-lg px-5 shadow-lg mb-2"
                                v-if="resultado.attributes.bookmarkable_type === 'App\\Models\\Fanfic'">{{ $t('Fanfic')
                                }}
                            </p>

                            <h3 class="text-xl font-medium">{{ resultado.attributes.title }}</h3>

                        </Card>
                    </div>
                    <div class="mt-6">
                        <v-pagination v-model="currentPage" :length="lastPage" @click="enviar"></v-pagination>
                    </div>
                </div>
                <div v-else-if="noBook == 1 && resultados.length == 0 && isLoading == false" id="empty"
                    class="text-3xl my-8">
                    <h1>{{ $t('No Bookmarks found') }}</h1>
                </div>
            </div>
        </main>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Breadcrumbs from '@/Components/Breadcrumbs.vue';
import { formatDate } from '@/utils/functions';
import { Head, usePage } from "@inertiajs/vue3";
import Card from '@/Components/Card.vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from "axios";
import moment from 'moment';


const isLoading = ref(false);
const noBook = ref(0);
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
    isLoading.value = true;
    let params = {
        'filter[bookmarkable_type]': dataInput.value.bookmarkable_type,
        'filter[title]': dataInput.value.title,
        'filter[notes]': dataInput.value.notes,
        'filter[synopsis]': dataInput.value.synopsis,
        'page[size]': 5,
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
        isLoading.value = false;
        noBook.value = 1;

    } catch (error) {
        console.error("Error fetching bookmarks:", error);
        isLoading.value = false;

    }
};

onMounted(async () => {
    // Get the 'type' query parameter from the route
    const { props } = usePage();
    const { type } = props;
    console.log(type);
    if (type) {
        // Map the query parameter to the corresponding type
        dataInput.value.bookmarkable_type = type;
        await enviar();
    }
});
</script>