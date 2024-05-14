<template>

    <Head :title="bookmark_data.title" />
    <AuthenticatedLayout>
        <div class="flex flex-col justify-center m-auto max-w-7xl h-screen items-center">
            <div class="container mx-4 h-3/4">
                <Card class="h-auto" nameButton="UPDATE" :token="token" :id="bookmark_data.id">
                    <div class="mt-3">
                        <InputLabel value="Title"></InputLabel>
                        <TextInput v-model="bookmark_data.title"></TextInput>
                    </div>
                    <!-- PARAMETROS MOVIE -->
                    <div v-if="bookmark_data.tipo == 'App\\Models\\Movie'">
                        <div class="mt-3">
                            <InputLabel value="Director"></InputLabel>
                            <TextInput v-model="bookmark_data.director"></TextInput>
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Actors"></InputLabel>
                            <TextInput v-model="bookmark_data.actors"></TextInput>
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Release date"></InputLabel>
                            <TextInput v-model="bookmark_data.release_date"></TextInput>
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Currently at"></InputLabel>
                            <TextInput v-model="bookmark_data.currently_at"></TextInput>
                        </div>
                    </div>

                    <!-- PARAMETROS FANFIC -->
                    <div v-if="bookmark_data.tipo == 'App\\Models\\Fanfic'">
                        <div class="mt-3">
                            <InputLabel value="Author"></InputLabel>
                            <TextInput v-model="bookmark_data.author"></TextInput>
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Fandom"></InputLabel>
                            <TextInput v-model="bookmark_data.fandom"></TextInput>
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Original fiction"></InputLabel>
                            <TextInput v-model="bookmark_data.relationships"></TextInput>
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Language"></InputLabel>
                            <TextInput v-model="bookmark_data.language"></TextInput>
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Words"></InputLabel>
                            <TextInput v-model="bookmark_data.words"></TextInput>
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Read chapters"></InputLabel>
                            <TextInput v-model="bookmark_data.read_chapters"></TextInput>
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Total chapters"></InputLabel>
                            <TextInput v-model="bookmark_data.total_chapters"></TextInput>
                        </div>
                    </div>

                    <div v-if="bookmark_data.tipo == 'App\\Models\\Book'">
                        <div class="mt-3">
                            <InputLabel value="Author"></InputLabel>
                            <TextInput v-model="bookmark_data.author"></TextInput>
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Language"></InputLabel>
                            <TextInput v-model="bookmark_data.language"></TextInput>
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Read pages"></InputLabel>
                            <TextInput v-model="bookmark_data.read_pages"></TextInput>
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Total pages"></InputLabel>
                            <TextInput v-model="bookmark_data.total_pages"></TextInput>
                        </div>
                    </div>

                    <div v-if="bookmark_data.tipo == 'App\\Models\\Series'">
                        <div class="mt-3">
                            <InputLabel value="Actors"></InputLabel>
                            <TextInput v-model="bookmark_data.actors"></TextInput>
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Number seasons"></InputLabel>
                            <TextInput v-model="bookmark_data.num_seasons"></TextInput>
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Number episodes"></InputLabel>
                            <TextInput v-model="bookmark_data.num_episodes"></TextInput>
                        </div>
                        <div class="mt-3">
                            <InputLabel value="Currently at"></InputLabel>
                            <TextInput v-model="bookmark_data.currently_at"></TextInput>
                        </div>
                    </div>

                    <div class="mt-3">
                        <InputLabel value="Notes"></InputLabel>
                        <TextInput v-model="bookmark_data.notes"></TextInput>
                    </div>
                    <div class="mt-3">
                        <InputLabel value="Synopsis"></InputLabel>
                        <TextInput v-model="bookmark_data.synopsis"></TextInput>
                    </div>
                </Card>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/Card.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import { usePage } from '@inertiajs/vue3';

const bookmark_data = ref('');

const getBookmarks = () => {
    const { props } = usePage();
    const { bookmark } = props;
    const { token } = props;


    const data = bookmark.data.attributes;

    let json = data.bookmarkable;
    json.tipo = data.bookmarkable_type;
    json.title = data.title;
    json.synopsis = data.synopsis;
    json.notes = data.notes;
    json.id = bookmark.data.id;


    bookmark_data.value = json;
    console.log(bookmark_data.value);

}

onMounted(() => {
    getBookmarks();
});
</script>
