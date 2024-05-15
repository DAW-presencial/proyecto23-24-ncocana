<template>

    <Head title="Bookmarks" />
    <AuthenticatedLayout>
        <main class="flex-1 p-5">
            <div class="flex flex-col max-w-7xl mx-auto">
                <!-- BOTONES Y INPUT -->
                <div class="flex justify-between w-full">
                    <div>
                        <PrimaryButton>
                            <Link href='/createbookmark'>Create Bookmark</Link>
                        </PrimaryButton>
                    </div>
                    <div class="flex gap-4">
                        <TextInput class="w-64" v-model="buscar"></TextInput>
                        <PrimaryButton>Search</PrimaryButton>
                    </div>
                </div>
                <div class="mt-4 p-6 rounded-md max-h-screen bg-stone-50">
                    <div class="flex justify-between gap-10">
                        <div class='flex flex-col w-4/6 h-auto rounded-sm space-y-8'>
                            <!-- Cards -->
                            <Card v-for="( b ) in  bookmarks " :key="b.id" class="ml-0" :modifyLink="getLink(b.id)"
                                :id="b.id" nameButton="SHOW">
                                <div v-if="b.tipo == 'App\\Models\\Movie'" class="p-4">
                                    <h1 class="text-xl mb-4">Movie</h1>
                                    <p><strong>Title: </strong>{{ b.title }}</p>
                                    <p><strong>Director: </strong>{{ b.director }}</p>
                                    <p><strong>Actors: </strong>{{ b.actors }}</p>
                                    <p><strong>Relase date: </strong>{{ formatDate(b.release_date) }}</p>
                                    <p><strong>Currently at: </strong>{{ b.currently_at }}</p>
                                    <p class="mt-2"><strong>Notes: </strong>{{ b.notes }}</p>
                                    <p class="mt-2"><strong>Synopsis: </strong>{{ b.synopsis }}</p>
                                </div>

                                <div v-if="b.tipo == 'App\\Models\\Fanfic'">
                                    <h1 class="text-xl mb-4">Fanfic</h1>
                                    <p><strong>Title: </strong>{{ b.title }}</p>
                                    <p><strong>Author: </strong>{{ b.author }}</p>
                                    <p><strong>Fandom: </strong>{{ b.fandom }}</p>
                                    <p><strong>Original fiction: </strong>{{ b.relationships }}</p>
                                    <p><strong>Language: </strong>{{ b.language }}</p>
                                    <p><strong>Words: </strong>{{ b.words }}</p>
                                    <p><strong>Read chapters: </strong>{{ b.read_chapters }}</p>
                                    <p><strong>Total chapters: </strong>{{ b.total_chapters }}</p>
                                    <p class="mt-2"><strong>Notes: </strong>{{ b.notes }}</p>
                                    <p class="mt-2"><strong>Synopsis: </strong>{{ b.synopsis }}</p>

                                </div>

                                <div v-if="b.tipo == 'App\\Models\\Book'">
                                    <h1 class="text-xl mb-4">Book</h1>
                                    <p><strong>Title: </strong>{{ b.title }}</p>
                                    <p><strong>Author: </strong>{{ b.author }}</p>
                                    <p><strong>Language: </strong>{{ b.language }}</p>
                                    <p><strong>Read pages: </strong>{{ b.read_pages }}</p>
                                    <p><strong>Total pages: </strong>{{ b.total_pages }}</p>
                                    <p class="mt-2"><strong>Notes: </strong>{{ b.notes }}</p>
                                    <p class="mt-2"><strong>Synopsis: </strong>{{ b.synopsis }}</p>
                                </div>

                                <div v-if="b.tipo == 'App\\Models\\Series'">
                                    <h1 class="text-xl mb-4">Series</h1>
                                    <p><strong>Title: </strong>{{ b.title }}</p>
                                    <p><strong>Actors: </strong>{{ b.actors }}</p>
                                    <p><strong>Number seasons: </strong>{{ b.num_seasons }}</p>
                                    <p><strong>Number episodes: </strong>{{ b.num_episodes }}</p>
                                    <p><strong>Currently at: </strong>{{ b.currently_at }}</p>
                                    <p class="mt-2"><strong>Notes: </strong>{{ b.notes }}</p>
                                    <p class="mt-2"><strong>Synopsis: </strong>{{ b.synopsis }}</p>

                                </div>
                            </Card>

                        </div>
                        <div class='w-2/6 flex flex-col border border-gray-400 rounded-md shadow-lg'>
                            <div class=" p-4">
                                <InputLabel value="SORT"></InputLabel>
                                <TextArea value="SORT" class="h-14"></TextArea>
                            </div>
                            <div class="p-4">
                                <InputLabel value="INCLUDE"></InputLabel>
                                <TextArea value="INCLUDE" class="h-60"></TextArea>
                            </div>

                            <div class=" p-4">
                                <InputLabel value="EXCLUDE"></InputLabel>
                                <TextArea value="EXCLUDE" class="h-60"></TextArea>
                            </div>

                            <div class="p-4">
                                <PrimaryButton>Search Bookmarks</PrimaryButton>
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
import { Head, Link, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import Card from '@/Components/Card.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextArea from '@/Components/TextArea.vue'
import { onMounted, ref } from 'vue'
import moment from 'moment'; // Importa moment aquí

const { props } = usePage();
const { user } = props;

// Variables
const currentPage = ref(1);
const lastPage = ref(null);
const buscar = ref('');
const bookmarks = ref([]);

const getBookmarks = () => {
    const token = localStorage.getItem('token');
    axios.get(`/bookmarks?page[size]=2&page[number]=${currentPage.value}`, {
        headers: {
            Authorization: token
        }
    })
        .then(response => {


            const res = response.data
            const data = res.data;

            // Pagination
            currentPage.value = res.meta.current_page;
            lastPage.value = res.meta.last_page;

            // console.log("Current Page: ", currentPage.value)
            // console.log("Last Page: ", lastPage.value)

            bookmarks.value = [];
            for (let i = 0; i < data.length; i++) {
                // console.log(data[i].attributes.bookmarkable_type);

                // creamos una variable donde vamos a tener todos los campos
                let json = data[i].attributes.bookmarkable;
                json.tipo = data[i].attributes.bookmarkable_type;
                json.id = data[i].id;
                json.title = data[i].attributes.title;
                json.synopsis = data[i].attributes.synopsis;
                json.notes = data[i].attributes.notes;

                bookmarks.value.push(json);
            }


        })
        .catch(error => console.log('Ha ocurrido un error: ' + error))
}

const nextPage = () => {
    if (currentPage.value < lastPage.value) {
        currentPage.value++;
        getBookmarks();
    }
}

const prevPage = () => {
    if (currentPage.value > lastPage.value) {
        currentPage.value--;
        getBookmarks();
    }
}

const getLink = (id) => {
    const url = `http://127.0.0.1:8000/bookmarks/${id}`;
    return url;
}

const formatDate = (date) => {
    return moment(date).format('YYYY/MM/DD');
}

onMounted(() => {
    getBookmarks();
    formatDate();
});

</script>
