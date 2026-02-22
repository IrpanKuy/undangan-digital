import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import { stripVuetifyImportant } from "./plugins/stripVuetifyImportant";
// import vueDevTools from "vite-plugin-vue-devtools";
// import { createHtmlPlugin } from "vite-plugin-html";

export default defineConfig({
    plugins: [
        // Plugin untuk menghapus !important dari Vuetify CSS
        // sehingga Tailwind utilities selalu menang
        stripVuetifyImportant(),

        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        tailwindcss(),
        // vueDevTools(),
        // createHtmlPlugin({}),
    ],
});
