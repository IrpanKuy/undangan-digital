import "../css/app.css";
import "./bootstrap";

import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        // Plugin bawaan
        app.use(plugin);
        app.use(ZiggyVue, Ziggy);

        // Daftarkan Iconify secara global (Opsional, tapi sangat direkomendasikan jika sering dipakai)
        app.component("Icon", Icon);

        app.mount(el);
    },

    defaults: {
        form: {
            recentlySuccessfulDuration: 5000,
        },
        prefetch: {
            cacheFor: "1m",
            hoverDelay: 150,
        },
        visitOptions: (_href, options) => {
            return {
                headers: {
                    ...options.headers,
                    "X-Custom-Header": "value",
                },
            };
        },
    },
});
