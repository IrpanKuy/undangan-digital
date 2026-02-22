<script setup>
import { ref } from "vue";
import { Link, useForm, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import adminDashboardLayout from "../../layouts/adminDashboardLayout.vue";

const props = defineProps({
    templates: Array,
});

const loadingDeleteId = ref(null);

/**
 * Toast Helper
 * Menggunakan SweetAlert Toast untuk memberikan feedback sukses/error
 */
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

/**
 * Konfirmasi Hapus
 * Menampilkan SweetAlert Modal untuk konfirmasi penghapusan data
 */
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
        const deleteForm = useForm({});
        deleteForm.delete(
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

/**
 * Format Tanggal
 * Mengubah string tanggal menjadi format bahasa Indonesia yang lebih terbaca
 */
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
        <!-- Slot: Header Title -->
        <template #headerTitle>
            <Icon
                icon="mdi:book-open-page-variant-outline"
                width="22"
                color="#004D31"
            />
            Konten Template Undangan
        </template>

        <!-- Slot: Content -->
        <template #content>
            <v-container fluid class="p-0">
                <!-- Page Header -->
                <v-row align="center" justify="space-between" class="mb-5 px-3">
                    <v-col cols="12" md="auto">
                        <p class="text-xl font-bold text-gray-700 mb-1">
                            Konten Template Undangan
                        </p>
                        <p class="text-sm text-gray-500">
                            Kelola daftar konten awal yang akan digunakan pada
                            setiap template undangan
                        </p>
                    </v-col>
                    <v-col cols="12" md="auto">
                        <v-btn
                            color="primary"
                            prepend-icon="mdi-plus"
                            @click="
                                router.get(
                                    route(
                                        'admin.template-content-undangan.create-content',
                                    ),
                                )
                            "
                        >
                            Buat Template
                        </v-btn>
                    </v-col>
                </v-row>

                <!-- Grid Template (Responsive) -->
                <!-- Menggunakan Vuetify Grid (v-row & v-col) untuk tampilan responsif di Mobile, Tablet, & Desktop -->
                <v-row v-if="templates && templates.length > 0" class="px-1">
                    <v-col
                        v-for="item in templates"
                        :key="item.id"
                        cols="12"
                        sm="6"
                        md="4"
                        lg="3"
                    >
                        <v-card
                            elevation="2"
                            rounded="lg"
                            class="h-full flex flex-col hover:shadow-lg transition-shadow duration-300"
                        >
                            <!-- Thumbnail Paling Atas -->
                            <v-img
                                src="https://placehold.co/600x400?text=No+Image"
                                height="220"
                                cover
                                class="bg-gray-100 rounded-t-lg"
                            >
                                <template #placeholder>
                                    <div
                                        class="flex items-center justify-center h-full"
                                    >
                                        <v-progress-circular
                                            indeterminate
                                            color="primary"
                                        />
                                    </div>
                                </template>
                            </v-img>

                            <v-card-item class="pb-0 pt-4">
                                <!-- Judul Undangan -->
                                <v-card-title
                                    class="text-base font-bold text-gray-800 leading-tight"
                                >
                                    {{ item.judul }}
                                </v-card-title>
                            </v-card-item>

                            <v-card-text class="grow pt-2">
                                <!-- Waktu Akad Nikah dari Relasi -->
                                <div
                                    class="flex items-center gap-2 text-sm text-gray-600 mb-1"
                                >
                                    <v-icon
                                        icon="mdi-calendar-heart"
                                        size="18"
                                        color="indigo"
                                    />
                                    <span class="font-medium">
                                        {{
                                            formatDate(
                                                item
                                                    .template_undangan_pernikahan
                                                    ?.tanggal_mulai_akad,
                                            )
                                        }}
                                    </span>
                                </div>
                                <div
                                    class="flex items-center gap-2 text-sm text-gray-600"
                                    v-if="
                                        item.template_undangan_pernikahan
                                            ?.waktu_mulai_akad
                                    "
                                >
                                    <v-icon
                                        icon="mdi-clock-time-four-outline"
                                        size="18"
                                        color="indigo"
                                    />
                                    <span>
                                        {{
                                            item.template_undangan_pernikahan?.waktu_mulai_akad.substring(
                                                0,
                                                5,
                                            )
                                        }}
                                        WIB
                                    </span>
                                </div>
                            </v-card-text>

                            <v-divider class="mx-4" />

                            <!-- Tombol Aksi -->
                            <v-card-actions class="p-4 gap-2 flex flex-col">
                                <div class="w-full flex gap-2">
                                    <v-btn
                                        variant="flat"
                                        color="primary"
                                        size="x-small"
                                        class="grow font-bold text-xs"
                                        prepend-icon="mdi-pencil-box-multiple"
                                        @click="
                                            router.get(
                                                route(
                                                    'admin.template-content-undangan.edit-content',
                                                    item.id,
                                                ),
                                            )
                                        "
                                    >
                                        Edit Konten
                                    </v-btn>
                                    <v-btn
                                        variant="flat"
                                        color="secondary"
                                        size="x-small"
                                        class="grow font-bold text-xs"
                                        prepend-icon="mdi-cog"
                                        @click="
                                            router.get(
                                                route(
                                                    'admin.template-content-undangan.edit-setting',
                                                    item.id,
                                                ),
                                            )
                                        "
                                    >
                                        Edit Setting
                                    </v-btn>
                                </div>
                                <v-btn
                                    variant="flat"
                                    color="error"
                                    size="x-small"
                                    block
                                    class="font-bold text-xs"
                                    prepend-icon="mdi-trash-can-outline"
                                    :loading="loadingDeleteId === item.id"
                                    @click="confirmDelete(item)"
                                >
                                    Hapus
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>

                <!-- Empty State (Jika Data Kosong) -->
                <div
                    v-else
                    class="flex flex-col items-center justify-center py-32 text-gray-400"
                >
                    <v-icon
                        icon="mdi-book-open-blank-variant"
                        size="100"
                        class="mb-4 opacity-10"
                    />
                    <p class="text-xl font-bold">Belum ada konten template</p>
                    <p class="text-sm mt-1">
                        Gunakan tombol di sidebar untuk mulai membuat template
                        baru
                    </p>
                </div>
            </v-container>
        </template>
    </adminDashboardLayout>
</template>

<style scoped>
/* Transisi halus saat hover card */
.hover\:shadow-lg:hover {
    box-shadow:
        0 10px 15px -3px rgba(0, 0, 0, 0.1),
        0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
}
</style>
