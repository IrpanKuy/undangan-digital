import "../css/app.css";
import "./bootstrap";

import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import { createApp, h } from "vue";
import { createInertiaApp, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

// 1. Import AOS
import AOS from "aos";
import "aos/dist/aos.css";

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

        // 2. Inisialisasi AOS
        app.config.globalProperties.$AOS = AOS;

        app.mount(el);

        AOS.init({
            duration: 1000, // Durasi animasi (1 detik)
            once: true, // Animasi hanya jalan sekali saat di-scroll
            easing: "ease-in-out",
            delay: 100, // Delay default sebelum animasi mulai
        });

        router.on("finish", () => {
            AOS.refresh();
        });
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
