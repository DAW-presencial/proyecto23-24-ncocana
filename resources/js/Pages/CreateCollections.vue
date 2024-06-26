<template>

    <Head :title="$t('Create Collection')" />

    <AuthenticatedLayout>
        <main class="flex-1 p-4">
            <div class="mx-auto max-w-7xl mt-6 gap-4">
                <div class="pb-4">
                    <Breadcrumbs :items="['Home', $t('Bookmark'), $t('Create Collection')]"></Breadcrumbs>
                </div>
                <div class="text-xl font-bold mx-auto my-4">
                    <h1>{{ $t('Create Collection') }}</h1>
                </div>
                <!-- TYPES -->
                <form @submit.prevent="enviar">
                    <div v-for="(label, field) in fields" :key="field">
                        <label :for="field" class="block text-sm font-medium leading-6 text-gray-900">{{ $t(label)
                            }}</label>
                        <div class="my-2">
                            <input type="text" :name="field" :id="field" :autocomplete="field"
                                v-model="dataInput[field]" :placeholder="$t(placeholders[field])"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                :class="{ 'border-red-600': errors[field] }" :required="field !== 'tags'" />
                            <p v-if="errors[field]" class="mt-2 text-sm text-red-600">{{ errors[field][0] }}</p>
                        </div>
                    </div>
                    <PrimaryButton>{{ $t('Send') }}</PrimaryButton>
                </form>
            </div>
        </main>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import Breadcrumbs from '@/Components/Breadcrumbs.vue';
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const dataInput = ref({
    name: "",
    description: "",
    tags: "" // Asegúrate de que `tags` esté inicializado aquí
});
const errors = ref({});

const fields = {
    name: "Title",
    description: "Description",
    tags: "Tags"
};

const placeholders = {
    name: "Enter the collection title",
    description: "Enter the collection description",
    tags: "Enter the collection tags"
};

const enviar = async () => {
    errors.value = {}; // Clear errors before submitting
    try {
        // console.log(dataInput.value);
        const tags = dataInput.value.tags;
        const tagsSeparados = tags ? tags.split(',') : [];
        // console.log(dataInput.value);
        const dataToSend = {
            data: {
                type: "collections",
                attributes: {
                    name: dataInput.value.name,
                    description: dataInput.value.description,
                    tags: tagsSeparados // Usa los tags separados por comas
                }
            }
        };

        // console.log('Response:', dataToSend);
        const response = await axios.post('api/v1/collections', dataToSend);

        if (response.status === 201 || response.status === 200) {
            window.location.href = `/collections/${response.data.data.id}`;
            // console.log(response.data.data.id);
        } else {
            console.error('Error: Unexpected response status', response.status);
        }
    } catch (error) {
        if (error.response && error.response.status === 422) {
            const responseErrors = error.response.data.errors;
            responseErrors.forEach((errorItem) => {
                const pointer = errorItem.source.pointer.replace('/data/attributes/bookmarkable/', '').replace('/', '.');
                const errorDetail = errorItem.detail.replace('data.attributes.bookmarkable.', '');
                if (errors.value[pointer]) {
                    errors.value[pointer].push(errorDetail);
                } else {
                    errors.value[pointer] = [errorDetail];
                }
            });
        } else {
            console.error('Error submitting form:', error);
        }
    }
};
</script>
