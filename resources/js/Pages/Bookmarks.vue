<template>

    <Head title="Bookmarks" />
    <AuthenticatedLayout>
        <main class="flex-1 p-5">
            <div class="flex flex-col max-w-7xl mx-auto">
                <!-- BOTONES Y INPUT -->
                <div class="flex justify-between w-full">
                    <div>
                        <PrimaryButton>Create Bookmark</PrimaryButton>
                    </div>
                    <div class="flex gap-4">
                        <TextInput class="w-64"></TextInput>
                        <PrimaryButton>Search</PrimaryButton>
                    </div>
                </div>
                <div class="mt-4 p-6 rounded-md max-h-screen bg-stone-50">
                    <div class="flex justify-between gap-10">
                        <div class='flex flex-col w-4/6 h-auto rounded-sm space-y-8'>
                            <!-- Cards -->
                            <Card v-for="(b, index) in bookmarks" :key="b.id" class="ml-0">
                                <p><strong>Title: </strong>{{ b.title }}</p>
                                <p><strong>Author: </strong>{{ b.author }}</p>
                                <p><strong>Language: </strong>{{ b.language }}</p>
                                <p><strong>Read pages: </strong>{{ b.read_pages }}</p>
                                <p><strong>Total pages: </strong>{{ b.total_pages }}</p>
                                <div class="py-1">
                                    <p><strong>Synopsis: </strong></p>
                                    <p>{{ b.synopsis }}</p>
                                </div>
                                <div class="pb-1">
                                    <p><strong>Notes: </strong></p>
                                    <p>{{ b.notes }}</p>
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
                        <TailwindPagination :data="bookmarks" @pagination-change-page="getBookmarks">
                        </TailwindPagination>
                        <!-- <Pagination></Pagination> -->
                    </div>
                </div>
            </div>

        </main>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import Pagination from '@/Components/Pagination.vue'
import Card from '@/Components/Card.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextArea from '@/Components/TextArea.vue'
import { onMounted } from 'vue'
import { TailwindPagination } from 'laravel-vue-pagination';


const bookmarks = [];

const getBookmarks = (page = 1) => {
    // Petición para obtener un array con todos los libros
    axios.get(`http://127.0.0.1:8000/api/v1/bookmarks/page=${page}`)
        .then(response => {
            let res = response.data

            const data = res.data
            for (let i = 0; i < data.length; i++) {
                console.log(data[i].attributes.bookmarkable)
                bookmarks.push(data[i].attributes.bookmarkable)
            }
        })
        .catch(error => console.log('Ha ocurrido un error: ' + error))
}

onMounted(() => {
    getBookmarks();
});

</script>
