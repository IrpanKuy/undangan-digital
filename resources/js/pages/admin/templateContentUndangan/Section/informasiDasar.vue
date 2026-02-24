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

// Fungsi untuk menangani perubahan file di FilePond
const handleThumbnailUpdate = (fileItems) => {
    if (fileItems && fileItems.length > 0) {
        // Ambil objek File asli untuk dikirim via Inertia
        props.modelValue.thumbnail = fileItems[0].file;
    } else {
        props.modelValue.thumbnail = null;
    }
};
</script>

<template>
    <div class="bg-white border border-gray-300 rounded-sm">
        <div
            class="bg-gray-100 border-b border-gray-300 px-5 py-3 flex items-center gap-2"
        >
            <Icon
                icon="mdi:information-outline"
                width="20"
                class="text-gray-700"
            />
            <h3
                class="font-bold text-gray-800 uppercase tracking-tight text-sm"
            >
                Informasi Dasar
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <!-- Row 1: Judul & Slug -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label
                        class="block text-xs font-bold text-gray-700 uppercase mb-1"
                    >
                        Judul Undangan <span class="text-red-500">*</span>
                    </label>
                    <input
                        v-model="modelValue.judul"
                        type="text"
                        class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                        placeholder="Contoh: Pernikahan Romeo & Juliet"
                    />
                    <p
                        v-if="modelValue.errors.judul"
                        class="text-[10px] text-red-600 mt-1 uppercase font-bold"
                    >
                        {{ modelValue.errors.judul }}
                    </p>
                </div>
                <div>
                    <label
                        class="block text-xs font-bold text-gray-700 uppercase mb-1"
                    >
                        URL (Slug) <span class="text-red-500">*</span>
                    </label>
                    <input
                        v-model="modelValue.url"
                        type="text"
                        class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                        placeholder="Contoh: romeo-juliet"
                    />
                    <p
                        v-if="modelValue.errors.url"
                        class="text-[10px] text-red-600 mt-1 uppercase font-bold"
                    >
                        {{ modelValue.errors.url }}
                    </p>
                </div>
            </div>

            <!-- Row 2: Thumbnail Upload (FilePond) -->
            <div>
                <label
                    class="block text-xs font-bold text-gray-700 uppercase mb-1"
                >
                    Thumbnail Undangan
                    <span class="text-gray-400 font-normal normal-case"
                        >(Opsional jika tidak ingin diubah)</span
                    >
                </label>
                <FilePond
                    name="thumbnail"
                    label-idle="Tarik & Lepas gambar atau <span class='filepond--label-action'>Telusuri</span>"
                    accepted-file-types="image/jpeg, image/png, image/jpg"
                    @updatefiles="handleThumbnailUpdate"
                    class="mb-0 custom-filepond"
                />
                <p
                    v-if="modelValue.errors.thumbnail"
                    class="text-[10px] text-red-600 mt-1 uppercase font-bold"
                >
                    {{ modelValue.errors.thumbnail }}
                </p>
            </div>

            <!-- Row 3: Salam Pembuka & Youtube -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label
                        class="block text-xs font-bold text-gray-700 uppercase mb-1"
                    >
                        Salam Pembuka
                    </label>
                    <input
                        v-model="modelValue.salam_pembuka"
                        type="text"
                        class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                        placeholder="Contoh: Assalamualaikum Wr. Wb."
                    />
                </div>
                <div>
                    <label
                        class="block text-xs font-bold text-gray-700 uppercase mb-1"
                    >
                        URL Video YouTube (Opsional)
                    </label>
                    <input
                        v-model="modelValue.video_youtube_url"
                        type="url"
                        class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                        placeholder="https://youtube.com/..."
                    />
                </div>
            </div>

            <!-- Row 4: Teks Pembuka -->
            <div>
                <label
                    class="block text-xs font-bold text-gray-700 uppercase mb-1"
                >
                    Teks Pembuka
                </label>
                <textarea
                    v-model="modelValue.text_pembuka"
                    rows="3"
                    class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                    placeholder="Dengan memohon rahmat dan ridho Allah SWT..."
                ></textarea>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Opsional: Menyesuaikan FilePond agar sudutnya sedikit lebih flat (rounded-sm) */
:deep(.filepond--panel-root) {
    border-radius: 0.125rem; /* setara dengan rounded-sm di tailwind */
    background-color: #f9fafb; /* setara dengan bg-gray-50 */
    border: 1px solid #9ca3af; /* setara dengan border-gray-400 */
}
:deep(.filepond--drop-label) {
    color: #4b5563; /* setara dengan text-gray-600 */
}
</style>
