<template>

    <Head :title="$t('Collections')" />
    <AuthenticatedLayout>
        <div>
            <main class="flex-1 p-5 max-w-7xl mx-auto">
                <div class="mx-auto">
                    <Breadcrumbs :items="[$t('Home'), $t('Collections')]"></Breadcrumbs>
                </div>
                <div class="flex flex-col mx-auto ">
                    <div class="text-3xl font-bold mx-auto my-4"></div>
                    <!-- BUTTONS AND INPUT -->
                    <div class="flex justify-between w-full">
                        <div>
                            <PrimaryButton>
                                <Link href='/createcollection'>{{ $t('Create Collection') }}</Link>
                            </PrimaryButton>
                        </div>
                    </div>
                    <div class="mt-4 p-6 rounded-md bg-gradient-to-b from-sky-200 to-emerald-200">
                        <div class="flex justify-between gap-10">
                            <div class="flex flex-col md:w-4/6 h-auto rounded-sm space-y-3">
                                <!-- Cards -->
                                <Card v-for="c in collections" :key="c.id" class="ml-0 h-full flex-auto flex-col bg-stone-50"
                                    :modifyLink="'/collections/' + c.id" :id="c.id" nameButton="SHOW" candeletecollection="true">
                                    <div>
                                        <p class="text-2xl font-medium">{{ c.attributes.name }}</p>
                                        <p class="text-gray-700"><strong class="font-medium">
                                                {{$t('Description')}}: </strong>{{ c.attributes.description }}
                                        </p>
                                        <p class="text-gray-700"><strong class="font-medium">
                                                {{$t('Tags')}}: </strong>{{ c.attributes.tags }}
                                        </p>
                                    </div>
                                </Card>
                                <div v-if="isLoading" id="empty" class="text-3xl m-auto">
                                    <h1>{{ $t('Cargando Colecciones...') }}</h1>
                                </div>
                                <div v-else-if="!collections || collections.length === 0" id="empty"
                                    class="text-3xl m-auto">
                                    <h1>{{ $t('No Collections found') }}</h1>
                                </div>
                            </div>
                            <div class="w-2/6 flex flex-col border border-gray-400 rounded-md shadow-lg min-h-72 bg-gradient-to-b from-sky-300 to-emerald-300">
                                <div class="pt-4 px-4 ">
                                    <InputLabel :value="$t('SORT BY')"></InputLabel>
                                    <select id="sort" name="sort" v-model="sortBy"
                                        class="w-full p-2 border border-gray-300 rounded-md bg-stone-50">
                                        <option value="name">{{ $t('Title') }}</option>
                                        <option value="created_at">{{ $t('Created at') }}</option>
                                        <option value="updated_at">{{ $t('Updated at') }}</option>
                                    </select>
                                </div>
                                <div class="pt-2 px-4">
                                    <InputLabel :value="$t('ORDER')"></InputLabel>
                                    <select id="order" name="order" v-model="order"
                                        class="w-full p-2 border border-gray-300 rounded-md bg-stone-50">
                                        <option value="asc">{{ $t('Ascending') }}</option>
                                        <option value="desc">{{ $t('Descending') }}</option>
                                    </select>
                                </div>
                                <div class="pt-2 px-4">
                                    <InputLabel :value="$t('TAGS')"></InputLabel>
                                    <TextInput class="w-64 bg-stone-50" v-model="tags"></TextInput>
                                </div>
                                <div class="pt-4 px-4">
                                    <PrimaryButton @click="sortCollections">
                                        {{ $t('Search Collections') }}
                                    </PrimaryButton>
                                </div>
                            </div>
                        </div>
                        <div class="mt-15">
                            <v-pagination v-model="currentPage" :length="lastPage"
                                @click="getCollections"></v-pagination>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import axios from 'axios';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Card from '@/Components/Card.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Breadcrumbs from '@/Components/Breadcrumbs.vue';

import { nextPage, prevPage, formatDate } from '@/utils/functions';

const currentPage = ref(1);
const lastPage = ref(null);
const sortBy = ref('updated_at');
const sort = ref('');
const isLoading = ref(false);
const order = ref('desc');
const collections = ref([]);
const tags = ref('');

const sortCollections = () => {
    if (sortBy.value) {
        sort.value = (order.value === 'desc' ? '-' : '') + sortBy.value;
    } else {
        sort.value = ''; // No sort parameter sent, so backend can use its default
    }
    tags.value = tags.value.replace(/\s+/g, '');
    currentPage.value = 1; // Reset to first page
    getCollections();
};

const getCollections = async () => {
    isLoading.value = true;

    let params = {
        'page[size]': 8,
        'page[number]': currentPage.value
    };

    if (sort.value) {
        params.sort = sort.value;
    }

    if (tags.value) {
        params.tags = tags.value;
    }

    try {
        const response = await axios.get('api/v1/collections', { params });
        const res = response.data;
        const data = res.data;
        // console.log(data);
        currentPage.value = res.meta.current_page;
        lastPage.value = res.meta.last_page;

        console.log(data);
        let tags = [];
        // TAGS
        for (let i = 0; i < data.length; i++) {
            tags = [];
            for (let x = 0; x < data[i].attributes.tags.length; x++) {
                tags.push(data[i].attributes.tags[x].name);
            }
            data[i].attributes.tags = tags.join(',');
            // console.log(data[i].attributes);
            collections.value = data;
        }
        console.log(data);
    } catch (error) {
        console.log('Ha ocurrido un error: ' + error);
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    getCollections();
});

nextPage(currentPage, lastPage);
prevPage(currentPage);
</script>
