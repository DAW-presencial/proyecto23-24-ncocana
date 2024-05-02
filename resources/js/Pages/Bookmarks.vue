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
                            <Card v-for="b in books" :key="b" class="ml-0">
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
                        <Pagination></Pagination>
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

// ARRAY JSON DE LOS DATOS DE LOS LIBROS
const books = [
    {
        "id": 1,
        "title": "The Great Gatsby",
        "author": "F. Scott Fitzgerald",
        "language": "English",
        "read_pages": 150,
        "total_pages": 180,
        "synopsis": "The story of the fabulously wealthy Jay Gatsby and his love for the beautiful Daisy Buchanan.",
        "notes": "Amazing book, must-read!"
    },
    {
        "id": 2,
        "title": "To Kill a Mockingbird",
        "author": "Harper Lee",
        "language": "English",
        "read_pages": 200,
        "total_pages": 281,
        "synopsis": "The unforgettable novel of a childhood in a sleepy Southern town and the crisis of conscience that rocked it.",
        "notes": "One of the greatest novels of all time."
    },
    {
        "id": 3,
        "title": "1984",
        "author": "George Orwell",
        "language": "English",
        "read_pages": 250,
        "total_pages": 328,
        "synopsis": "A dystopian novel set in a totalitarian state.",
        "notes": "A must-read classic that remains frighteningly relevant."
    }
    // ,{
    //     "id": 4,
    //     "title": "Pride and Prejudice",
    //     "author": "Jane Austen",
    //     "language": "English",
    //     "read_pages": 180,
    //     "total_pages": 279,
    //     "synopsis": "A romantic novel of manners.",
    //     "notes": "A timeless classic."
    // },
    // {
    //     "id": 5,
    //     "title": "The Catcher in the Rye",
    //     "author": "J.D. Salinger",
    //     "language": "English",
    //     "read_pages": 170,
    //     "total_pages": 277,
    //     "synopsis": "A novel by J. D. Salinger about a teenager named Holden Caulfield.",
    //     "notes": "A classic coming-of-age novel."
    // },
    // {
    //     "id": 6,
    //     "title": "Moby-Dick",
    //     "author": "Herman Melville",
    //     "language": "English",
    //     "read_pages": 220,
    //     "total_pages": 625,
    //     "synopsis": "The adventures of Ishmael and his voyage aboard the Pequod, led by the monomaniacal Captain Ahab.",
    //     "notes": "A challenging but rewarding read."
    // },
    // {
    //     "id": 7,
    //     "title": "Don Quixote",
    //     "author": "Miguel de Cervantes",
    //     "language": "Spanish",
    //     "read_pages": 300,
    //     "total_pages": 863,
    //     "synopsis": "The story follows the adventures of an hidalgo named Mr. Alonso Quixano who reads so many chivalric romances that he loses his sanity and decides to set out to revive chivalry, undo wrongs, and bring justice to the world, under the name Don Quixote.",
    //     "notes": "A masterpiece of world literature."
    // },
    // {
    //     "id": 8,
    //     "title": "The Picture of Dorian Gray",
    //     "author": "Oscar Wilde",
    //     "language": "English",
    //     "read_pages": 180,
    //     "total_pages": 254,
    //     "synopsis": "A Gothic and philosophical novel.",
    //     "notes": "Wilde's only novel, a compelling exploration of the nature of beauty, corruption, and morality."
    // },
    // {
    //     "id": 9,
    //     "title": "Anna Karenina",
    //     "author": "Leo Tolstoy",
    //     "language": "Russian",
    //     "read_pages": 270,
    //     "total_pages": 964,
    //     "synopsis": "The tragic story of the aristocrat Anna Karenina's affair with the affluent Count Vronsky.",
    //     "notes": "One of the greatest novels ever written."
    // },
    // {
    //     "id": 10,
    //     "title": "The Hobbit",
    //     "author": "J.R.R. Tolkien",
    //     "language": "English",
    //     "read_pages": 230,
    //     "total_pages": 310,
    //     "synopsis": "The adventures of Bilbo Baggins, a hobbit who embarks on a quest to reclaim the treasure guarded by Smaug the dragon.",
    //     "notes": "A delightful fantasy adventure."
    // }
]

// Poner el nombre de la página
onMounted(() => {
    document.title = "BookMarks | MyBookMarks";
});
</script>
