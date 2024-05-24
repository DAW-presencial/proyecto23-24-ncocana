<template>
    <div>
        <div class="relative rounded- w-8 h-8" @click="toggleSubMenu">
            <button class="bg-blue-500 text-white rounded-full focus:outline-none focus:ring-2 focus:ring-black">
                <img :src="language.img" class="w-full h-full object-cover rounded-full" alt="Flag">
                <!-- Usando la imagen SVG -->
            </button>
            <div v-if="showSubMenu"
                class="absolute top-full left-0 bg-white border border-gray-200 py-2 px-4 rounded shadow-lg">
                <ul>
                    <li v-for="(locale, index) in locales" :key="index">
                        <button @click="changeLanguage(locale)" class="block py-2 text-gray-800 hover:bg-gray-200">{{
            locale.name }}</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { loadLanguageAsync, getActiveLanguage } from 'laravel-vue-i18n';
import axios from 'axios';
const language = ref({ name: 'es', img: '/img/spain.svg' });

const locales = [
    {
        name: 'en',
        img: '/img/eeuu.svg'
    },
    {
        name: 'es',
        img: '/img/spain.svg'
    }
]
const currentLocale = ref(getActiveLanguage());
const showSubMenu = ref(false);

const changeLanguage = async (newLocale) => {
    await loadLanguageAsync(newLocale.name); // Cargar el nuevo idioma
    currentLocale.value = newLocale.name; // Establecer el nuevo idioma como el actual
    await sendLanguagePreference(newLocale); // Enviar la preferencia al servidor
    language.value = newLocale;
};

const sendLanguagePreference = async (newLocale) => {
    try {
        // Realizar una solicitud POST al servidor para cambiar el idioma
        await axios.post('/language-switch', { language: newLocale });
    } catch (error) {
        console.error('Error al cambiar el idioma:', error);
    }
};

const toggleSubMenu = () => {
    showSubMenu.value = !showSubMenu.value;
};

</script>

<style scoped>
button {
    border: none;
    /* Eliminar el borde */
    background-color: transparent;
    /* Hacer el fondo transparente */
    cursor: pointer;
}

button:hover {
    background-color: #ddd;
}
</style>
