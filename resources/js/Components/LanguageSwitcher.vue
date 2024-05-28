<template>
    <div>
        <div class="relative rounded- w-8 h-8" @click="toggleSubMenu">
            <button class="bg-blue-500 text-white rounded-full focus:outline-none focus:ring-2 focus:ring-black">
                <img :src="language.img" class="w-full h-full object-cover rounded-full" alt="Flag">
            </button>
            <div v-if="showSubMenu"
                class="absolute top-full left-0 bg-white border border-gray-200 py-2 px-4 rounded shadow-lg">
                <ul>
                    <li v-for="(locale, index) in locales" :key="index">
                        <button @click="loadLanguageImg(), changeLanguage(locale)"
                            class="block py-2 text-gray-800 hover:bg-gray-200">{{
            locale.name }}</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { loadLanguageAsync, getActiveLanguage } from 'laravel-vue-i18n';
import axios from 'axios';

const language = ref({});

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

const currentLocale = ref(localStorage.getItem('preferredLanguage') || getActiveLanguage());
const showSubMenu = ref(false);

const changeLanguage = async (newLocale) => {
    await loadLanguageAsync(newLocale.name);
    currentLocale.value = newLocale.name;
    localStorage.setItem('preferredLanguage', newLocale.name); // Guardar la preferencia del idioma
    await sendLanguagePreference(newLocale);
    language.value = newLocale;
};

const sendLanguagePreference = async (newLocale) => {
    try {
        await axios.post('/language-switch', { language: newLocale });
    } catch (error) {
        console.error('Error al cambiar el idioma:', error);
    }
};

const loadLanguageImg = () => {
    language.value = locales.find(loc => loc.name === currentLocale.value);
};

const toggleSubMenu = () => {
    showSubMenu.value = !showSubMenu.value;
};

// Cuando se monta el componente, cargar el idioma preferido
onMounted(() => {
    loadLanguageImg();
    loadLanguageAsync(currentLocale.value);
});

</script>

<style scoped>
button {
    border: none;
    background-color: transparent;
    cursor: pointer;
}

button:hover {
    background-color: #ddd;
}
</style>
