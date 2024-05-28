<template>

    <Head title="Bookmarks" />
    <AuthenticatedLayout>
        <main class="flex-1 p-5 max-w-7xl mx-auto">
            <div class="mx-auto">
                <Breadcrumbs :items="['Home', $t('Bookmark')]"></Breadcrumbs>
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
                    <!-- <div class="flex gap-4">
                        <TextInput class="w-64" v-model="buscar"></TextInput>
                        <PrimaryButton @click="getBookmarks">Search</PrimaryButton>
                    </div> -->
                </div>
                <div class="mt-4 p-6 rounded-md max-h-screen bg-stone-50">
                    <div class="flex justify-between gap-10">
                        <div class='flex flex-col w-4/6 h-auto rounded-sm space-y-6'>
                            <!-- Cards -->
                            <Card v-for="(b) in bookmarks" :key="b.id" class="ml-0" :modifyLink="'/bookmarks/' + b.id"
                                :id="b.id" nameButton="SHOW" candelete=true>
                                <div v-if="b.tipo == 'App\\Models\\Movie'">
                                    <h1 class="text-2xl font-medium mb-4">{{ $t('Movie') }}</h1>
                                    <p><strong>{{ $t('Title') }}: </strong>{{ b.title }}</p>
                                    <p><strong>{{ $t('Director') }}: </strong>{{ b.director }}</p>
                                    <p><strong>{{ $t('Actors') }}: </strong>{{ b.actors }}</p>
                                    <p><strong>{{ $t('Release date') }}: </strong>{{ formatDate(b.release_date) }}</p>
                                    <p><strong>{{ $t('Currently at') }}: </strong>{{ b.currently_at }}</p>
                                    <p class="mt-2"><strong>{{ $t('Notes') }}: </strong>{{ b.notes }}</p>
                                    <p class="mt-2"><strong>{{ $t('Synopsis') }}: </strong>{{ b.synopsis }}</p>
                                    <p class="mt-2"><strong>{{ $t('Tags') }}: </strong>{{ b.tags }}</p>
                                </div>

                                <div v-if="b.tipo == 'App\\Models\\Fanfic'">
                                    <h1 class="text-2xl font-medium mb-4">Fanfic</h1>
                                    <p><strong>{{ $t('Title') }}: </strong>{{ b.title }}</p>
                                    <p><strong>{{ $t('Author') }}: </strong>{{ b.author }}</p>
                                    <p><strong>{{ $t('Fandom') }}: </strong>{{ b.fandom }}</p>
                                    <p><strong>{{ $t('Original fiction') }}: </strong>{{ b.relationships }}</p>
                                    <p><strong>{{ $t('Language') }}: </strong>{{ b.language }}</p>
                                    <p><strong>{{ $t('Words') }}: </strong>{{ b.words }}</p>
                                    <p><strong>{{ $t('Read chapters') }}: </strong>{{ b.read_chapters }}</p>
                                    <p><strong>{{ $t('Total chapters') }}: </strong>{{ b.total_chapters }}</p>
                                    <p class="mt-2"><strong>{{ $t('Notes') }}: </strong>{{ b.notes }}</p>
                                    <p class="mt-2"><strong>{{ $t('Synopsis') }}: </strong>{{ b.synopsis }}</p>
                                    <p class="mt-2"><strong>{{ $t('Tags') }}: </strong>{{ b.tags }}</p>
                                </div>

                                <div v-if="b.tipo == 'App\\Models\\Book'">
                                    <h1 class="text-2xl font-medium mb-4">Book</h1>
                                    <p><strong>{{ $t('Title') }}: </strong>{{ b.title }}</p>
                                    <p><strong>{{ $t('Author') }}: </strong>{{ b.author }}</p>
                                    <p><strong>{{ $t('Language') }}: </strong>{{ b.language }}</p>
                                    <p><strong>{{ $t('Read pages') }}: </strong>{{ b.read_pages }}</p>
                                    <p><strong>{{ $t('Total pages') }}: </strong>{{ b.total_pages }}</p>
                                    <p><strong>{{ $t('Notes') }}: </strong>{{ b.notes }}</p>
                                    <p class="mt-2"><strong>{{ $t('Synopsis') }}: </strong>{{ b.synopsis }}</p>
                                    <p class="mt-2"><strong>{{ $t('Tags') }}: </strong>{{ b.tags }}</p>
                                </div>

                                <div v-if="b.tipo == 'App\\Models\\Series'">
                                    <h1 class="text-2xl font-medium mb-4">Series</h1>
                                    <p><strong>{{ $t('Title') }}: </strong>{{ b.title }}</p>
                                    <p><strong>{{ $t('Actors') }}: </strong>{{ b.actors }}</p>
                                    <p><strong>{{ $t('Number seasons') }}: </strong>{{ b.num_seasons }}</p>
                                    <p><strong>{{ $t('Number episodes') }}: </strong>{{ b.num_episodes }}</p>
                                    <p><strong>{{ $t('Currently at') }}: </strong>{{ b.currently_at }}</p>
                                    <p class="mt-2"><strong>{{ $t('Notes') }}: </strong>{{ b.notes }}</p>
                                    <p class="mt-2"><strong>{{ $t('Synopsis') }}: </strong>{{ b.synopsis }}</p>
                                    <p class="mt-2"><strong>{{ $t('Tags') }}: </strong>{{ b.tags }}</p>
                                </div>
                            </Card>
                            <div v-if="!bookmarks.length" id="empty" class="text-3xl m-auto">
                                <h1>{{ $t('No Bookmarks found') }}</h1>
                            </div>
                        </div>
                        <div class='w-2/6 flex flex-col border border-gray-400 rounded-md shadow-lg min-h-72'>
                            <div class="pt-4 px-4">
                                <InputLabel :value="$t('SORT BY')"></InputLabel>
                                <select id="sort" name="sort" v-model="sortBy"
                                    class="w-full p-2 border border-gray-300 rounded-md">
                                    <option value="bookmarkable_type">{{ $t('Type') }}</option>
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

                            <div class="pt-2 px-4">
                                <InputLabel :value="$t('TAGS')"></InputLabel>
                                <TextInput class="w-64" v-model="tags"></TextInput>
                            </div>
                            <div class="pt-4 px-4">
                                <PrimaryButton @click="sortBookmarks">
                                    {{ $t('Search Bookmarks') }}
                                </PrimaryButton>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
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

const getBookmarks = () => {
    let params = {
        'page[size]': 2,
        'page[number]': currentPage.value
    };

    if (sort.value) {
        params.sort = sort.value;
    }

    if (tags.value) {
        params.tags = tags.value;
    }

    axios.get(`api/v1/bookmarks`, { params })
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
            }
        })
        .catch(error => console.log('Ha ocurrido un error: ' + error));
};

nextPage(currentPage, lastPage);
prevPage(currentPage);


onMounted(() => {
    getBookmarks();
});

</script>
