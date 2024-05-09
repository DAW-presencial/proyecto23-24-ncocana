import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { i18nVue } from 'laravel-vue-i18n';

// Configuración Vuetify
import vuetify from './plugins/vuetify';

// AXIOS
import axios from 'axios';

// URL base de la API
const baseURL = "http://127.0.0.1:8000/api/v1/bookmarks";

// Configuración axios
axios.defaults.baseURL = baseURL;
axios.defaults.headers.common['Accept'] = 'application/vnd.api+json';

createInertiaApp({
    title: (title) => `${title} - MyBookMarks`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(vuetify)
            .use(plugin)
            .use(ZiggyVue)
            .use(i18nVue, {
                resolve: async lang => {
                    const langs = import.meta.glob('../../lang/*.json');
                    return await langs[`../../lang/${lang}.json`]();
                }
            })
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
