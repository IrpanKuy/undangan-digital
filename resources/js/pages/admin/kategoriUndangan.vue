<script setup>
import { ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import adminDashboardLayout from "../layouts/adminDashboardLayout.vue";
import { Icon } from "@iconify/vue";

const props = defineProps({
    kategori: Array,
});

// ─── Dialog State ────────────────────────────────────────────────────────────
const dialogCreate = ref(false);
const dialogEdit = ref(false);
const loadingDeleteId = ref(null);

// ─── Inertia useForm ─────────────────────────────────────────────────────────
const formCreate = useForm({ name: "", status: true });
const formEdit = useForm({ id: null, name: "", status: true });

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

// ─── CREATE ──────────────────────────────────────────────────────────────────
const openCreate = () => {
    formCreate.reset();
    formCreate.clearErrors();
    dialogCreate.value = true;
};

const submitCreate = () => {
    formCreate.post(route("admin.kategori-undangan.store"), {
        preserveScroll: true,
        onSuccess: () => {
            dialogCreate.value = false;
            toast("success", "Kategori undangan berhasil ditambahkan.");
        },
    });
};

// ─── EDIT ────────────────────────────────────────────────────────────────────
const openEdit = (item) => {
    formEdit.clearErrors();
    formEdit.id = item.id;
    formEdit.name = item.name;
    formEdit.status = item.status;
    dialogEdit.value = true;
};

const submitEdit = () => {
    formEdit.put(route("admin.kategori-undangan.update", formEdit.id), {
        preserveScroll: true,
        onSuccess: () => {
            dialogEdit.value = false;
            toast("success", "Kategori undangan berhasil diperbarui.");
        },
    });
};

// ─── DELETE ──────────────────────────────────────────────────────────────────
const confirmDelete = (item) => {
    Swal.fire({
        title: "Hapus Kategori?",
        html: `Kategori <b>${item.name}</b> akan dihapus secara permanen.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (!result.isConfirmed) return;
        loadingDeleteId.value = item.id;

        router.delete(route("admin.kategori-undangan.destroy", item.id), {
            preserveScroll: true,
            onSuccess: () =>
                toast("success", "Kategori undangan berhasil dihapus."),
            onError: () => toast("error", "Gagal menghapus kategori undangan."),
            onFinish: () => {
                loadingDeleteId.value = null;
            },
        });
    });
};
</script>

<template>
    <adminDashboardLayout>
        <!-- Slot: Header Title -->
        <template #headerTitle>
            <div class="flex items-center gap-2">
                <Icon
                    icon="dashicons:category"
                    width="22"
                    class="text-[#004D31]"
                />
                <span class="font-semibold text-gray-800"
                    >Kategori Undangan</span
                >
            </div>
        </template>

        <!-- Slot: Content -->
        <template #content>
            <div class="w-full p-5">
                <!-- Page Header -->
                <div
                    class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6"
                >
                    <div>
                        <h1 class="text-xl font-bold text-gray-800 mb-1">
                            Kategori Undangan
                        </h1>
                        <p class="text-sm text-gray-600">
                            Kelola daftar kategori undangan digital
                        </p>
                    </div>
                    <div>
                        <button
                            @click="openCreate"
                            class="cursor-pointer inline-flex items-center gap-2 bg-[#004D31] hover:bg-[#003824] text-white px-4 py-2.5 rounded-sm font-medium transition-colors border border-[#004D31]"
                        >
                            <Icon icon="mdi:plus" width="18" />
                            Tambah Kategori
                        </button>
                    </div>
                </div>

                <!-- Tabel Data (Desain Flat) -->
                <div class="bg-white border border-gray-300 rounded-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="bg-gray-100 border-b-2 border-gray-300 text-sm font-semibold text-gray-700"
                                >
                                    <th
                                        class="py-3 px-4 w-16 text-center border-r border-gray-200"
                                    >
                                        No
                                    </th>
                                    <th
                                        class="py-3 px-4 border-r border-gray-200"
                                    >
                                        Nama Kategori
                                    </th>
                                    <th
                                        class="py-3 px-4 w-40 border-r border-gray-200"
                                    >
                                        Status
                                    </th>
                                    <th class="py-3 px-4 w-32 text-center">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Empty State -->
                                <tr v-if="kategori.length === 0">
                                    <td
                                        colspan="4"
                                        class="py-10 text-center text-gray-500"
                                    >
                                        <div
                                            class="flex flex-col items-center justify-center"
                                        >
                                            <Icon
                                                icon="mdi:tag-off-outline"
                                                class="text-gray-400 mb-2"
                                                width="48"
                                            />
                                            <p
                                                class="text-sm font-medium text-gray-700"
                                            >
                                                Belum ada kategori
                                            </p>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Data Rows -->
                                <tr
                                    v-for="(item, index) in kategori"
                                    :key="item.id"
                                    class="border-b border-gray-200 hover:bg-gray-50 transition-colors"
                                >
                                    <td
                                        class="py-2.5 px-4 text-sm text-gray-700 text-center border-r border-gray-200"
                                    >
                                        {{ index + 1 }}
                                    </td>
                                    <td
                                        class="py-2.5 px-4 text-sm font-medium text-gray-800 border-r border-gray-200"
                                    >
                                        {{ item.name }}
                                    </td>
                                    <td
                                        class="py-2.5 px-4 border-r border-gray-200"
                                    >
                                        <!-- Status Badge (Flat) -->
                                        <span
                                            :class="[
                                                'inline-flex items-center gap-1.5 px-2 py-0.5 rounded-sm text-xs font-medium w-max border',
                                                item.status
                                                    ? 'bg-green-50 text-green-700 border-green-200'
                                                    : 'bg-red-50 text-red-700 border-red-200',
                                            ]"
                                        >
                                            <Icon
                                                :icon="
                                                    item.status
                                                        ? 'mdi:check'
                                                        : 'mdi:close'
                                                "
                                                width="14"
                                            />
                                            {{
                                                item.status
                                                    ? "Aktif"
                                                    : "Tidak Aktif"
                                            }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-4">
                                        <div
                                            class="flex items-center justify-center gap-1.5"
                                        >
                                            <!-- Edit Button -->
                                            <button
                                                @click="openEdit(item)"
                                                class="cursor-pointer p-1.5 text-blue-700 bg-blue-50 hover:bg-blue-600 hover:text-white border border-blue-200 hover:border-blue-600 rounded-sm transition-colors"
                                                title="Edit"
                                            >
                                                <Icon
                                                    icon="mdi:pencil"
                                                    width="16"
                                                />
                                            </button>

                                            <!-- Delete Button -->
                                            <button
                                                @click="confirmDelete(item)"
                                                :disabled="
                                                    loadingDeleteId === item.id
                                                "
                                                class="cursor-pointer p-1.5 text-red-700 bg-red-50 hover:bg-red-600 hover:text-white border border-red-200 hover:border-red-600 rounded-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                                title="Hapus"
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

            <!-- ═══════════════ DIALOG CREATE (Flat UI Modal) ═══════════════ -->
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
                    class="fixed inset-0 z-60 flex items-center justify-center p-4"
                >
                    <!-- Backdrop -->
                    <div
                        class="absolute inset-0 bg-gray-900/60"
                        @click="
                            !formCreate.processing && (dialogCreate = false)
                        "
                    ></div>

                    <!-- Modal Panel -->
                    <div
                        class="relative w-full max-w-md bg-white rounded-sm shadow-lg border border-gray-300"
                    >
                        <!-- Header -->
                        <div
                            class="bg-gray-100 border-b border-gray-300 px-5 py-3 flex items-center justify-between"
                        >
                            <div class="flex items-center gap-2 text-gray-800">
                                <Icon icon="mdi:tag-plus" width="20" />
                                <h3 class="text-base font-semibold">
                                    Tambah Kategori Undangan
                                </h3>
                            </div>
                            <button
                                @click="dialogCreate = false"
                                class="cursor-pointer text-gray-500 hover:text-gray-800"
                            >
                                <Icon icon="mdi:close" width="20" />
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="p-5">
                            <div class="space-y-4">
                                <!-- Input Nama -->
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        Nama Kategori
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        v-model="formCreate.name"
                                        type="text"
                                        autofocus
                                        placeholder="Contoh: Pernikahan, Ulang Tahun..."
                                        :class="[
                                            'w-full border rounded-sm px-3 py-2 text-sm outline-none transition-colors',
                                            formCreate.errors.name
                                                ? 'border-red-500 bg-red-50'
                                                : 'border-gray-400 focus:border-[#004D31]',
                                        ]"
                                    />
                                    <p
                                        v-if="formCreate.errors.name"
                                        class="mt-1 text-xs text-red-600"
                                    >
                                        {{ formCreate.errors.name }}
                                    </p>
                                </div>

                                <!-- Select Status -->
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        Status
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        v-model="formCreate.status"
                                        :class="[
                                            'cursor-pointer w-full border rounded-sm px-3 py-2 text-sm outline-none transition-colors bg-white',
                                            formCreate.errors.status
                                                ? 'border-red-500 bg-red-50'
                                                : 'border-gray-400 focus:border-[#004D31]',
                                        ]"
                                    >
                                        <option :value="true">Aktif</option>
                                        <option :value="false">
                                            Tidak Aktif
                                        </option>
                                    </select>
                                    <p
                                        v-if="formCreate.errors.status"
                                        class="mt-1 text-xs text-red-600"
                                    >
                                        {{ formCreate.errors.status }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div
                            class="bg-gray-50 px-5 py-3 border-t border-gray-300 flex justify-end gap-2"
                        >
                            <button
                                type="button"
                                @click="dialogCreate = false"
                                :disabled="formCreate.processing"
                                class="cursor-pointer px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-400 rounded-sm hover:bg-gray-100 disabled:opacity-50 transition-colors"
                            >
                                Batal
                            </button>
                            <button
                                type="button"
                                @click="submitCreate"
                                :disabled="formCreate.processing"
                                class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-[#004D31] border border-[#004D31] rounded-sm hover:bg-[#003824] disabled:opacity-50 transition-colors"
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
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </transition>

            <!-- ═══════════════ DIALOG EDIT (Flat UI Modal) ═══════════════ -->
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
                    <!-- Backdrop -->
                    <div
                        class="absolute inset-0 bg-gray-900/60"
                        @click="!formEdit.processing && (dialogEdit = false)"
                    ></div>

                    <!-- Modal Panel -->
                    <div
                        class="relative w-full max-w-md bg-white rounded-sm shadow-lg border border-gray-300"
                    >
                        <!-- Header -->
                        <div
                            class="bg-gray-100 border-b border-gray-300 px-5 py-3 flex items-center justify-between"
                        >
                            <div class="flex items-center gap-2 text-gray-800">
                                <Icon icon="mdi:tag-edit" width="20" />
                                <h3 class="text-base font-semibold">
                                    Edit Kategori Undangan
                                </h3>
                            </div>
                            <button
                                @click="dialogEdit = false"
                                class="cursor-pointer text-gray-500 hover:text-gray-800"
                            >
                                <Icon icon="mdi:close" width="20" />
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="p-5">
                            <div class="space-y-4">
                                <!-- Input Nama -->
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        Nama Kategori
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        v-model="formEdit.name"
                                        type="text"
                                        placeholder="Contoh: Pernikahan, Ulang Tahun..."
                                        :class="[
                                            'w-full border rounded-sm px-3 py-2 text-sm outline-none transition-colors',
                                            formEdit.errors.name
                                                ? 'border-red-500 bg-red-50'
                                                : 'border-gray-400 focus:border-[#004D31]',
                                        ]"
                                    />
                                    <p
                                        v-if="formEdit.errors.name"
                                        class="mt-1 text-xs text-red-600"
                                    >
                                        {{ formEdit.errors.name }}
                                    </p>
                                </div>

                                <!-- Select Status -->
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        Status
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        v-model="formEdit.status"
                                        :class="[
                                            'cursor-pointer w-full border rounded-sm px-3 py-2 text-sm outline-none transition-colors bg-white',
                                            formEdit.errors.status
                                                ? 'border-red-500 bg-red-50'
                                                : 'border-gray-400 focus:border-[#004D31]',
                                        ]"
                                    >
                                        <option :value="true">Aktif</option>
                                        <option :value="false">
                                            Tidak Aktif
                                        </option>
                                    </select>
                                    <p
                                        v-if="formEdit.errors.status"
                                        class="mt-1 text-xs text-red-600"
                                    >
                                        {{ formEdit.errors.status }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div
                            class="bg-gray-50 px-5 py-3 border-t border-gray-300 flex justify-end gap-2"
                        >
                            <button
                                type="button"
                                @click="dialogEdit = false"
                                :disabled="formEdit.processing"
                                class="cursor-pointer px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-400 rounded-sm hover:bg-gray-100 disabled:opacity-50 transition-colors"
                            >
                                Batal
                            </button>
                            <button
                                type="button"
                                @click="submitEdit"
                                :disabled="formEdit.processing"
                                class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-[#004D31] border border-[#004D31] rounded-sm hover:bg-[#003824] disabled:opacity-50 transition-colors"
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
                                Perbarui
                            </button>
                        </div>
                    </div>
                </div>
            </transition>
        </template>
    </adminDashboardLayout>
</template>
