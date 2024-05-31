<template>

    <Head :title="$t('Single Bookmark')" />
    <AuthenticatedLayout>
        <main class="flex-1 p-4">
            <div class="flex flex-col justify-center mx-auto max-w-7xl">
                <div class="pb-12">
                    <Breadcrumbs :items="['Home', 'Bookmark', 'Single Bookmark']"></Breadcrumbs>
                </div>
                <div class="container mx-4 h-3/4">
                    <Card class="h-auto" nameButton="UPDATE" :update="updateBookmark" :id="bookmark_data.id"
                        candelete="true">

                        <div v-if="errors['general']" class="mt-2 text-sm text-red-600">{{ errors['general'][0] }}</div>

                        <div class="mt-3">
                            <InputLabel :value="$t('Title')" />
                            <TextInput v-model="bookmark_data.title" :placeholder="placeholders['title']" />
                            <p v-if="errors['title']" class="mt-2 text-sm text-red-600">{{ errors['title'][0] }}</p>
                        </div>

                        <!-- PARAMETROS MOVIE -->
                        <div v-if="bookmark_data.tipo === 'App\\Models\\Movie'">
                            <div class="mt-3">
                                <InputLabel :value="$t('Director')" />
                                <TextInput v-model="bookmark_data.director"
                                    :placeholder="placeholders['Movie']['director']" />
                                <p v-if="errors['director']" class="mt-2 text-sm text-red-600">{{ errors['director'][0]
                                    }}</p>
                            </div>
                            <div class="mt-3">
                                <InputLabel :value="$t('Actors')" />
                                <TextInput v-model="bookmark_data.actors"
                                    :placeholder="placeholders['Movie']['actors']" />
                                <p v-if="errors['actors']" class="mt-2 text-sm text-red-600">{{ errors['actors'][0] }}
                                </p>
                            </div>
                            <div class="mt-3">
                                <InputLabel :value="$t('Release date')" />
                                <TextInput v-model="bookmark_data.release_date"
                                    :placeholder="placeholders['Movie']['release_date']" />
                                <p v-if="errors['release_date']" class="mt-2 text-sm text-red-600">{{
        errors['release_date'][0] }}</p>
                            </div>
                            <div class="mt-3">
                                <InputLabel :value="$t('Currently at')" />
                                <TextInput v-model="bookmark_data.currently_at"
                                    :placeholder="placeholders['Movie']['currently_at']" />
                                <p v-if="errors['currently_at']" class="mt-2 text-sm text-red-600">{{
        errors['currently_at'][0] }}</p>
                            </div>
                        </div>

                        <!-- PARAMETROS FANFIC -->
                        <div v-if="bookmark_data.tipo === 'App\\Models\\Fanfic'">
                            <div class="mt-3">
                                <InputLabel :value="$t('Author')" />
                                <TextInput v-model="bookmark_data.author"
                                    :placeholder="placeholders['Fanfic']['author']" />
                                <p v-if="errors['author']" class="mt-2 text-sm text-red-600">{{ errors['author'][0] }}
                                </p>
                            </div>
                            <div class="mt-3">
                                <InputLabel :value="$t('Fandom')" />
                                <TextInput v-model="bookmark_data.fandom"
                                    :placeholder="placeholders['Fanfic']['fandom']" />
                                <p v-if="errors['fandom']" class="mt-2 text-sm text-red-600">{{ errors['fandom'][0] }}
                                </p>
                            </div>
                            <div class="mt-3">
                                <InputLabel :value="$t('Original fiction')" />
                                <TextInput v-model="bookmark_data.relationships"
                                    :placeholder="placeholders['Fanfic']['relationships']" />
                                <p v-if="errors['relationships']" class="mt-2 text-sm text-red-600">{{
        errors['relationships'][0] }}</p>
                            </div>
                            <div class="mt-3">
                                <InputLabel :value="$t('Language')" />
                                <TextInput v-model="bookmark_data.language"
                                    :placeholder="placeholders['Fanfic']['language']" />
                                <p v-if="errors['language']" class="mt-2 text-sm text-red-600">{{ errors['language'][0]
                                    }}</p>
                            </div>
                            <div class="mt-3">
                                <InputLabel :value="$t('Words')" />
                                <TextInput v-model="bookmark_data.words"
                                    :placeholder="placeholders['Fanfic']['words']" />
                                <p v-if="errors['words']" class="mt-2 text-sm text-red-600">{{ errors['words'][0] }}</p>
                            </div>
                            <div class="mt-3">
                                <InputLabel :value="$t('Read chapters')" />
                                <TextInput v-model="bookmark_data.read_chapters"
                                    :placeholder="placeholders['Fanfic']['read_chapters']" />
                                <p v-if="errors['read_chapters']" class="mt-2 text-sm text-red-600">{{
        errors['read_chapters'][0] }}</p>
                            </div>
                            <div class="mt-3">
                                <InputLabel :value="('Total chapters')" />
                                <TextInput v-model="bookmark_data.total_chapters"
                                    :placeholder="placeholders['Fanfic']['total_chapters']" />
                                <p v-if="errors['total_chapters']" class="mt-2 text-sm text-red-600">{{
        errors['total_chapters'][0] }}</p>
                            </div>
                        </div>

                        <!-- PARAMETROS BOOK -->
                        <div v-if="bookmark_data.tipo === 'App\\Models\\Book'">
                            <div class="mt-3">
                                <InputLabel :value="$t('Author')" />
                                <TextInput v-model="bookmark_data.author"
                                    :placeholder="placeholders['Book']['author']" />
                                <p v-if="errors['author']" class="mt-2 text-sm text-red-600">{{ errors['author'][0] }}
                                </p>
                            </div>
                            <div class="mt-3">
                                <InputLabel :value="$t('Language')" />
                                <TextInput v-model="bookmark_data.language"
                                    :placeholder="placeholders['Book']['language']" />
                                <p v-if="errors['language']" class="mt-2 text-sm text-red-600">{{ errors['language'][0]
                                    }}</p>
                            </div>
                            <div class="mt-3">
                                <InputLabel :value="$t('Read pages')" />
                                <TextInput v-model="bookmark_data.read_pages"
                                    :placeholder="placeholders['Book']['read_pages']" />
                                <p v-if="errors['read_pages']" class="mt-2 text-sm text-red-600">{{
        errors['read_pages'][0] }}</p>
                            </div>
                            <div class="mt-3">
                                <InputLabel :value="$t('Total pages')" />
                                <TextInput v-model="bookmark_data.total_pages"
                                    :placeholder="placeholders['Book']['total_pages']" />
                                <p v-if="errors['total_pages']" class="mt-2 text-sm text-red-600">{{
        errors['total_pages'][0] }}</p>
                            </div>
                        </div>

                        <!-- PARAMETROS SERIES -->
                        <div v-if="bookmark_data.tipo === 'App\\Models\\Series'">
                            <div class="mt-3">
                                <InputLabel :value="$t('Actors')" />
                                <TextInput v-model="bookmark_data.actors"
                                    :placeholder="placeholders['Series']['actors']" />
                                <p v-if="errors['actors']" class="mt-2 text-sm text-red-600">{{ errors['actors'][0] }}
                                </p>
                            </div>
                            <div class="mt-3">
                                <InputLabel :value="$t('Number seasons')" />
                                <TextInput v-model="bookmark_data.num_seasons"
                                    :placeholder="placeholders['Series']['num_seasons']" />
                                <p v-if="errors['num_seasons']" class="mt-2 text-sm text-red-600">{{
        errors['num_seasons'][0] }}</p>
                            </div>
                            <div class="mt-3">
                                <InputLabel :value="$t('Number episodes')" />
                                <TextInput v-model="bookmark_data.num_episodes"
                                    :placeholder="placeholders['Series']['currenum_episodesntly_at']" />
                                <p v-if="errors['num_episodes']" class="mt-2 text-sm text-red-600">{{
        errors['num_episodes'][0] }}</p>
                            </div>
                            <div class="mt-3">
                                <InputLabel :value="$t('Currently at')" />
                                <TextInput v-model="bookmark_data.currently_at"
                                    :placeholder="placeholders['Series']['currently_at']" />
                                <p v-if="errors['currently_at']" class="mt-2 text-sm text-red-600">{{
                                    errors['currently_at'][0] }}</p>
                            </div>
                        </div>

                        <div class="mt-3">
                            <InputLabel :value="$t('Synopsis')" />
                            <textarea class="border rounded-md shadow w-full h-20 text-sm p-2 resize-y"
                                v-model="bookmark_data.synopsis" :placeholder="placeholders['synopsis']"></textarea>
                            <p v-if="errors['synopsis']" class="mt-2 text-sm text-red-600">{{ errors['synopsis'][0] }}
                            </p>
                        </div>
                        <div class="mt-3">
                            <InputLabel :value="$t('Notes')" />
                            <textarea class="border rounded-md shadow w-full h-20 text-sm p-2 resize-y"
                                v-model="bookmark_data.notes" :placeholder="placeholders['notes']"></textarea>
                            <p v-if="errors['notes']" class="mt-2 text-sm text-red-600">{{ errors['notes'][0] }}</p>
                        </div>
                        <div class="mt-3">
                            <InputLabel :value="$t('Tags')" />
                            <TextInput v-model="bookmark_data.tags" :placeholder="placeholders['tags']" />
                            <p v-if="errors['tags']" class="mt-2 text-sm text-red-600">{{ errors['tags'] }}</p>
                        </div>
                    </Card>
                </div>
            </div>
        </main>
    </AuthenticatedLayout>
