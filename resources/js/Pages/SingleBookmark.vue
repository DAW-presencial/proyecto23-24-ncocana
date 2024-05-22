<template>

    <Head :title="bookmark_data.title" />
    <AuthenticatedLayout>
        <div class="flex flex-col justify-center m-auto max-w-7xl h-screen items-center">
            <div class="container mx-4 h-3/4">
                <Card class="h-auto" nameButton="UPDATE" :update="updateBookmark" :id="bookmark_data.id">
                    <div class="mt-3">
                        <InputLabel value="Title" />
                        <TextInput v-model="bookmark_data.title" />
                    </div>

                    <!-- PARAMETROS MOVIE -->
                    <div v-if="bookmark_data.tipo === 'App\\Models\\Movie'">
                        <div class="mt-3">
                            <InputLabel value="Director" />
                            <TextInput v-model="bookmark_data.director" />
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Actors" />
                            <TextInput v-model="bookmark_data.actors" />
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Release date" />
                            <TextInput v-model="bookmark_data.release_date" />
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Currently at" />
                            <TextInput v-model="bookmark_data.currently_at" />
                        </div>
                    </div>

                    <!-- PARAMETROS FANFIC -->
                    <div v-if="bookmark_data.tipo === 'App\\Models\\Fanfic'">
                        <div class="mt-3">
                            <InputLabel value="Author" />
                            <TextInput v-model="bookmark_data.author" />
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Fandom" />
                            <TextInput v-model="bookmark_data.fandom" />
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Original fiction" />
                            <TextInput v-model="bookmark_data.relationships" />
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Language" />
                            <TextInput v-model="bookmark_data.language" />
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Words" />
                            <TextInput v-model="bookmark_data.words" />
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Read chapters" />
                            <TextInput v-model="bookmark_data.read_chapters" />
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Total chapters" />
                            <TextInput v-model="bookmark_data.total_chapters" />
                        </div>
                    </div>

                    <!-- PARAMETROS BOOK -->
                    <div v-if="bookmark_data.tipo === 'App\\Models\\Book'">
                        <div class="mt-3">
                            <InputLabel value="Author" />
                            <TextInput v-model="bookmark_data.author" />
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Language" />
                            <TextInput v-model="bookmark_data.language" />
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Read pages" />
                            <TextInput v-model="bookmark_data.read_pages" />
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Total pages" />
                            <TextInput v-model="bookmark_data.total_pages" />
                        </div>
                    </div>

                    <!-- PARAMETROS SERIES -->
                    <div v-if="bookmark_data.tipo === 'App\\Models\\Series'">
                        <div class="mt-3">
                            <InputLabel value="Actors" />
                            <TextInput v-model="bookmark_data.actors" />
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Number seasons" />
                            <TextInput v-model="bookmark_data.num_seasons" />
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Number episodes" />
                            <TextInput v-model="bookmark_data.num_episodes" />
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Currently at" />
                            <TextInput v-model="bookmark_data.currently_at" />
                        </div>
                    </div>

                    <div class="mt-3">
                        <InputLabel value="Notes" />
                        <TextInput v-model="bookmark_data.notes" />
                    </div>
                    <div class="mt-3">
                        <InputLabel value="Synopsis" />
                        <TextInput v-model="bookmark_data.synopsis" />
                    </div>
                    <div class="mt-3">
                        <InputLabel value="Tags" />
                        <TextInput v-model="bookmark_data.tags" />
                    </div>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { getParamsBookmark } from '@/utils/functions';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/Card.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import axios from 'axios';

const bookmark_data = ref({});
const request = ref(null);

const getBookmarks = () => {
    const { props } = usePage();
    const { bookmark } = props;

    console.log(bookmark);
    const dataAttributes = bookmark.data.attributes;

    let json = dataAttributes.bookmarkable;
    json.tipo = dataAttributes.bookmarkable_type;
    json.title = dataAttributes.title;
    json.synopsis = dataAttributes.synopsis;
    json.notes = dataAttributes.notes;
    json.id = bookmark.data.id;
    json.tags = [];

    for (let x = 0; x < dataAttributes.tags.length; x++) {
        json.tags.push(dataAttributes.tags[x].name);
    }
    json.tags = json.tags.join(',');

    request.value = bookmark;
    bookmark_data.value = json;
};

const updateBookmark = async () => {
    const bookmark = request.value.data;
    let tipo = '';

    if (bookmark.attributes.bookmarkable_type === "App\\Models\\Movie") {
        tipo = "Movie";
    }
    if (bookmark.attributes.bookmarkable_type === "App\\Models\\Fanfic") {
        tipo = "Fanfic";
    }
    if (bookmark.attributes.bookmarkable_type === "App\\Models\\Book") {
        tipo = "Book";
    }
    if (bookmark.attributes.bookmarkable_type === "App\\Models\\Series") {
        tipo = "Series";
    }
    const tags = bookmark_data.value.tags;
    const tagsSeparados = tags.split(',');
    console.log(tagsSeparados);

    const data = {
        data: {
            type: bookmark.type,
            id: bookmark.id,
            attributes:
            {
                title: bookmark_data.value.title,
                synopsis: bookmark_data.value.synopsis,
                notes: bookmark_data.value.notes,
                bookmarkable: await getParamsBookmark(bookmark.attributes.bookmarkable_type, bookmark_data.value),
                bookmarkable_type: tipo,
                tags: tagsSeparados
            }
        }
    }
    console.log(data);

    try {
        await axios.patch(`/bookmarks/${bookmark.id}`, data, {
            headers: {
                'Content-Type': 'application/vnd.api+json',
            },
        }).then(() => {
            window.location.href = "/bookmarks";
        });
    } catch (error) {
        console.error(error);
    }
};

onMounted(getBookmarks);
</script>
