<script setup>
import { Icon } from "@iconify/vue";
import { computed } from "vue";
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
// Handler untuk Foto Pria
const handleFotoPriaUpdate = (fileItems) => {
    if (fileItems && fileItems.length > 0) {
        if (fileItems[0].origin === 1) {
            // 1 = File baru dari user
            props.modelValue.foto_pria = fileItems[0].file;
        } else if (fileItems[0].origin === 3) {
            props.modelValue.foto_pria = null;
        }
    } else {
        props.modelValue.foto_pria = null;
        props.modelValue.foto_pria_path = null;
    }
};

// Handler untuk Foto Wanita
const handleFotoWanitaUpdate = (fileItems) => {
    if (fileItems && fileItems.length > 0) {
        if (fileItems[0].origin === 1) {
            // 1 = File baru dari user
            props.modelValue.foto_wanita = fileItems[0].file;
        } else if (fileItems[0].origin === 3) {
            props.modelValue.foto_wanita = null;
        }
    } else {
        props.modelValue.foto_wanita = null;
        props.modelValue.foto_wanita_path = null;
    }
};

const serverOptions = {
    load: (source, load, error, progress, abort, headers) => {
        fetch(source)
            .then((res) => res.blob())
            .then(load);
    },
};

const initialFotoPria = computed(() =>
    props.modelValue.foto_pria_path
        ? [
              {
                  source: `/storage/${props.modelValue.foto_pria_path}`,
                  options: { type: "local" },
              },
          ]
        : [],
);