</template>

<script setup>
import { formatDate, getParamsBookmark } from '@/utils/functions';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/Card.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import axios from 'axios';
import Breadcrumbs from '@/Components/Breadcrumbs.vue';

const bookmark_data = ref({});
const request = ref(null);
const errors = ref({});

const placeholders = {
    title: "Enter the title",
    synopsis: "Enter a brief synopsis",
    notes: "Enter any additional notes",
    tags: 'Enter any tags separated by commas: "tag1, tag2, tag3"',
    Book: {
        author: "Enter the author's name",
        language: "Enter the language",
        read_pages: "Enter the number of pages read",
        total_pages: "Enter the total number of pages",
    },
    Movie: {
        director: "Enter the director's name",
        actors: "Enter the main actors",
        release_date: "Enter the release date: YYYY/MM/DD",
        currently_at: "Enter your current position: HH:MM:SS",
    },
    Series: {
        actors: "Enter the main actors",
        num_seasons: "Enter the number of seasons",
        num_episodes: "Enter the number of episodes",
        currently_at: "Enter your current position: Season 4, Episode 3",
    },
    Fanfic: {
        author: "Enter the author's name",
        fandom: "Enter the fandom",
        language: "Enter the language",
        words: "Enter the word count",
        read_chapters: "Enter the number of chapters read",
        total_chapters: "Enter the total number of chapters",
        relationships: "Enter the relationships",
    }
};

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
    json.release_date = formatDate(json.release_date);

    for (let x = 0; x < dataAttributes.tags.length; x++) {
        json.tags.push(dataAttributes.tags[x].name);
    }
    json.tags = json.tags.join(',');

    request.value = bookmark;
    bookmark_data.value = json;
};

