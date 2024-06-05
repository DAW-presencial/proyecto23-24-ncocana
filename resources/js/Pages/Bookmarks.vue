<template>

    <Head :title="$t('Bookmarks')" />
    <AuthenticatedLayout>
        <main class="flex-1 p-5 max-w-7xl mx-auto">
            <div class="mx-auto">
                <Breadcrumbs :items="[$t('Home'), $t('Bookmarks')]"></Breadcrumbs>
            </div>
            <div class="flex flex-col mx-auto">
                <div class="text-3xl font-bold mx-auto my-4">
                </div>
                <!-- BOTONES Y INPUT -->
                <div class="flex justify-between w-full">
                    <div>
                        <PrimaryButton>
                            <Link href='/createbookmark'>{{ $t('Create Bookmark') }}</Link>
                        </PrimaryButton>
                    </div>
                </div>
                <div class="mt-4 p-6 rounded-md  bg-gradient-to-b from-sky-200 to-emerald-200 rounded-md">
                    <div class="flex justify-between gap-10">
                        <div class='flex flex-col w-4/6 h-auto rounded-sm space-y-3'>
                            <!-- Cards -->
                            <Card v-for="(b) in bookmarks" :key="b.id" class="ml-0 h-auto bg-stone-50"
                                :modifyLink="'/bookmarks/' + b.id" :id="b.id" nameButton="SHOW" candelete=true>
                                <div v-if="b.tipo == 'App\\Models\\Movie'">
                                    <div class="bg-red-100 inline-block rounded-lg px-5 shadow-lg  mb-2">
                                        <div>
                                            <p class="p-15">{{ $t('Movie') }}</p>
                                        </div>
                                    </div>
                                    <h3 class="text-xl font-medium">{{ b.title }}</h3>
                                </div>

                                <div v-else-if="b.tipo == 'App\\Models\\Fanfic'">
                                    <div class="bg-amber-100 inline-block rounded-lg px-5 shadow-lg mb-2">
                                        <div>
                                            <p class="p-15">{{ $t('Fanfic') }}</p>
                                        </div>
                                    </div>
                                    <h3 class="text-xl font-medium">{{ b.title }}</h3>
                                </div>

                                <div v-else-if="b.tipo == 'App\\Models\\Book'">
                                   <div class="bg-lime-100 inline-block rounded-lg px-5 shadow-lg mb-2">
                                        <div>
                                            <p class="p-15">{{ $t('Book') }}</p>
                                        </div>
                                    </div>
                                    <h3 class="text-xl font-medium">{{ b.title }}</h3>
                                </div>

                                <div v-else-if="b.tipo == 'App\\Models\\Series'">
                                      <div class="bg-cyan-100 inline-block rounded-lg px-5 shadow-lg mb-2">
                                        <div>
                                            <p class="p-15">{{ $t('Series') }}</p>
                                        </div>
                                    </div>
                                    <h3 class="text-xl font-medium">{{ b.title }}</h3>

                                </div>
                            </Card>
                            <div v-if="isLoading == true" id="empty" class="text-3xl m-auto">
                                <h1>{{ $t('Loading Bookmarks...') }}</h1>
                            </div>
                            <div v-else-if="!bookmarks.length && isLoading == false" id="empty" class="text-3xl m-auto">
                                <h1>{{ $t('No Bookmarks found') }}</h1>
                            </div>
                        </div>
                        <div class='w-2/6 flex flex-col bg-gradient-to-b from-sky-300 to-emerald-300 rounded-md shadow-lg min-h-72'>
                            <div class="pt-4 px-4">
                                <InputLabel :value="$t('SORT BY')"></InputLabel>
                                <select id="sort" name="sort" v-model="sortBy"
                                    class="w-full p-2 border border-gray-300 rounded-md bg-stone-50">
                                    <option value="bookmarkable_type">{{ $t('Type') }}</option>
                                    <option value="title">{{ $t('Title') }}</option>
                                    <option value="created_at">{{ $t('Created at') }}</option>
                                    <option value="updated_at" selected>{{ $t('Updated at') }}</option>
                                </select>
                            </div>
                            <div class="pt-2 px-4">
                                <InputLabel :value="$t('ORDER')"></InputLabel>
                                <select id="order" name="order" v-model="order"
                                    class="w-full p-2 border border-gray-300 rounded-md bg-stone-50">
                                    <option value="asc">{{ $t('Ascending') }}</option>
                                    <option value="desc" selected>{{ $t('Descending') }}</option>
                                </select>
                            </div>

                            <div class="pt-2 px-4">
                                <InputLabel :value="$t('TAGS')"></InputLabel>
                                <TextInput class="w-64 bg-stone-50" v-model="tags"></TextInput>
                            </div>
                            <div class="pt-4 px-4">
                                <PrimaryButton @click="sortBookmarks">
                                    {{ $t('Search Bookmarks') }}
                                </PrimaryButton>
                            </div>
                        </div>
                    </div>
                    <div class="mt-15">
                        <v-pagination v-model="currentPage" :length="lastPage" @click="getBookmarks"></v-pagination>
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
const tags = ref('');
const bookmarks = ref([]);
const sortBy = ref('updated_at');
const sort = ref('');
const order = ref('desc');
const isLoading = ref(false);

const sortBookmarks = () => {
    if (sortBy.value) {
        sort.value = (order.value === 'desc' ? '-' : '') + sortBy.value;
    } else {
        sort.value = ''; // No sort parameter sent, so backend can use its default
    }
    tags.value = tags.value.replace(/\s+/g, '');
    currentPage.value = 1; // Reset to first page
    getBookmarks();
};

const getBookmarks = async () => {
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

    await axios.get(`api/v1/bookmarks`, { params })
        .then(response => {
            const res = response.data;
            const data = res.data;

            // Pagination
            currentPage.value = res.meta.current_page;
            lastPage.value = res.meta.last_page;

            bookmarks.value = [];
            for (let i = 0; i < data.length; i++) {
                let json = data[i].attributes.bookmarkable;
                json.tipo = data[i].attributes.bookmarkable_type;
                json.id = data[i].id;
                json.title = data[i].attributes.title;
                json.synopsis = data[i].attributes.synopsis;
                json.notes = data[i].attributes.notes;

                json.tags = [];
                for (let x = 0; x < data[i].attributes.tags.length; x++) {
                    json.tags.push(data[i].attributes.tags[x].name);
                }
                json.tags = json.tags.join(',');

                bookmarks.value.push(json);
                isLoading.value = false;
        
            }
        })
        .catch(error => console.log('Ha ocurrido un error: ' + error));
    isLoading.value = false;
};

nextPage(currentPage, lastPage);
prevPage(currentPage);


onMounted(() => {
    getBookmarks();
});

</script>