const initialFotoWanita = computed(() =>
    props.modelValue.foto_wanita_path
        ? [
              {
                  source: `/storage/${props.modelValue.foto_wanita_path}`,
                  options: { type: "local" },
              },
          ]
        : [],
);
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
                        class="font-bold text-sm text-blue-800 border-b border-gray-300 pb-2 mb-3 flex items-center gap-2"
                    >
                        <Icon icon="mdi:gender-male" /> Mempelai Pria
                    </h4>
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-700 mb-1"
                            >Nama Panggilan</label
                        >
                        <input
                            v-model="modelValue.nama_panggilan_pria"
                            type="text"
                            placeholder="Contoh: Afri"
                            class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                        />
                        <p
                            v-if="modelValue.errors.nama_panggilan_pria"
                            class="text-[10px] text-red-600 mt-1 font-bold"
                        >
                            {{ modelValue.errors.nama_panggilan_pria }}
                        </p>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-700 mb-1"
                            >Nama Lengkap</label
                        >
                        <input
                            v-model="modelValue.nama_lengkap_pria"
                            type="text"
                            placeholder="Contoh: Afri Putra"
                            class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                        />
                        <p
                            v-if="modelValue.errors.nama_lengkap_pria"
                            class="text-[10px] text-red-600 mt-1 font-bold"
                        >
                            {{ modelValue.errors.nama_lengkap_pria }}
                        </p>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-700 mb-1"
                            >Keterangan Keluarga</label
                        >
                        <textarea
                            v-model="modelValue.keterangan_keluarga_pria"
                            rows="2"
                            class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                            placeholder="Putra pertama dari Bapak..."
                        ></textarea>
                        <p
                            v-if="modelValue.errors.keterangan_keluarga_pria"
                            class="text-[10px] text-red-600 mt-1 font-bold"
                        >
                            {{ modelValue.errors.keterangan_keluarga_pria }}
                        </p>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-700 mb-1"
                            >Doa Pengantin Pria
                        </label>
                        <textarea
                            v-model="modelValue.doa_pengantin_pria"
                            rows="3"
                            class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                            placeholder="Doa untuk mempelai wanita..."
                        ></textarea>
                        <p class="text-[10px] text-gray-500 mt-1">
                            Doa mempelai pria untuk mempelai wanita
                        </p>
                    </div>
                    <!-- Social Media Pria -->
                    <div>
                        <label
                            class="flex items-center gap-1 text-[10px] font-bold text-gray-600 mb-1"
                        >
                            <Icon icon="mdi:instagram" class="text-red-600" />
                            Instagram
                            <span class="text-gray-400 font-normal normal-case"
                                >(opsional)</span
                            >
                        </label>
                        <input
                            v-model="modelValue.instagram_url_pria"
                            type="url"
                            class="w-full border border-gray-300 rounded-sm px-2 py-2 text-xs outline-none focus:border-[#004D31] bg-white"
                            placeholder="https://..."
                        />
                        <p
                            v-if="modelValue.errors.instagram_url_pria"
                            class="text-[10px] text-red-600 mt-1 font-bold"
                        >
                            {{ modelValue.errors.instagram_url_pria }}
                        </p>
                    </div>
                    <div>
                        <label
                            class="flex items-center gap-1 text-[10px] font-bold text-gray-600 mb-1"
                        >
                            <Icon
                                icon="ic:baseline-tiktok"
                                class="text-black"
                            />
                            TikTok
                            <span class="text-gray-400 font-normal normal-case"
                                >(opsional)</span
                            >
                        </label>
                        <input
                            v-model="modelValue.tiktok_url_pria"
                            type="url"
                            class="w-full border border-gray-300 rounded-sm px-2 py-2 text-xs outline-none focus:border-[#004D31] bg-white"
                            placeholder="https://..."
                        />
                        <p
                            v-if="modelValue.errors.tiktok_url_pria"
                            class="text-[10px] text-red-600 mt-1 font-bold"
                        >
                            {{ modelValue.errors.tiktok_url_pria }}
                        </p>
                    </div>
                    <div>
                        <label
                            class="flex items-center gap-1 text-[10px] font-bold text-gray-600 mb-1"
                        >
                            <Icon icon="ri:twitter-x-fill" class="text-black" />
                            X/Twitter
                            <span class="text-gray-400 font-normal normal-case"
                                >(opsional)</span
                            >
                        </label>
                        <input
                            v-model="modelValue.x_url_pria"
                            type="url"
                            class="w-full border border-gray-300 rounded-sm px-2 py-2 text-xs outline-none focus:border-[#004D31] bg-white"
                            placeholder="https://..."
                        />
                        <p
                            v-if="modelValue.errors.x_url_pria"
                            class="text-[10px] text-red-600 mt-1 font-bold"
                        >
                            {{ modelValue.errors.x_url_pria }}
                        </p>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-700 mb-1"
                        >
                            Foto Mempelai Pria
                        </label>
                        <FilePond
                            name="foto_pria"
                            label-idle="Tarik & Lepas gambar atau <span class='filepond--label-action'>Telusuri</span>"
                            accepted-file-types="image/jpeg, image/png, image/jpg"
                            @updatefiles="handleFotoPriaUpdate"
                            :files="initialFotoPria"
                            :server="serverOptions"
                            class="mb-0 custom-filepond"
                        />
                        <p class="text-[10px] text-gray-600 mt-1 font-light">
                            Ukuran file maksimal 5MB
                        </p>
                        <p
                            v-if="modelValue.errors.foto_pria"
                            class="text-[10px] text-red-600 mt-1 font-bold"
                        >
                            {{ modelValue.errors.foto_pria }}
                        </p>
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
                        class="font-bold text-sm text-pink-700 border-b border-gray-300 pb-2 mb-3 flex items-center gap-2"
                    >
                        <Icon icon="mdi:gender-female" /> Mempelai Wanita
                    </h4>
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-700 mb-1"
                            >Nama Panggilan</label
                        >
                        <input
                            v-model="modelValue.nama_panggilan_wanita"
                            type="text"
                            placeholder="Contoh: Anisa"
                            class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                        />
                        <p
                            v-if="modelValue.errors.nama_panggilan_wanita"
                            class="text-[10px] text-red-600 mt-1 font-bold"
                        >
                            {{ modelValue.errors.nama_panggilan_wanita }}
                        </p>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-700 mb-1"
                            >Nama Lengkap</label
                        >
                        <input
                            v-model="modelValue.nama_lengkap_wanita"
                            type="text"
                            placeholder="Contoh: Anisa Putri"
                            class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                        />
                        <p
                            v-if="modelValue.errors.nama_lengkap_wanita"
                            class="text-[10px] text-red-600 mt-1 font-bold"
                        >
                            {{ modelValue.errors.nama_lengkap_wanita }}
                        </p>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-700 mb-1"
                            >Keterangan Keluarga</label
                        >
                        <textarea
                            v-model="modelValue.keterangan_keluarga_wanita"
                            rows="2"
                            class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                            placeholder="Putri bungsu dari Bapak..."
                        ></textarea>
                        <p
                            v-if="modelValue.errors.keterangan_keluarga_wanita"
                            class="text-[10px] text-red-600 mt-1 font-bold"
                        >
                            {{ modelValue.errors.keterangan_keluarga_wanita }}
                        </p>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-gray-700 mb-1"
                            >Doa Pengantin Wanita
                        </label>
                        <textarea
                            v-model="modelValue.doa_pengantin_wanita"
                            rows="3"
                            class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                            placeholder="Doa untuk mempelai pria..."
                        ></textarea>
                        <p class="text-[10px] text-gray-500 mt-1">
                            Doa mempelai wanita untuk mempelai pria
                        </p>
                    </div>
                    <!-- Social Media Wanita -->
                    <div>
                        <label
                            class="flex items-center gap-1 text-[10px] font-bold text-gray-600 mb-1"
                        >
                            <Icon icon="mdi:instagram" class="text-pink-600" />
                            Instagram
                            <span class="text-gray-400 font-normal normal-case"
                                >(opsional)</span
                            >
                        </label>
                        <input
                            v-model="modelValue.instagram_url_wanita"
                            type="url"
                            class="w-full border border-gray-300 rounded-sm px-2 py-2 text-xs outline-none focus:border-[#004D31] bg-white"
                            placeholder="https://..."
                        />
                        <p
                            v-if="modelValue.errors.instagram_url_wanita"
                            class="text-[10px] text-red-600 mt-1 font-bold"
                        >
                            {{ modelValue.errors.instagram_url_wanita }}
                        </p>
                    </div>
                    <div>
                        <label
                            class="flex items-center gap-1 text-[10px] font-bold text-gray-600 mb-1"
                        >
                            <Icon
                                icon="ic:baseline-tiktok"
                                class="text-black"
                            />
                            TikTok
                            <span class="text-gray-400 font-normal normal-case"
                                >(opsional)</span
                            >
                        </label>
                        <input
                            v-model="modelValue.tiktok_url_wanita"
                            type="url"
                            class="w-full border border-gray-300 rounded-sm px-2 py-2 text-xs outline-none focus:border-[#004D31] bg-white"
                            placeholder="https://..."
                        />
                        <p
                            v-if="modelValue.errors.tiktok_url_wanita"
                            class="text-[10px] text-red-600 mt-1 font-bold"
                        >
                            {{ modelValue.errors.tiktok_url_wanita }}
                        </p>
                    </div>
                    <div>
                        <label
                            class="flex items-center gap-1 text-[10px] font-bold text-gray-600 mb-1"
                        >
                            <Icon icon="ri:twitter-x-fill" class="text-black" />
                            X/Twitter
                            <span class="text-gray-400 font-normal normal-case"
                                >(opsional)</span
                            >
                        </label>
                        <input
                            v-model="modelValue.x_url_wanita"
                            type="url"
                            class="w-full border border-gray-300 rounded-sm px-2 py-2 text-xs outline-none focus:border-[#004D31] bg-white"
                            placeholder="https://..."
                        />
                        <p
                            v-if="modelValue.errors.x_url_wanita"
                            class="text-[10px] text-red-600 mt-1 font-bold"
                        >
                            {{ modelValue.errors.x_url_wanita }}
                        </p>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-700 mb-1"
                        >
                            Foto Mempelai Wanita
                        </label>
                        <FilePond
                            name="foto_wanita"
                            label-idle="Tarik & Lepas gambar atau <span class='filepond--label-action'>Telusuri</span>"
                            accepted-file-types="image/jpeg, image/png, image/jpg"
                            @updatefiles="handleFotoWanitaUpdate"
                            :files="initialFotoWanita"
                            :server="serverOptions"
                            class="mb-0 custom-filepond"
                        />
                        <p class="text-[10px] text-gray-600 mt-1 font-light">
                            Ukuran file maksimal 5MB
                        </p>
                        <p
                            v-if="modelValue.errors.foto_wanita"
                            class="text-[10px] text-red-600 mt-1 font-bold"
                        >
                            {{ modelValue.errors.foto_wanita }}
                        </p>
                    </div>
                </div>
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