const updateBookmark = async () => {
    errors.value = {}; // Clear errors before submitting

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

    // Check if any required field is empty
    const bookmarkableFields = await getParamsBookmark(bookmark.attributes.bookmarkable_type, bookmark_data.value);
    const emptyFields = Object.values(bookmarkableFields).filter(value => !value);
    if (emptyFields.length > 0) {
        errors.value.general = ["Please fill in all required fields."];
        return; // Exit function early if any bookmarkable field is empty
    }

    // Check if any required field in bookmark_data is empty
    const requiredFields = ['title', 'synopsis', 'notes'];
    if (requiredFields.some(field => !bookmark_data.value[field])) {
        errors.value.general = ["Please fill in all required fields."];
        return; // Exit function early if any required field is empty
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
        await axios.patch(`api/v1/bookmarks/${bookmark.id}`, data, {
            headers: {
                'Content-Type': 'application/vnd.api+json',
            },
        }).then(() => {
            window.location.href = "/bookmarks";
        });
    } catch (error) {
        if (error.response && error.response.status === 422) {
            const responseErrors = error.response.data.errors;
            responseErrors.forEach((errorItem) => {
                const pointer = errorItem.source.pointer.includes('/data/attributes/bookmarkable/') ?
                    errorItem.source.pointer.replace('/data/attributes/bookmarkable/', '').replace('/', '.') :
                    errorItem.source.pointer.replace('/data/attributes/', '').replace('/', '.');
                const errorDetail = errorItem.detail.includes('data.attributes.bookmarkable.') ?
                    errorItem.detail.replace('data.attributes.bookmarkable.', '') :
                    errorItem.detail.replace('data.attributes.', '');
                if (errors.value[pointer]) {
                    errors.value[pointer].push(errorDetail);
                } else {
                    errors.value[pointer] = [errorDetail];
                }
            });
        } else if (error.response && error.response.status === 400) {
            if(error.response.data.message) {
                errors.value['tags'] = error.response.data.message;
            }
            console.log(errors);
        } else {
            console.error('Error submitting form:', error);
        }
    }
};

onMounted(getBookmarks);
</script>
