<script setup>
import { Icon } from "@iconify/vue";

// --- FilePond Imports ---
import vueFilePond from "vue-filepond";
import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";

// Inisialisasi komponen FilePond
const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
);

const props = defineProps({
    modelValue: Object,
});

const emit = defineEmits(["update:modelValue"]);

const addKisah = () => {
    props.modelValue.kisah_cintas.push({
        id: null,
        tanggal: "",
        peristiwa: "",
        foto: null,
        foto_path: null,
    });
};

const removeKisah = (index) => {
    // Note: jika butuh track deleted kisah cinta di DB, tambahkan array remove_kisah_cintas di form
    props.modelValue.kisah_cintas.splice(index, 1);
};

// Menerima fileItems dari event FilePond dan index dari loop
const handleFileChange = (fileItems, index) => {
    if (fileItems && fileItems.length > 0) {
        props.modelValue.kisah_cintas[index].foto = fileItems[0].file;
    } else {
        props.modelValue.kisah_cintas[index].foto = null;
    }
};
</script>

<template>
    <div
        class="bg-white border border-gray-300 rounded-sm h-full flex flex-col"
    >
        <!-- Header Section -->
        <div
            class="bg-gray-100 border-b border-gray-300 px-5 py-3 flex items-center justify-between shrink-0"
        >
            <div class="flex items-center gap-2">
                <Icon
                    icon="mdi:cards-heart-outline"
                    width="20"
                    class="text-gray-700"
                />
                <h3
                    class="font-bold text-gray-800 uppercase tracking-tight text-sm"
                >
                    Kisah Cinta
                </h3>
            </div>
            <button
                type="button"
                @click="addKisah"
                class="cursor-pointer text-xs font-bold text-[#004D31] bg-[#004D31]/10 hover:bg-[#004D31]/20 px-3 py-1.5 rounded-sm transition-colors border border-[#004D31]/30 flex items-center gap-1"
            >
                <Icon icon="mdi:plus" /> Tambah Cerita
            </button>
        </div>

        <div class="p-6 flex-1">
            <div
                v-if="modelValue.kisah_cintas.length === 0"
                class="text-center py-6 border border-dashed border-gray-300 rounded-sm bg-gray-50 text-gray-500 text-sm"
            >
                Belum ada cerita perjalanan cinta.
            </div>

            <div class="space-y-4">
                <div
                    v-for="(item, index) in modelValue.kisah_cintas"
                    :key="item"
                    class="p-4 border border-gray-200 bg-gray-50 rounded-sm relative"
                >
                    <button
                        @click="removeKisah(index)"
                        type="button"
                        class="absolute top-3 right-3 text-red-500 hover:text-white bg-red-100 hover:bg-red-600 p-1 rounded-sm cursor-pointer transition-colors shadow-sm"
                    >
                        <Icon icon="mdi:close" width="16" />
                    </button>

                    <h4
                        class="font-bold text-sm text-[#004D31] uppercase border-b border-gray-300 pb-2 mb-3"
                    >
                        Cerita #{{ index + 1 }}
                    </h4>

                    <div class="space-y-3">
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-700 uppercase mb-1"
                                >Tanggal/Bulan/Tahun</label
                            >
                            <input
                                v-model="item.tanggal"
                                type="text"
                                class="w-full border border-gray-400 rounded-sm px-3 py-1.5 text-sm outline-none focus:border-[#004D31] bg-white"
                                placeholder="Contoh: 14 Februari 2020"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-700 uppercase mb-1"
                                >Isi Cerita / Peristiwa</label
                            >
                            <textarea
                                v-model="item.peristiwa"
                                rows="2"
                                class="w-full border border-gray-400 rounded-sm px-3 py-1.5 text-sm outline-none focus:border-[#004D31] bg-white"
                                placeholder="Awal mula kami bertemu..."
                            ></textarea>
                        </div>

                        <!-- Preview Gambar dari Database (Jika Ada) -->
                        <div
                            v-if="item.foto_path && !item.foto"
                            class="w-full h-32 bg-gray-200 border border-gray-300 rounded-sm flex items-center justify-center overflow-hidden mb-2 mt-4"
                        >
                            <img
                                :src="`/storage/${item.foto_path}`"
                                class="w-full h-full object-cover"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-gray-700 uppercase mb-1 mt-2"
                                >Foto Momen
                                <span
                                    class="text-gray-400 font-normal normal-case"
                                    >(Opsional)</span
                                ></label
                            >
                            <!-- Input FilePond -->
                            <FilePond
                                :name="'kisah_cinta_foto_' + index"
                                label-idle="Tarik & Lepas foto <br/> atau <span class='filepond--label-action'>Telusuri</span>"
                                accepted-file-types="image/jpeg, image/png, image/jpg"
                                @updatefiles="
                                    (files) => handleFileChange(files, index)
                                "
                                class="mb-0 custom-filepond"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Menyesuaikan FilePond dengan Flat UI Tailwind */
:deep(.filepond--panel-root) {
    border-radius: 0.125rem; /* rounded-sm */
    background-color: #ffffff;
    border: 1px solid #9ca3af; /* border-gray-400 */
}
:deep(.filepond--drop-label) {
    color: #4b5563; /* text-gray-600 */
    font-size: 0.875rem; /* text-sm */
}
:deep(.filepond--root) {
    margin-bottom: 0 !important;
}
</style>
