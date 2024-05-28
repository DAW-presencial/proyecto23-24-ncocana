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
                        <button @click="changeLanguage(locale)" class="block py-2 text-gray-800 hover:bg-gray-200">{{
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
import axios from '@/config/axios-config';

const language = ref({ name: 'English', code: 'en', img: '/img/eeuu.svg' });

const locales = [
    {
        name: 'English',
        code: 'en',
        img: '/img/eeuu.svg'
    },
    {
        name: 'Español',
        code: 'es',
        img: '/img/spain.svg'
    }
];
const currentLocale = ref(getActiveLanguage());
const showSubMenu = ref(false);

const changeLanguage = async (newLocale) => {
    await loadLanguageAsync(newLocale.code);
    currentLocale.value = newLocale.code;
    await sendLanguagePreference(newLocale.code);
    language.value = newLocale;
};

const sendLanguagePreference = async (newLocale) => {
    try {
        await axios.post('/language-switch', { language: newLocale });
    } catch (error) {
        console.error('Error al cambiar el idioma:', error);
    }
};

const toggleSubMenu = () => {
    showSubMenu.value = !showSubMenu.value;
};

const getCurrentLanguage = async () => {
    try {
        const response = await axios.get('/current-language');
        const currentLang = response.data.language;

        const locale = locales.find(locale => locale.code === currentLang);
        if (locale) {
            language.value = locale;
            currentLocale.value = locale.code;
        }
    } catch (error) {
        console.error('Error getting the current language:', error);
    }
};

onMounted(async () => {
    await getCurrentLanguage();
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