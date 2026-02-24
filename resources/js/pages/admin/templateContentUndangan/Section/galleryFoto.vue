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

const addGallery = () => {
    props.modelValue.galleries.push({
        id: null,
        file: null,
        image_path: null,
    });
};

const removeGallery = (index) => {
    const item = props.modelValue.galleries[index];
    if (item.id) {
        props.modelValue.remove_galleries.push(item.id);
    }
    props.modelValue.galleries.splice(index, 1);
};

// Menerima fileItems dari event FilePond dan index dari loop
const handleFileChange = (fileItems, index) => {
    if (fileItems && fileItems.length > 0) {
        props.modelValue.galleries[index].file = fileItems[0].file;
    } else {
        props.modelValue.galleries[index].file = null;
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
                    icon="mdi:image-multiple-outline"
                    width="20"
                    class="text-gray-700"
                />
                <h3
                    class="font-bold text-gray-800 uppercase tracking-tight text-sm"
                >
                    Galeri Foto
                </h3>
            </div>
            <button
                type="button"
                @click="addGallery"
                class="cursor-pointer text-xs font-bold text-[#004D31] bg-[#004D31]/10 hover:bg-[#004D31]/20 px-3 py-1.5 rounded-sm transition-colors border border-[#004D31]/30 flex items-center gap-1"
            >
                <Icon icon="mdi:plus" /> Tambah Foto
            </button>
        </div>

        <div class="p-6 flex-1">
            <div
                v-if="modelValue.galleries.length === 0"
                class="text-center py-6 border border-dashed border-gray-300 rounded-sm bg-gray-50 text-gray-500 text-sm"
            >
                Belum ada foto galeri.
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div
                    v-for="(item, index) in modelValue.galleries"
                    :key="item"
                    class="p-3 border border-gray-200 bg-gray-50 rounded-sm relative flex flex-col gap-3 pt-8"
                >
                    <!-- Tombol Hapus (Silang) -->
                    <button
                        @click="removeGallery(index)"
                        type="button"
                        class="absolute top-2 right-2 text-red-500 hover:text-white bg-red-100 hover:bg-red-600 p-1 rounded-sm cursor-pointer transition-colors z-10 shadow-sm"
                    >
                        <Icon icon="mdi:close" width="14" />
                    </button>

                    <!-- Preview Gambar dari Database (Jika Ada) -->
                    <div
                        v-if="item.image_path && !item.file"
                        class="w-full h-32 bg-gray-200 border border-gray-300 rounded-sm flex items-center justify-center overflow-hidden"
                    >
                        <img
                            :src="`/storage/${item.image_path}`"
                            class="w-full h-full object-cover"
                        />
                    </div>

                    <!-- Input FilePond -->
                    <div class="flex-1">
                        <FilePond
                            :name="'gallery_foto_' + index"
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
