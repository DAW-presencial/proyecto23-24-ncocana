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
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/Card.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import axios from 'axios';

const bookmark_data = ref({});
const request = ref(null);
const token = localStorage.getItem('token');

const getBookmarks = () => {
    const { props } = usePage();
    const { bookmark } = props;

    const dataAttributes = bookmark.data.attributes;

    let json = dataAttributes.bookmarkable;
    json.tipo = dataAttributes.bookmarkable_type;
    json.title = dataAttributes.title;
    json.synopsis = dataAttributes.synopsis;
    json.notes = dataAttributes.notes;
    json.id = bookmark.data.id;

    request.value = bookmark;
    bookmark_data.value = json;

    console.log(bookmark_data.value);
};

const updateBookmark = async () => {
    const bookmark = request.value.data;
    console.log(request.value)

    const getParamsBookmark = async () => {
        if (bookmark.attributes.bookmarkable_type === "App\\Models\\Movie") {
            bookmark.attributes.bookmarkable_type = "Movie";

            return {
                actors: bookmark_data.value.actors,
                currently_at: bookmark_data.value.currently_at,
                director: bookmark_data.value.director,
                release_date: bookmark_data.value.release_date,
            };
        }
        if (
            bookmark.attributes.bookmarkable_type === "App\\Models\\Fanfic"
        ) {
            bookmark.attributes.bookmarkable_type = "Fanfic";
            return {
                author: bookmark_data.value.author,
                fandom: bookmark_data.value.fandom,
                language: bookmark_data.value.language,
                words: bookmark_data.value.words, // Asegúrate de que sea un entero
                read_chapters: bookmark_data.value.read_chapters, // Asegúrate de que sea un entero
                total_chapters: bookmark_data.value.total_chapters,
                relationships: bookmark_data.value.relationships,
            };
        }
        if (
            bookmark.attributes.bookmarkable_type === "App\\Models\\Book"
        ) {
            bookmark.attributes.bookmarkable_type = "Book";

            return {
                author: bookmark_data.value.author,
                language: bookmark_data.value.language,
                read_pages: bookmark_data.value.read_pages,
                total_pages: bookmark_data.value.total_pages,
            };
        }
        if (
            bookmark.attributes.bookmarkable_type === "App\\Models\\Series"
        ) {
            bookmark.attributes.bookmarkable_type = "Series";

            return {
                actors: bookmark_data.value.actors,
                currently_at: bookmark_data.value.currently_at,
                num_episodes: bookmark_data.value.num_episodes,
                num_seasons: bookmark_data.value.num_seasons,
            };
        }
    };

    const data = {
        data: {
            type: bookmark.type,
            id: bookmark.id,
            attributes:
                [
                    {
                        title: bookmark_data.value.title,
                        synopsis: bookmark_data.value.synopsis,
                        notes: bookmark_data.value.notes,
                        bookmarkable: await getParamsBookmark(),
                        bookmarkable_type: bookmark.attributes.bookmarkable_type,
                    }
                ]
        },
    };
    console.log(data);

    try {
        await axios.patch(`/bookmarks/${bookmark.id}`, data, {
            headers: {
                'Content-Type': 'application/vnd.api+json',
                Authorization: `${token}`,
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
