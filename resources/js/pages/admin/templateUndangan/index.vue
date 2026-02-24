<script setup>
import { ref, computed } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import adminDashboardLayout from "../../layouts/adminDashboardLayout.vue";
import { Icon } from "@iconify/vue";

// --- FilePond Imports ---
import vueFilePond from "vue-filepond";

// Import FilePond styles
import "filepond/dist/filepond.min.css";

// Import FilePond plugins
// Please note that you need to install these plugins separately

// Import image preview plugin styles
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";

// Import image preview and file type validation plugins
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";

// Create FilePond component
const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
);

const props = defineProps({
    templates: Array,
    categories: Array,
    availableUndangans: Array,
});

// ─── State ───────────────────────────────────────────────────────────────────
const dialogCreate = ref(false);
const dialogEdit = ref(false);
const loadingDeleteId = ref(null);
const search = ref("");

// ─── Filtered Data ───────────────────────────────────────────────────────────
const filteredTemplates = computed(() => {
    if (!search.value) return props.templates;
    return props.templates.filter(
        (item) =>
            item.judul_undangan
                .toLowerCase()
                .includes(search.value.toLowerCase()) ||
            item.kategori_undangan?.name
                .toLowerCase()
                .includes(search.value.toLowerCase()),
    );
});

// ─── Inertia useForm ─────────────────────────────────────────────────────────
const formCreate = useForm({
    undangan_id: null,
    judul_undangan: "",
    kategori_undangan_id: null,
    file_name: "",
    thumbnail_template: null,
    template_premium: false,
    is_active: true,
    music_url: "",
});

const formEdit = useForm({
    id: null,
    undangan_id: null,
    judul_undangan: "",
    kategori_undangan_id: null,
    file_name: "",
    thumbnail_template: null,
    template_premium: false,
    is_active: true,
    music_url: "",
});

// ─── Toast Helper ────────────────────────────────────────────────────────────
const toast = (type, message) => {
    Swal.fire({
        icon: type,
        title: type === "success" ? "Berhasil!" : "Gagal!",
        text: message,
        timer: 2500,
        showConfirmButton: false,
        toast: true,
        position: "top-end",
    });
};

// ─── FilePond Handlers ───────────────────────────────────────────────────────
const handleFileProcessCreate = (error, file) => {
    if (!error) formCreate.thumbnail_template = file.file;
};
const handleFileRemoveCreate = () => {
    formCreate.thumbnail_template = null;
};

const handleFileProcessEdit = (error, file) => {
    if (!error) formEdit.thumbnail_template = file.file;
};
const handleFileRemoveEdit = () => {
    formEdit.thumbnail_template = null;
};

// ─── Actions ─────────────────────────────────────────────────────────────────
const openCreate = () => {
    formCreate.reset();
    formCreate.clearErrors();
    dialogCreate.value = true;
};

const submitCreate = () => {
    formCreate.post(route("admin.template-undangan.store"), {
        preserveScroll: true,
        onSuccess: () => {
            dialogCreate.value = false;
            toast("success", "Template berhasil ditambahkan.");
        },
    });
};

const openEdit = (item) => {
    formEdit.clearErrors();
    formEdit.id = item.id;
    formEdit.undangan_id = item.undangan_id;
    formEdit.judul_undangan = item.judul_undangan;
    formEdit.kategori_undangan_id = item.kategori_undangan_id;
    formEdit.file_name = item.file_name;
    formEdit.template_premium = item.template_premium == 1; // force boolean
    formEdit.is_active = item.is_active == 1; // force boolean
    formEdit.music_url = item.music_url;
    formEdit.thumbnail_template = null;
    dialogEdit.value = true;
};

const submitEdit = () => {
    formEdit
        .transform((data) => ({
            ...data,
            _method: "put",
        }))
        .post(route("admin.template-undangan.update", formEdit.id), {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                dialogEdit.value = false;
                toast("success", "Template berhasil diperbarui.");
            },
        });
};

