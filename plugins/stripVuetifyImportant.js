/**
 * Vite Plugin: Strip !important dari Vuetify Utility Classes
 *
 * Plugin ini menghapus !important dari CSS Vuetify sehingga
 * Tailwind CSS utility classes selalu menang (karena muncul
 * belakangan di cascade).
 *
 * Hanya menghapus !important dari vuetify styles, BUKAN dari
 * file CSS lainnya.
 */
export function stripVuetifyImportant() {
    return {
        name: "strip-vuetify-important",
        enforce: "pre",

        transform(code, id) {
            // Hanya proses file CSS dari vuetify
            if (
                id.includes("vuetify") &&
                (id.endsWith(".css") ||
                    id.endsWith(".sass") ||
                    id.endsWith(".scss"))
            ) {
                // Hapus !important dari semua deklarasi CSS vuetify
                const transformed = code.replace(/\s*!important/g, "");
                return {
                    code: transformed,
                    map: null,
                };
            }
            return null;
        },
    };
}
