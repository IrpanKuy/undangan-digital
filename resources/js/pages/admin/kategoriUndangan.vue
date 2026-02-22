<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import adminDashboardLayout from "../layouts/adminDashboardLayout.vue";

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

// ─── Options ─────────────────────────────────────────────────────────────────
const statusOptions = [
    { title: "Aktif", value: true },
    { title: "Tidak Aktif", value: false },
];

const headers = [
    { title: "No", key: "index", sortable: false, width: "80px" },
    { title: "Nama Kategori", key: "name", sortable: true },
    { title: "Status", key: "status", sortable: true, width: "150px" },
    {
        title: "Aksi",
        key: "actions",
        sortable: false,
        width: "140px",
        align: "center",
    },
];

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
        const deleteForm = useForm({});
        deleteForm.delete(route("admin.kategori-undangan.destroy", item.id), {
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
            <Icon icon="dashicons:category" width="22" color="#004D31" />
            Kategori Undangan
        </template>

        <!-- Slot: Content -->
        <template #content>
            <v-container fluid class="p-0">
                <!-- Page Header -->
                <v-row align="center" justify="space-between" class="mb-5">
                    <v-col cols="auto">
                        <p class="text-xl font-bold text-gray-700 mb-1">
                            Kategori Undangan
                        </p>
                        <p class="text-sm text-gray-500">
                            Kelola daftar kategori undangan digital
                        </p>
                    </v-col>
                    <v-col cols="auto">
                        <v-btn prepend-icon="mdi:plus" @click="openCreate">
                            Tambah Kategori
                        </v-btn>
                    </v-col>
                </v-row>

                <!-- Tabel Data -->
                <v-card elevation="2" rounded="lg">
                    <v-data-table
                        :headers="headers"
                        :items="kategori"
                        :items-per-page="10"
                        hover
                    >
                        <!-- No -->
                        <template #item.index="{ index }">
                            <span class="text-sm text-gray-500">{{
                                index + 1
                            }}</span>
                        </template>

                        <!-- Nama -->
                        <template #item.name="{ item }">
                            <span class="font-medium">{{ item.name }}</span>
                        </template>

                        <!-- Status -->
                        <template #item.status="{ item }">
                            <v-chip
                                :color="item.status ? 'success' : 'error'"
                                variant="tonal"
                                size="small"
                                density="comfortable"
                            >
                                <v-icon
                                    :icon="
                                        item.status
                                            ? 'mdi-check-circle'
                                            : 'mdi-close-circle'
                                    "
                                    start
                                    size="14"
                                />
                                {{ item.status ? "Aktif" : "Tidak Aktif" }}
                            </v-chip>
                        </template>

                        <!-- Aksi -->
                        <template #item.actions="{ item }">
                            <div class="flex items-center justify-center gap-2">
                                <v-btn
                                    color="primary"
                                    variant="tonal"
                                    size="small"
                                    icon
                                    @click="openEdit(item)"
                                >
                                    <v-icon
                                        icon="mdi-pencil-outline"
                                        size="18"
                                    />
                                    <v-tooltip activator="parent" location="top"
                                        >Edit</v-tooltip
                                    >
                                </v-btn>
                                <v-btn
                                    color="error"
                                    variant="tonal"
                                    size="small"
                                    icon
                                    :loading="loadingDeleteId === item.id"
                                    @click="confirmDelete(item)"
                                >
                                    <v-icon
                                        icon="mdi-trash-can-outline"
                                        size="18"
                                    />
                                    <v-tooltip activator="parent" location="top"
                                        >Hapus</v-tooltip
                                    >
                                </v-btn>
                            </div>
                        </template>

                        <!-- Empty State -->
                        <template #no-data>
                            <div
                                class="flex flex-col items-center py-12 text-gray-500"
                            >
                                <v-icon
                                    icon="mdi-tag-off-outline"
                                    size="56"
                                    class="mb-3 opacity-30"
                                />
                                <p class="text-base font-medium">
                                    Belum ada kategori
                                </p>
                                <p class="text-sm mt-1">
                                    Klik "Tambah Kategori" untuk memulai
                                </p>
                            </div>
                        </template>
                    </v-data-table>
                </v-card>
            </v-container>

            <!-- ═══════════════ DIALOG CREATE ═══════════════ -->
            <v-dialog v-model="dialogCreate" max-width="460" persistent>
                <v-card rounded="xl" elevation="10">
                    <!-- Header -->
                    <v-sheet
                        color="#004D31"
                        rounded="t-xl"
                        class="p-5! flex items-center gap-3"
                    >
                        <v-icon icon="mdi-tag-plus" color="white" size="24" />
                        <span class="text-white text-lg font-semibold">
                            Tambah Kategori Undangan
                        </span>
                    </v-sheet>

                    <v-card-text class="p-6">
                        <v-row dense>
                            <!-- Nama Kategori -->
                            <v-col cols="12">
                                <p
                                    class="text-sm font-medium text-gray-600 mb-1"
                                >
                                    Nama Kategori
                                    <span class="text-red-600">*</span>
                                </p>
                                <v-text-field
                                    v-model="formCreate.name"
                                    placeholder="Contoh: Pernikahan, Ulang Tahun, Aqiqah..."
                                    :error-messages="formCreate.errors.name"
                                    hide-details="auto"
                                    autofocus
                                />
                            </v-col>

                            <!-- Status -->
                            <v-col cols="12">
                                <p
                                    class="text-sm font-medium text-gray-600 mb-1"
                                >
                                    Status <span class="text-red-600">*</span>
                                </p>
                                <v-select
                                    v-model="formCreate.status"
                                    :items="statusOptions"
                                    item-title="title"
                                    item-value="value"
                                    :error-messages="formCreate.errors.status"
                                    hide-details="auto"
                                />
                            </v-col>
                        </v-row>
                    </v-card-text>

                    <v-divider />
                    <v-card-actions class="p-4 gap-2 justify-end">
                        <v-btn
                            variant="outlined"
                            color="grey-darken-2"
                            :disabled="formCreate.processing"
                            @click="dialogCreate = false"
                        >
                            Batal
                        </v-btn>
                        <v-btn
                            prepend-icon="mdi-content-save-outline"
                            :loading="formCreate.processing"
                            @click="submitCreate"
                        >
                            Simpan
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <!-- ═══════════════ DIALOG EDIT ═══════════════ -->
            <v-dialog v-model="dialogEdit" max-width="460" persistent>
                <v-card rounded="xl" elevation="10">
                    <!-- Header -->
                    <v-sheet
                        color="#004D31"
                        rounded="t-xl"
                        class="p-5 flex items-center gap-3"
                    >
                        <v-icon
                            icon="mdi-tag-edit-outline"
                            color="white"
                            size="24"
                        />
                        <span class="text-white text-lg font-semibold">
                            Edit Kategori Undangan
                        </span>
                    </v-sheet>

                    <v-card-text class="p-6">
                        <v-row dense>
                            <!-- Nama Kategori -->
                            <v-col cols="12">
                                <p
                                    class="text-sm font-medium text-gray-600 mb-1"
                                >
                                    Nama Kategori
                                    <span class="text-red-600">*</span>
                                </p>
                                <v-text-field
                                    v-model="formEdit.name"
                                    placeholder="Contoh: Pernikahan, Ulang Tahun, Aqiqah..."
                                    :error-messages="formEdit.errors.name"
                                    hide-details="auto"
                                />
                            </v-col>

                            <!-- Status -->
                            <v-col cols="12">
                                <p
                                    class="text-sm font-medium text-gray-600 mb-1"
                                >
                                    Status <span class="text-red-600">*</span>
                                </p>
                                <v-select
                                    v-model="formEdit.status"
                                    :items="statusOptions"
                                    item-title="title"
                                    item-value="value"
                                    :error-messages="formEdit.errors.status"
                                    hide-details="auto"
                                />
                            </v-col>
                        </v-row>
                    </v-card-text>

                    <v-divider />
                    <v-card-actions class="p-4 gap-2 justify-end">
                        <v-btn
                            variant="outlined"
                            color="grey-darken-2"
                            :disabled="formEdit.processing"
                            @click="dialogEdit = false"
                        >
                            Batal
                        </v-btn>
                        <v-btn
                            prepend-icon="mdi-content-save-edit-outline"
                            :loading="formEdit.processing"
                            @click="submitEdit"
                        >
                            Perbarui
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </template>
    </adminDashboardLayout>
</template>