const confirmDelete = (item) => {
    Swal.fire({
        title: "Hapus Template?",
        text: `Template ${item.judul_undangan} akan dihapus permanen.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Ya, Hapus",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (!result.isConfirmed) return;
        loadingDeleteId.value = item.id;
        router.delete(route("admin.template-undangan.destroy", item.id), {
            preserveScroll: true,
            onFinish: () => (loadingDeleteId.value = null),
        });
    });
};
</script>

<template>
    <adminDashboardLayout>
        <!-- Header Title -->
        <template #headerTitle>
            <div class="flex items-center gap-2">
                <Icon
                    icon="mdi:certificate-outline"
                    width="22"
                    class="text-[#004D31]"
                />
                <span class="font-semibold text-gray-800"
                    >Manajemen Template</span
                >
            </div>
        </template>

        <!-- Main Content -->
        <template #content>
            <div class="w-full">
                <!-- Page Header -->
                <div
                    class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6"
                >
                    <div>
                        <h1 class="text-xl font-bold text-gray-800 mb-1">
                            Template Undangan
                        </h1>
                        <p class="text-sm text-gray-600">
                            Kelola daftar desain template undangan yang
                            tersedia.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <!-- Search Bar (Flat) -->
                        <div class="relative min-w-[240px]">
                            <span
                                class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"
                            >
                                <Icon icon="mdi:magnify" width="18" />
                            </span>
                            <input
                                v-model="search"
                                type="text"
                                placeholder="Cari Judul / Kategori..."
                                class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-sm focus:border-[#004D31] outline-none bg-white transition-colors"
                            />
                        </div>
                        <button
                            @click="openCreate"
                            class="cursor-pointer inline-flex items-center justify-center gap-2 bg-[#004D31] hover:bg-[#003824] text-white px-4 py-2 rounded-sm font-medium transition-colors border border-[#004D31]"
                        >
                            <Icon icon="mdi:plus" width="18" />
                            <span>Tambah Template</span>
                        </button>
                    </div>
                </div>

                <!-- Table Area (Flat Design) -->
                <div class="bg-white border border-gray-300 rounded-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="bg-gray-100 border-b-2 border-gray-300 text-sm font-semibold text-gray-700 uppercase tracking-wider"
                                >
                                    <th
                                        class="py-3 px-4 w-16 text-center border-r border-gray-200"
                                    >
                                        No
                                    </th>
                                    <th
                                        class="py-3 px-4 w-24 text-center border-r border-gray-200"
                                    >
                                        Preview
                                    </th>
                                    <th
                                        class="py-3 px-4 border-r border-gray-200"
                                    >
                                        Judul Undangan
                                    </th>
                                    <th
                                        class="py-3 px-4 border-r border-gray-200"
                                    >
                                        Kategori
                                    </th>
                                    <th
                                        class="py-3 px-4 w-28 border-r border-gray-200"
                                    >
                                        Lisensi
                                    </th>
                                    <th
                                        class="py-3 px-4 w-28 border-r border-gray-200"
                                    >
                                        Status
                                    </th>
                                    <th class="py-3 px-4 w-32 text-center">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="filteredTemplates.length === 0">
                                    <td
                                        colspan="7"
                                        class="py-12 text-center text-gray-500"
                                    >
                                        <div class="flex flex-col items-center">
                                            <Icon
                                                icon="mdi:certificate-outline"
                                                class="text-gray-300 mb-2"
                                                width="48"
                                            />
                                            <p class="text-sm font-medium">
                                                Data template tidak ditemukan
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                <tr
                                    v-for="(item, index) in filteredTemplates"
                                    :key="item.id"
                                    class="border-b border-gray-200 hover:bg-gray-50 transition-colors"
                                >
                                    <td
                                        class="py-2.5 px-4 text-sm text-gray-700 text-center border-r border-gray-200"
                                    >
                                        {{ index + 1 }}
                                    </td>
                                    <td
                                        class="py-2.5 px-4 border-r border-gray-200"
                                    >
                                        <div
                                            class="w-14 h-14 bg-gray-100 border border-gray-200 overflow-hidden flex items-center justify-center rounded-sm"
                                        >
                                            <img
                                                v-if="item.thumbnail_template"
                                                :src="`/storage/${item.thumbnail_template}`"
                                                class="w-full h-full object-cover"
                                            />
                                            <Icon
                                                v-else
                                                icon="mdi:image-off-outline"
                                                class="text-gray-300"
                                                width="20"
                                            />
                                        </div>
                                    </td>
                                    <td
                                        class="py-2.5 px-4 text-sm font-bold text-gray-800 border-r border-gray-200"
                                    >
                                        {{ item.judul_undangan }}
                                        <div
                                            class="text-[10px] font-normal text-gray-500 mt-0.5 flex items-center gap-1"
                                        >
                                            <Icon
                                                icon="mdi:account-group-outline"
                                            />
                                            {{ item.usage || 0 }} penggunaan
                                        </div>
                                    </td>
                                    <td
                                        class="py-2.5 px-4 text-sm text-gray-700 border-r border-gray-200"
                                    >
                                        {{ item.kategori_undangan?.name }}
                                    </td>
                                    <td
                                        class="py-2.5 px-4 border-r border-gray-200"
                                    >
                                        <span
                                            :class="[
                                                'px-2 py-0.5 text-[10px] font-bold uppercase border rounded-sm',
                                                item.template_premium
                                                    ? 'bg-amber-50 text-amber-700 border-amber-200'
                                                    : 'bg-slate-50 text-slate-700 border-slate-200',
                                            ]"
                                        >
                                            {{
                                                item.template_premium
                                                    ? "Premium"
                                                    : "Gratis"
                                            }}
                                        </span>
                                    </td>
                                    <td
                                        class="py-2.5 px-4 border-r border-gray-200"
                                    >
                                        <span
                                            :class="[
                                                'px-2 py-0.5 text-[10px] font-bold uppercase border rounded-sm',
                                                item.is_active
                                                    ? 'bg-green-50 text-green-700 border-green-200'
                                                    : 'bg-red-50 text-red-700 border-red-200',
                                            ]"
                                        >
                                            {{
                                                item.is_active ? "Aktif" : "Off"
                                            }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-4">
                                        <div
                                            class="flex items-center justify-center gap-1.5"
                                        >
                                            <button
                                                @click="openEdit(item)"
                                                class="cursor-pointer p-1.5 text-blue-700 bg-blue-50 hover:bg-blue-600 hover:text-white border border-blue-200 hover:border-blue-600 rounded-sm transition-colors shadow-sm"
                                            >
                                                <Icon
                                                    icon="mdi:pencil"
                                                    width="16"
                                                />
                                            </button>
                                            <button
                                                @click="confirmDelete(item)"
                                                :disabled="
                                                    loadingDeleteId === item.id
                                                "
                                                class="cursor-pointer p-1.5 text-red-700 bg-red-50 hover:bg-red-600 hover:text-white border border-red-200 hover:border-red-600 rounded-sm transition-colors disabled:opacity-50"
                                            >
                                                <Icon
                                                    v-if="
                                                        loadingDeleteId ===
                                                        item.id
                                                    "
                                                    icon="mdi:loading"
                                                    class="animate-spin"
                                                    width="16"
                                                />
                                                <Icon
                                                    v-else
                                                    icon="mdi:delete"
                                                    width="16"
                                                />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal Create (Flat Style) -->
            <transition
                enter-active-class="transition duration-150 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="dialogCreate"
                    class="fixed inset-0 z-[60] flex items-center justify-center p-4"
                >
                    <div
                        class="absolute inset-0 bg-gray-900/60"
                        @click="
                            !formCreate.processing && (dialogCreate = false)
                        "
                    ></div>
                    <div
                        class="relative w-full max-w-2xl bg-white rounded-sm shadow-xl border border-gray-300 max-h-[95vh] flex flex-col overflow-hidden"
                    >
                        <!-- Header -->
                        <div
                            class="bg-gray-100 border-b border-gray-300 px-5 py-3 flex items-center justify-between shrink-0"
                        >
                            <div
                                class="flex items-center gap-2 font-bold text-gray-800"
                            >
                                <Icon
                                    icon="mdi:certificate-outline"
                                    width="20"
                                />
                                <span>Tambah Template Baru</span>
                            </div>
                            <button
                                @click="dialogCreate = false"
                                class="cursor-pointer text-gray-500 hover:text-red-600 transition-colors"
                            >
                                <Icon icon="mdi:close" width="24" />
                            </button>
                        </div>

                        <!-- Form Body -->
                        <div class="p-6 overflow-y-auto space-y-4">
                            <!-- Row 1 -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-700 uppercase mb-1"
                                        >Konten Dasar
                                        <span class="text-red-500"
                                            >*</span
                                        ></label
                                    >
                                    <select
                                        v-model="formCreate.undangan_id"
                                        class="cursor-pointer w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                                    >
                                        <option :value="null">
                                            Pilih Undangan
                                        </option>
                                        <option
                                            v-for="u in availableUndangans"
                                            :key="u.id"
                                            :value="u.id"
                                        >
                                            {{ u.judul }}
                                        </option>
                                    </select>
                                    <p
                                        v-if="formCreate.errors.undangan_id"
                                        class="text-[10px] text-red-600 mt-1 uppercase font-bold"
                                    >
                                        {{ formCreate.errors.undangan_id }}
                                    </p>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-700 uppercase mb-1"
                                        >Kategori
                                        <span class="text-red-500"
                                            >*</span
                                        ></label
                                    >
                                    <select
                                        v-model="
                                            formCreate.kategori_undangan_id
                                        "
                                        class="cursor-pointer w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                                    >
                                        <option :value="null">
                                            Pilih Kategori
                                        </option>
                                        <option
                                            v-for="c in categories"
                                            :key="c.id"
                                            :value="c.id"
                                        >
                                            {{ c.name }}
                                        </option>
                                    </select>
                                    <p
                                        v-if="
                                            formCreate.errors
                                                .kategori_undangan_id
                                        "
                                        class="text-[10px] text-red-600 mt-1 uppercase font-bold"
                                    >
                                        {{
                                            formCreate.errors
                                                .kategori_undangan_id
                                        }}
                                    </p>
                                </div>
                            </div>

                            <!-- Row 2 -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-700 uppercase mb-1"
                                        >Judul Undangan
                                        <span class="text-red-500"
                                            >*</span
                                        ></label
                                    >
                                    <input
                                        v-model="formCreate.judul_undangan"
                                        type="text"
                                        placeholder="Contoh: Gold Floral"
                                        class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                                    />
                                    <p
                                        v-if="formCreate.errors.judul_undangan"
                                        class="text-[10px] text-red-600 mt-1 uppercase font-bold"
                                    >
                                        {{ formCreate.errors.judul_undangan }}
                                    </p>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-700 uppercase mb-1"
                                        >Nama File (Vue)
                                        <span class="text-red-600"
                                            >*</span
                                        ></label
                                    >
                                    <input
                                        v-model="formCreate.file_name"
                                        type="text"
                                        placeholder="Contoh: gold-floral"
                                        class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                                    />
                                    <p
                                        v-if="formCreate.errors.file_name"
                                        class="text-[10px] text-red-600 mt-1 uppercase font-bold"
                                    >
                                        {{ formCreate.errors.file_name }}
                                    </p>
                                </div>
                            </div>

                            <!-- Row 3 -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-700 uppercase mb-1"
                                        >Lisensi
                                        <span class="text-red-500"
                                            >*</span
                                        ></label
                                    >
                                    <select
                                        v-model="formCreate.template_premium"
                                        class="cursor-pointer w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                                    >
                                        <option :value="false">Gratis</option>
                                        <option :value="true">Premium</option>
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-700 uppercase mb-1"
                                        >Status Publikasi
                                        <span class="text-red-500"
                                            >*</span
                                        ></label
                                    >
                                    <select
                                        v-model="formCreate.is_active"
                                        class="cursor-pointer w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                                    >
                                        <option :value="true">Aktif</option>
                                        <option :value="false">
                                            Tidak Aktif
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Music URL -->
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-700 uppercase mb-1"
                                    >URL Music (Opsional)</label
                                >
                                <input
                                    v-model="formCreate.music_url"
                                    type="text"
                                    placeholder="https://..."
                                    class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                                />
                            </div>

                            <!-- FilePond Upload -->
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-700 uppercase mb-1"
                                    >Thumbnail Preview
                                    <span class="text-red-500">*</span></label
                                >
                                <file-pond
                                    class="cursor-pointer"
                                    name="thumbnail"
                                    label-idle="Tarik gambar ke sini atau <span class='filepond--label-action'>Cari</span>"
                                    accepted-file-types="image/jpeg, image/png"
                                    @addfile="handleFileProcessCreate"
                                    @removefile="handleFileRemoveCreate"
                                />
                                <p
                                    v-if="formCreate.errors.thumbnail_template"
                                    class="text-[10px] text-red-600 mt-1 uppercase font-bold"
                                >
                                    {{ formCreate.errors.thumbnail_template }}
                                </p>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div
                            class="bg-gray-100 border-t border-gray-300 px-6 py-4 flex justify-end gap-2 shrink-0"
                        >
                            <button
                                @click="dialogCreate = false"
                                class="cursor-pointer px-4 py-2 text-sm font-bold text-gray-600 bg-white border border-gray-400 rounded-sm hover:bg-gray-200 transition-colors uppercase tracking-tighter"
                            >
                                Batal
                            </button>
                            <button
                                @click="submitCreate"
                                :disabled="formCreate.processing"
                                class="cursor-pointer inline-flex items-center gap-2 px-6 py-2 text-sm font-bold text-white bg-[#004D31] rounded-sm hover:bg-[#003824] transition-colors uppercase tracking-tighter disabled:opacity-50"
                            >
                                <Icon
                                    v-if="formCreate.processing"
                                    icon="mdi:loading"
                                    class="animate-spin"
                                    width="16"
                                />
                                <Icon
                                    v-else
                                    icon="mdi:content-save"
                                    width="16"
                                />
                                Simpan Template
                            </button>
                        </div>
                    </div>
                </div>
            </transition>

            <!-- Modal Edit (Flat Style) -->
            <transition
                enter-active-class="transition duration-150 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="dialogEdit"
                    class="fixed inset-0 z-[60] flex items-center justify-center p-4"
                >
                    <div
                        class="absolute inset-0 bg-gray-900/60"
                        @click="!formEdit.processing && (dialogEdit = false)"
                    ></div>
                    <div
                        class="relative w-full max-w-2xl bg-white rounded-sm shadow-xl border border-gray-300 max-h-[95vh] flex flex-col overflow-hidden"
                    >
                        <!-- Header -->
                        <div
                            class="bg-gray-100 border-b border-gray-300 px-5 py-3 flex items-center justify-between shrink-0"
                        >
                            <div
                                class="flex items-center gap-2 font-bold text-gray-800"
                            >
                                <Icon icon="mdi:pencil" width="20" />
                                <span>Perbarui Template</span>
                            </div>
                            <button
                                @click="dialogEdit = false"
                                class="cursor-pointer text-gray-500 hover:text-red-600 transition-colors"
                            >
                                <Icon icon="mdi:close" width="24" />
                            </button>
                        </div>

                        <!-- Form Body -->
                        <div class="p-6 overflow-y-auto space-y-4">
                            <!-- Fields (Sama dengan Create) -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-700 uppercase mb-1"
                                        >Konten Dasar</label
                                    >
                                    <select
                                        v-model="formEdit.undangan_id"
                                        class="cursor-pointer w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                                    >
                                        <option
                                            v-for="u in availableUndangans"
                                            :key="u.id"
                                            :value="u.id"
                                        >
                                            {{ u.judul }}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-700 uppercase mb-1"
                                        >Kategori</label
                                    >
                                    <select
                                        v-model="formEdit.kategori_undangan_id"
                                        class="cursor-pointer w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                                    >
                                        <option
                                            v-for="c in categories"
                                            :key="c.id"
                                            :value="c.id"
                                        >
                                            {{ c.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-700 uppercase mb-1"
                                        >Judul Undangan</label
                                    >
                                    <input
                                        v-model="formEdit.judul_undangan"
                                        type="text"
                                        class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-700 uppercase mb-1"
                                        >Nama File (Vue)</label
                                    >
                                    <input
                                        v-model="formEdit.file_name"
                                        type="text"
                                        class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                                    />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-700 uppercase mb-1"
                                        >Lisensi</label
                                    >
                                    <select
                                        v-model="formEdit.template_premium"
                                        class="cursor-pointer w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                                    >
                                        <option :value="false">Gratis</option>
                                        <option :value="true">Premium</option>
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-700 uppercase mb-1"
                                        >Status Publikasi</label
                                    >
                                    <select
                                        v-model="formEdit.is_active"
                                        class="cursor-pointer w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                                    >
                                        <option :value="true">Aktif</option>
                                        <option :value="false">
                                            Tidak Aktif
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-700 uppercase mb-1"
                                    >URL Music</label
                                >
                                <input
                                    v-model="formEdit.music_url"
                                    type="text"
                                    class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                                />
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-700 uppercase mb-1 text-blue-700"
                                    >Ganti Thumbnail (Opsional)</label
                                >
                                <file-pond
                                    class="cursor-pointer"
                                    name="thumbnail_edit"
                                    label-idle="Tarik gambar baru ke sini untuk mengganti"
                                    accepted-file-types="image/jpeg, image/png"
                                    @addfile="handleFileProcessEdit"
                                    @removefile="handleFileRemoveEdit"
                                />
                                <p
                                    class="text-[10px] text-gray-400 italic font-bold"
                                >
                                    Kosongkan jika tidak ingin mengubah gambar.
                                </p>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div
                            class="bg-gray-100 border-t border-gray-300 px-6 py-4 flex justify-end gap-2 shrink-0"
                        >
                            <button
                                @click="dialogEdit = false"
                                class="cursor-pointer px-4 py-2 text-sm font-bold text-gray-600 bg-white border border-gray-400 rounded-sm hover:bg-gray-200 transition-colors uppercase tracking-tighter"
                            >
                                Batal
                            </button>
                            <button
                                @click="submitEdit"
                                :disabled="formEdit.processing"
                                class="cursor-pointer inline-flex items-center gap-2 px-6 py-2 text-sm font-bold text-white bg-[#004D31] rounded-sm hover:bg-[#003824] transition-colors uppercase tracking-tighter disabled:opacity-50"
                            >
                                <Icon
                                    v-if="formEdit.processing"
                                    icon="mdi:loading"
                                    class="animate-spin"
                                    width="16"
                                />
                                <Icon
                                    v-else
                                    icon="mdi:content-save-edit"
                                    width="16"
                                />
                                Perbarui Template
                            </button>
                        </div>
                    </div>
                </div>
            </transition>
        </template>
    </adminDashboardLayout>
</template>

<style>
/* Kustomisasi FilePond agar lebih flat dan serasi */
.filepond--panel-root {
    background-color: #f9fafb;
    border: 1px solid #d1d5db;
    border-radius: 2px !important;
}
.filepond--drop-label {
    color: #6b7280;
    font-size: 13px;
    font-weight: 600;
}
.filepond--label-action {
    text-decoration-color: #004d31;
    color: #004d31;
}
.filepond--item-panel {
    background-color: #004d31 !important;
}
</style>
