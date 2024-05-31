<template>

    <Head :title="$t('Collections')" />
    <AuthenticatedLayout>
        <main class="flex-1 p-5 max-w-7xl mx-auto">
            <div class="mx-auto">
                <Breadcrumbs :items="[$t('Home'), $t('Collections')]"></Breadcrumbs>
            </div>
            <div class="flex flex-col mx-auto">
                <div class="text-3xl font-bold mx-auto my-4">
                </div>
                <!-- BOTONES Y INPUT -->
                <div class="flex justify-between w-full">
                    <div>
                        <PrimaryButton>
                            <Link href='/createcollection'>{{ $t('Create Collection') }}</Link>
                        </PrimaryButton>
                    </div>
                    <!-- <div class="flex gap-4">
                        <TextInput class="w-64" v-model="buscar"></TextInput>
                        <PrimaryButton @click="getCollections">Search</PrimaryButton>
                    </div> -->
                </div>
                <div class="mt-4 p-6 rounded-md  bg-stone-50">
                    <div class="flex justify-between gap-10">
                        <div class='flex flex-col w-4/6 h-auto rounded-sm space-y-3'>
                            <!-- Cards -->
                            <Card v-for="(c) in collections" :key="c.id" class="ml-0 h-full flex flex-col"
                                :modifyLink="'/collections/' + c.id" :id="c.id" nameButton="SHOW" candelete=true>
                                <div>
                                    <h1 class="text-2xl font-medium mb-4">{{ c.attributes.name }}</h1>
                                </div>
                            </Card>
                            <div v-if="isLoading == true" id="empty" class="text-3xl m-auto">
                                <h1>{{ $t('Cargando Colecciones...') }}</h1>
                            </div>
                            <div v-else-if="!collections" id="empty" class="text-3xl m-auto">
                                <h1>{{ $t('No Collections found') }}</h1>
                            </div>
                        </div>
                        <div class='w-2/6 flex flex-col border border-gray-400 rounded-md shadow-lg min-h-72'>
                            <div class="pt-4 px-4">
                                <InputLabel :value="$t('SORT BY')"></InputLabel>
                                <select id="sort" name="sort" v-model="sortBy"
                                    class="w-full p-2 border border-gray-300 rounded-md">
                                    <!-- <option value="bookmarkable_type">{{ $t('Type') }}</option> -->
                                    <option value="title">{{ $t('Title') }}</option>
                                    <option value="created_at">{{ $t('Created at') }}</option>
                                    <option value="updated_at" selected>{{ $t('Updated at') }}</option>
                                </select>
                            </div>
                            <div class="pt-2 px-4">
                                <InputLabel :value="$t('ORDER')"></InputLabel>
                                <select id="order" name="order" v-model="order"
                                    class="w-full p-2 border border-gray-300 rounded-md">
                                    <option value="asc">{{ $t('Ascending') }}</option>
                                    <option value="desc" selected>{{ $t('Descending') }}</option>
                                </select>
                            </div>
                            <div class="pt-4 px-4">
                                <PrimaryButton @click="sortCollections">
                                    {{ $t('Search Collections') }}
                                </PrimaryButton>
                            </div>
                        </div>
                    </div>
                    <div class="mt-15">
                        <v-pagination v-model="currentPage" :length="lastPage" @click="getCollections"></v-pagination>
                    </div>
                </div>
            </div>
        </main>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Card from '@/Components/Card.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { onMounted, ref } from 'vue';
import axios from 'axios';
import Breadcrumbs from '@/Components/Breadcrumbs.vue';

// FUNCTIONS
import { nextPage, prevPage, formatDate } from '@/utils/functions';

// Variables
const currentPage = ref(1);
const lastPage = ref(null);
const sortBy = ref('updated_at');
const sort = ref('');
const isLoading = ref(false);
const order = ref('desc');
const collections = ref();

const sortCollections = () => {
    if (sortBy.value) {
        sort.value = (order.value === 'desc' ? '-' : '') + sortBy.value;
    } else {
        sort.value = ''; // No sort parameter sent, so backend can use its default
    }
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

    await axios.get(`api/v1/collections`, { params })
        .then(response => {
            const res = response.data;
            const data = res.data;
            console.log(res);
            currentPage.value = res.meta.current_page;
            lastPage.value = res.meta.last_page;

            collections.value = [];
            collections.value = data;

            isLoading.value = false;

        })
        .catch(error => console.log('Ha ocurrido un error: ' + error));
    isLoading.value = false;
};

nextPage(currentPage, lastPage);
prevPage(currentPage);


onMounted(() => {
    getCollections();
});

</script>
