<template>
    <Head :title="$t('Collections Bookmark')" />
    <AuthenticatedLayout>
        <main class="flex-1 p-4">
            <div class="flex flex-col justify-center mx-auto max-w-7xl">
                <div class="pb-10">
                    <Breadcrumbs :items="['Home', 'Collection',name]"></Breadcrumbs>
                </div>
                <div class="flex flex-col mb-4">
                    <h1 class="text-6xl mx-auto font-bold">{{ name }}</h1>
                    <!-- Estilos de Tailwind CSS -->
                </div>
                <div class="mb-6">
                    <InputLabel for="bookmark-select" value="Choose a Bookmark" />
                    <select id="bookmark-select" v-model="selectedBookmarkId"
                        class="block w-full mt-1 rounded-md border shadow-md">
                        <option v-for="b in allBookmarks" :key="b.id" :value="b.id">
                            {{ b.attributes.title }}
                        </option>
                    </select>
                    <div class="mt-4">
                        <PrimaryButton @click="addBookmark">Add Bookmark</PrimaryButton>
                    </div>
                </div>
                <div class="container h-3/4 space-y-4">
                    <Card v-for="(b) in bookmark" :key="b.id" class="min-h-16 h-full" :id_bookmark="b.id"
                        nameButton="SHOW" :modifyLink="'/bookmarks/' + b.id"
                        :id_collection="collection_id">
                        <div>
                            <h1 v-if="b.bookmarkable_type === 'App\\Models\\Fanfic'" class="text-2xl font-medium mb-4">
                                Fanfic</h1>
                            <h1 v-else-if="b.bookmarkable_type === 'App\\Models\\Series'"
                                class="text-2xl font-medium mb-4">
                                Series</h1>
                            <h1 v-else-if="b.bookmarkable_type === 'App\\Models\\Movie'"
                                class="text-2xl font-medium mb-4">
                                Movie</h1>
                            <h1 v-else-if="b.bookmarkable_type === 'App\\Models\\Book'"
                                class="text-2xl font-medium mb-4">
                                Book</h1>
                            <p><strong>{{ $t('Title') }}: </strong>{{ b.title }}</p>
                        </div>
                    </Card>
                </div>
            </div>
        </main>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, usePage, Link } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/Card.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Breadcrumbs from '@/Components/Breadcrumbs.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const currentPage = ref(1);
const lastPage = ref(null);
const bookmark = ref([]);
const errors = ref({});
const isLoading = ref(false);
const allBookmarks = ref();
const name = ref('');

const { props } = usePage();
const { collection_id } = props;

const selectedBookmarkId = ref(null);

const getBookmarks = async () => {
    isLoading.value = true;

    let params = {
        'page[size]': 8,
        'page[number]': currentPage.value
    };

    try {
        const response = await axios.get(`/api/v1/collections/${collection_id}`, { params });
        const res = response.data;
        bookmark.value = res.data.attributes.bookmarks;
        name.value = res.data.attributes.name;
        console.log(res);

    } catch (error) {
        console.error('Ha ocurrido un error:', error);
    } finally {
        isLoading.value = false;
    }
};

const addBookmark = async () => {
    if (!selectedBookmarkId.value) return;

    try {
        await axios.post(
            `/api/v1/collections/${props.collection_id}/bookmarks/${selectedBookmarkId.value}`, {});
        await getBookmarks();
    } catch (error) {
        console.error('Error adding bookmark:', error);
    }
};
const getAllBookmarks = async () => {
    await axios.get(`api/v1/bookmarks`)
        .then(response => {
            const res = response.data;
            const data = res.data;
            console.log(data);

            allBookmarks.value = data;
        })
        .catch(error => console.log('Ha ocurrido un error: ' + error));
};

const removeBookmark = async () => {
    if (!selectedBookmarkId.value) return;

    try {
        await axios.delete(`/api/v1/collections/${collection_id}/bookmarks/${selectedBookmarkId.value}`);
        await getBookmarks();
    } catch (error) {
        console.error('Error removing bookmark:', error);
    }
};

watch(currentPage, getBookmarks);

onMounted(() => {
    getBookmarks(),
        getAllBookmarks()
});
</script>
