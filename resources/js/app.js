import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import vSelect from 'vue-select';  
import 'vue-select/dist/vue-select.css';  
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - UPEMOR`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const link = document.createElement('link');
        link.rel = 'icon';
        link.type = 'image/jpg';
        link.href = '/img/UPE_vertical.jpg'; // Cambia la ruta si es necesario
        document.head.appendChild(link);
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .component('v-select', vSelect)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
