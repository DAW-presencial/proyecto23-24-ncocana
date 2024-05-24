<template>
    <div>
        <button v-for="locale in locales" :key="locale.name" @click="changeLanguage(locale.name)" class="mx-1">
            {{ locale.name.toUpperCase() }}
        </button>
    </div>
</template>

<script>
import { ref } from 'vue';
import { loadLanguageAsync, getActiveLanguage } from 'laravel-vue-i18n';
import axios from 'axios';

export default {
    setup() {
        const locales = [
            {
                name: 'en',
                img: ''
            },
            {
                name: 'es',
                img: ''
            }
        ]; // Lista de idiomas disponibles
        const currentLocale = ref(getActiveLanguage());

        const changeLanguage = async (newLocale) => {
            await loadLanguageAsync(newLocale); // Cargar el nuevo idioma
            currentLocale.value = newLocale; // Establecer el nuevo idioma como el actual
            await sendLanguagePreference(newLocale); // Enviar la preferencia al servidor
        };

        const sendLanguagePreference = async (newLocale) => {
            try {
                // Realizar una solicitud POST al servidor para cambiar el idioma
                await axios.post('/language-switch', { language: newLocale });
            } catch (error) {
                console.error('Error al cambiar el idioma:', error);
            }
        };

        return {
            locales,
            currentLocale,
            changeLanguage,
        };
    },
};
</script>

<style scoped>
button {
    padding: 0.5rem 1rem;
    border: 1px solid #ccc;
    background-color: #f0f0f0;
    cursor: pointer;
}

button:hover {
    background-color: #ddd;
}
</style>
