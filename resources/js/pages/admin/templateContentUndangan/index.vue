<script setup>
import { ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import adminDashboardLayout from "../../layouts/adminDashboardLayout.vue";
import { Icon } from "@iconify/vue";
import Swal from "sweetalert2";

const props = defineProps({
    templates: {
        type: Array,
        default: () => [],
    },
});

const search = ref("");
const loadingDeleteId = ref(null);

// ─── Filtered Data (Pengganti Search v-data-table) ───────────────────────────
const filteredTemplates = computed(() => {
    if (!search.value) return props.templates;
    const s = search.value.toLowerCase();
    return props.templates.filter((item) =>
        item.judul?.toLowerCase().includes(s),
    );
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

// ─── Konfirmasi Hapus ────────────────────────────────────────────────────────
const confirmDelete = (item) => {
    Swal.fire({
        title: "Hapus Konten Template?",
        html: `Apakah Anda yakin ingin menghapus konten template <b>${item.judul}</b>? Tindakan ini tidak dapat dibatalkan.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (!result.isConfirmed) return;

        loadingDeleteId.value = item.id;
        router.delete(
            route("admin.template-content-undangan.destroy", item.id),
            {
                preserveScroll: true,
                onSuccess: () =>
                    toast("success", "Konten template berhasil dihapus."),
                onError: () =>
                    toast("error", "Gagal menghapus konten template."),
                onFinish: () => {
                    loadingDeleteId.value = null;
                },
            },
        );
    });
};

// ─── Format Tanggal ──────────────────────────────────────────────────────────
const formatDate = (dateString) => {
    if (!dateString) return "-";
    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};
</script>

<template>
    <adminDashboardLayout>
        <!-- Header Title -->
        <template #headerTitle>
            <div class="flex items-center gap-2">
                <Icon
                    icon="mdi:book-open-page-variant-outline"
                    width="22"
                    class="text-[#004D31]"
                />
                <span class="font-semibold text-gray-800"
                    >Konten Template Undangan</span
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
                            Konten Template Undangan
                        </h1>
                        <p class="text-sm text-gray-600">
                            Kelola informasi dan detail untuk masing-masing
                            template.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <!-- Search Bar -->
                        <div class="relative min-w-[240px]">
                            <span
                                class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"
                            >
                                <Icon icon="mdi:magnify" width="18" />
                            </span>
                            <input
                                v-model="search"
                                type="text"
                                placeholder="Cari Judul Konten..."
                                class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-sm focus:border-[#004D31] outline-none bg-white transition-colors"
                            />
                        </div>
                        <!-- Create Button -->
                        <button
                            @click="
                                router.get(
                                    route(
                                        'admin.template-content-undangan.create-content',
                                    ),
                                )
                            "
                            class="cursor-pointer inline-flex items-center justify-center gap-2 bg-[#004D31] hover:bg-[#003824] text-white px-4 py-2 rounded-sm font-medium transition-colors border border-[#004D31]"
                        >
                            <Icon icon="mdi:plus" width="18" />
                            <span>Buat Konten Baru</span>
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
                                        class="py-3 px-4 border-r border-gray-200"
                                    >
                                        Judul Konten
                                    </th>
                                    <th
                                        class="py-3 px-4 border-r border-gray-200"
                                    >
                                        Jadwal Akad
                                    </th>
                                    <th
                                        class="py-3 px-4 w-48 border-r border-gray-200"
                                    >
                                        Terakhir Diubah
                                    </th>
                                    <th class="py-3 px-4 w-36 text-center">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Empty State -->
                                <tr v-if="filteredTemplates.length === 0">
                                    <td
                                        colspan="5"
                                        class="py-12 text-center text-gray-500"
                                    >
                                        <div class="flex flex-col items-center">
                                            <Icon
                                                icon="mdi:book-open-blank-variant"
                                                class="text-gray-300 mb-2"
                                                width="48"
                                            />
                                            <p
                                                class="text-sm font-medium text-gray-700"
                                            >
                                                Belum ada konten template
                                            </p>
                                            <p class="text-xs mt-1">
                                                Klik "Buat Konten Baru" untuk
                                                memulai
                                            </p>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Data Rows -->
                                <tr
                                    v-for="(item, index) in filteredTemplates"
                                    :key="item.id"
                                    class="border-b border-gray-200 hover:bg-gray-50 transition-colors"
                                >
                                    <td
                                        class="py-3 px-4 text-sm text-gray-700 text-center border-r border-gray-200"
                                    >
                                        {{ index + 1 }}
                                    </td>
                                    <td
                                        class="py-3 px-4 text-sm font-bold text-gray-800 border-r border-gray-200"
                                    >
                                        {{ item.judul }}
                                    </td>
                                    <td
                                        class="py-3 px-4 border-r border-gray-200"
                                    >
                                        <div class="flex flex-col gap-0.5">
                                            <div
                                                class="flex items-center gap-1.5 text-sm text-gray-700 font-medium"
                                            >
                                                <Icon
                                                    icon="mdi:calendar-heart"
                                                    class="text-[#004D31]"
                                                    width="16"
                                                />
                                                <span>{{
                                                    formatDate(
                                                        item
                                                            .template_undangan_pernikahan
                                                            ?.tanggal_mulai_akad,
                                                    )
                                                }}</span>
                                            </div>
                                            <div
                                                v-if="
                                                    item
                                                        .template_undangan_pernikahan
                                                        ?.waktu_mulai_akad
                                                "
                                                class="flex items-center gap-1.5 text-xs text-gray-500"
                                            >
                                                <Icon
                                                    icon="mdi:clock-outline"
                                                    width="14"
                                                />
                                                <span
                                                    >{{
                                                        item.template_undangan_pernikahan.waktu_mulai_akad.substring(
                                                            0,
                                                            5,
                                                        )
                                                    }}
                                                    WIB</span
                                                >
                                            </div>
                                        </div>
                                    </td>
                                    <td
                                        class="py-3 px-4 text-sm text-gray-600 border-r border-gray-200"
                                    >
                                        {{ formatDate(item.updated_at) }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <div
                                            class="flex items-center justify-center gap-1.5"
                                        >
                                            <!-- Edit Content Button -->
                                            <button
                                                @click="
                                                    router.get(
                                                        route(
                                                            'admin.template-content-undangan.edit-content',
                                                            item.id,
                                                        ),
                                                    )
                                                "
                                                class="cursor-pointer p-1.5 text-blue-700 bg-blue-50 hover:bg-blue-600 hover:text-white border border-blue-200 hover:border-blue-600 rounded-sm transition-colors"
                                                title="Edit Konten"
                                            >
                                                <Icon
                                                    icon="mdi:pencil-box-multiple-outline"
                                                    width="16"
                                                />
                                            </button>

                                            <!-- Edit Setting Button -->
                                            <button
                                                @click="
                                                    router.get(
                                                        route(
                                                            'admin.template-content-undangan.edit-setting',
                                                            item.id,
                                                        ),
                                                    )
                                                "
                                                class="cursor-pointer p-1.5 text-slate-700 bg-slate-100 hover:bg-slate-700 hover:text-white border border-slate-300 hover:border-slate-700 rounded-sm transition-colors"
                                                title="Edit Pengaturan"
                                            >
                                                <Icon
                                                    icon="mdi:cog-outline"
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
                                                title="Hapus Konten"
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
                                                    icon="mdi:trash-can-outline"
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
        </template>
    </adminDashboardLayout>
</template>
