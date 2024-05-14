<template>

    <Head title="Search Advanced" />

    <AuthenticatedLayout>
        <main class="flex-1 p-4">
            <div class="mx-auto max-w-7xl mt-6 gap-4">
                <div class="text-xl font-bold mx-auto my-4">
                    <h1>Create Bookmark</h1>
                </div>
                <!-- TYPES -->
                <label for="type" class="block text-sm font-medium leading-6 text-gray-900"> type </label>
                <div class="mt-2">
                    <select id="type"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        v-model="selected_type">
                        <option v-for="t in types" :key="t" :value="t"> {{ t }}</option>
                    </select>
                </div>

                <!-- DYNAMIC FIELDS -->
                <div v-if="selected_type">
                    <div v-for="f in fields[selected_type]" :key="f">
                        <label :for="f" class="block text-sm font-medium leading-6 text-gray-900">{{ f }}</label>
                        <div class="mt-2">
                            <input type="text" :name="f" :id="f" :autocomplete="f"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, watch, onMounted } from "vue";

const types = ["Book", "Movie", "Series", "Fanfic"];
const selected_type = ref(null);

const fields = {
    Book: ["id", "title", "author", "language", "read_pages", "total_pages", "synopsis", "notes"],
    Movie: ["id", "title", "director", "actors", "release_date", "currently_at", "notes"],
    Series: ["id", "title", "actors", "num_seasons", "num_episodes", "currently_at", "synopsis", "notes"]
};

const getBookmarks = () => {
    // Petición para obtener un array con todos los libros
    axios.post(`/bookmarks`)
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

// Poner el nombre de la página
onMounted(() => {
    getBookmarks();
});

</script>
