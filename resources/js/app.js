import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { i18nVue } from 'laravel-vue-i18n';
import vuetify from './config/vuetify';
import axios from './config/axios-config'

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

// Agregar el icono al `head`
const link = document.createElement('link');
link.rel = 'icon';
link.type = 'image/png';  // Cambia esto al tipo de archivo de tu icono (e.g., 'image/png')
link.href = '/img/nuevo-logo1.png';  // Cambia esto a la ruta de tu icono
document.head.appendChild(link);
