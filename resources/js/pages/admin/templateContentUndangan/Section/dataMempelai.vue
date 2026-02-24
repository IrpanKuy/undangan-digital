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

// Handlers untuk FilePond
const handleFotoPriaUpdate = (fileItems) => {
    if (fileItems && fileItems.length > 0) {
        props.modelValue.foto_pria = fileItems[0].file;
    } else {
        props.modelValue.foto_pria = null;
    }
};

const handleFotoWanitaUpdate = (fileItems) => {
    if (fileItems && fileItems.length > 0) {
        props.modelValue.foto_wanita = fileItems[0].file;
    } else {
        props.modelValue.foto_wanita = null;
    }
};
</script>

<template>
    <div class="bg-white border border-gray-300 rounded-sm">
        <div
            class="bg-gray-100 border-b border-gray-300 px-5 py-3 flex items-center gap-2"
        >
            <Icon
                icon="mdi:account-heart-outline"
                width="20"
                class="text-gray-700"
            />
            <h3
                class="font-bold text-gray-800 uppercase tracking-tight text-sm"
            >
                Data Mempelai
            </h3>
        </div>
        <div class="p-6 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Mempelai Pria -->
                <div
                    class="space-y-4 p-5 border border-gray-200 bg-gray-50 rounded-sm relative overflow-hidden"
                >
                    <div
                        class="absolute top-0 left-0 w-1 h-full bg-blue-600"
                    ></div>
                    <h4
                        class="font-bold text-sm text-blue-800 uppercase border-b border-gray-300 pb-2 mb-3 flex items-center gap-2"
                    >
                        <Icon icon="mdi:gender-male" /> Mempelai Pria
                    </h4>
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-700 uppercase mb-1"
                            >Nama Panggilan</label
                        >
                        <input
                            v-model="modelValue.nama_panggilan_pria"
                            type="text"
                            class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-700 uppercase mb-1"
                            >Nama Lengkap</label
                        >
                        <input
                            v-model="modelValue.nama_lengkap_pria"
                            type="text"
                            class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-700 uppercase mb-1"
                            >Keterangan Keluarga</label
                        >
                        <textarea
                            v-model="modelValue.keterangan_keluarga_pria"
                            rows="2"
                            class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                            placeholder="Putra pertama dari Bapak..."
                        ></textarea>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-700 uppercase mb-1"
                        >
                            Foto Pria
                            <span class="text-gray-400 font-normal normal-case"
                                >(Opsional)</span
                            >
                        </label>
                        <FilePond
                            name="foto_pria"
                            label-idle="Tarik & Lepas gambar atau <span class='filepond--label-action'>Telusuri</span>"
                            accepted-file-types="image/jpeg, image/png, image/jpg"
                            @updatefiles="handleFotoPriaUpdate"
                            class="mb-0 custom-filepond"
                        />
                    </div>
                </div>

                <!-- Mempelai Wanita -->
                <div
                    class="space-y-4 p-5 border border-gray-200 bg-gray-50 rounded-sm relative overflow-hidden"
                >
                    <div
                        class="absolute top-0 left-0 w-1 h-full bg-pink-500"
                    ></div>
                    <h4
                        class="font-bold text-sm text-pink-700 uppercase border-b border-gray-300 pb-2 mb-3 flex items-center gap-2"
                    >
                        <Icon icon="mdi:gender-female" /> Mempelai Wanita
                    </h4>
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-700 uppercase mb-1"
                            >Nama Panggilan</label
                        >
                        <input
                            v-model="modelValue.nama_panggilan_wanita"
                            type="text"
                            class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-700 uppercase mb-1"
                            >Nama Lengkap</label
                        >
                        <input
                            v-model="modelValue.nama_lengkap_wanita"
                            type="text"
                            class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-700 uppercase mb-1"
                            >Keterangan Keluarga</label
                        >
                        <textarea
                            v-model="modelValue.keterangan_keluarga_wanita"
                            rows="2"
                            class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                            placeholder="Putri bungsu dari Bapak..."
                        ></textarea>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-700 uppercase mb-1"
                        >
                            Foto Wanita
                            <span class="text-gray-400 font-normal normal-case"
                                >(Opsional)</span
                            >
                        </label>
                        <FilePond
                            name="foto_wanita"
                            label-idle="Tarik & Lepas gambar atau <span class='filepond--label-action'>Telusuri</span>"
                            accepted-file-types="image/jpeg, image/png, image/jpg"
                            @updatefiles="handleFotoWanitaUpdate"
                            class="mb-0 custom-filepond"
                        />
                    </div>
                </div>
            </div>

            <div>
                <label
                    class="block text-xs font-bold text-gray-700 uppercase mb-1"
                    >Teks Penutup</label
                >
                <textarea
                    v-model="modelValue.text_penutup"
                    rows="3"
                    class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                    placeholder="Merupakan suatu kehormatan dan kebahagiaan bagi kami..."
                ></textarea>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Opsional: Menyesuaikan FilePond agar sudutnya sedikit lebih flat (rounded-sm) */
:deep(.filepond--panel-root) {
    border-radius: 0.125rem; /* setara dengan rounded-sm di tailwind */
    background-color: #ffffff;
    border: 1px solid #9ca3af; /* setara dengan border-gray-400 */
}
:deep(.filepond--drop-label) {
    color: #4b5563; /* setara dengan text-gray-600 */
}
</style>
