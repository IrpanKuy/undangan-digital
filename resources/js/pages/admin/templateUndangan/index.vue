<script setup>
import { ref, computed } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import adminDashboardLayout from "../../layouts/adminDashboardLayout.vue";
import { Icon } from "@iconify/vue";
import Swal from "sweetalert2";
import { VFileUpload } from "vuetify/labs/VFileUpload";

const props = defineProps({
    templates: Array,
    categories: Array,
    availableUndangans: Array,
});

const search = ref("");
const dialog = ref(false);
const isEditing = ref(false);

const headers = [
    { title: "Thumbnail", key: "thumbnail_template", sortable: false },
    { title: "Judul Undangan", key: "judul_undangan" },
    { title: "Kategori", key: "kategori_undangan.name" },
    { title: "Premium", key: "template_premium" },
    { title: "Status", key: "is_active" },
    { title: "Penggunaan", key: "usage" },
    { title: "Aksi", key: "actions", sortable: false, align: "end" },
];

const form = useForm({
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

const openDialog = (item = null) => {
    if (item) {
        isEditing.value = true;
        form.id = item.id;
        form.undangan_id = item.undangan_id;
        form.judul_undangan = item.judul_undangan;
        form.kategori_undangan_id = item.kategori_undangan_id;
        form.file_name = item.file_name;
        form.template_premium = item.template_premium;
        form.is_active = item.is_active;
        form.music_url = item.music_url;
        form.thumbnail_template = null;
    } else {
        isEditing.value = false;
        form.reset();
        form.id = null;
    }
    dialog.value = true;
};

const closeDialog = () => {
    dialog.value = false;
    form.reset();
};

const submit = () => {
    form.post(route("admin.template-undangan.store"), {
        onSuccess: () => {
            closeDialog();
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: isEditing.value
                    ? "Template berhasil diperbarui."
                    : "Template berhasil ditambahkan.",
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: "top-end",
            });
        },
    });
};

const deleteItem = (id) => {
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("admin.template-undangan.destroy", id), {
                onSuccess: () => {
                    Swal.fire({
                        icon: "success",
                        title: "Dihapus!",
                        text: "Template berhasil dihapus.",
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: "top-end",
                    });
                },
            });
        }
    });
};
</script>

<template>
    <adminDashboardLayout>
        <template #headerTitle>
            <div class="flex items-center gap-2">
                <Icon
                    icon="mdi:certificate-outline"
                    width="24"
                    class="text-primary"
                />
                <span>Manajemen Template Undangan</span>
            </div>
        </template>

        <template #content>
            <v-container fluid>
                <v-card
                    variant="outlined"
                    class="rounded-xl overflow-hidden border-gray-200"
                >
                    <v-card-title
                        class="bg-gray-50 px-6 py-4 border-b border-gray-200"
                    >
                        <v-row align="center">
                            <v-col cols="12" md="4">
                                <v-text-field
                                    v-model="search"
                                    prepend-inner-icon="mdi-magnify"
                                    label="Cari Template..."
                                    variant="outlined"
                                    density="compact"
                                    hide-details
                                    class="bg-white rounded-lg"
                                ></v-text-field>
                            </v-col>
                            <v-spacer></v-spacer>
                            <v-col cols="auto">
                                <v-btn
                                    color="primary"
                                    prepend-icon="mdi-plus"
                                    class="rounded-lg text-none"
                                    @click="openDialog()"
                                >
                                    Tambah Template
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-card-title>

                    <v-data-table
                        :headers="headers"
                        :items="templates"
                        :search="search"
                        hover
                        class="border-0"
                    >
                        <template #[`item.thumbnail_template`]="{ item }">
                            <v-avatar size="50" rounded="lg" class="border">
                                <v-img
                                    v-if="item.thumbnail_template"
                                    :src="`/storage/${item.thumbnail_template}`"
                                    cover
                                />
                                <Icon
                                    v-else
                                    icon="mdi:image-off-outline"
                                    width="24"
                                    class="text-gray-400"
                                />
                            </v-avatar>
                        </template>

                        <template #[`item.template_premium`]="{ item }">
                            <v-chip
                                :color="
                                    item.template_premium
                                        ? 'amber'
                                        : 'blue-grey'
                                "
                                size="small"
                                variant="tonal"
                                class="font-bold"
                            >
                                <Icon
                                    :icon="
                                        item.template_premium
                                            ? 'mdi:crown'
                                            : 'mdi-leaf'
                                    "
                                    class="mr-1"
                                />
                                {{
                                    item.template_premium ? "Premium" : "Gratis"
                                }}
                            </v-chip>
                        </template>

                        <template #[`item.is_active`]="{ item }">
                            <v-chip
                                :color="item.is_active ? 'success' : 'error'"
                                size="small"
                                variant="flat"
                            >
                                {{ item.is_active ? "Aktif" : "Nonaktif" }}
                            </v-chip>
                        </template>

                        <template #[`item.usage`]="{ item }">
                            <div class="flex items-center gap-1">
                                <Icon
                                    icon="mdi:account-group-outline"
                                    class="text-gray-500"
                                />
                                <span>{{ item.usage }} kali</span>
                            </div>
                        </template>

                        <template #[`item.actions`]="{ item }">
                            <div class="flex items-center justify-end gap-1">
                                <v-btn
                                    icon
                                    variant="text"
                                    color="primary"
                                    size="small"
                                    @click="openDialog(item)"
                                    v-tooltip="'Edit Template'"
                                >
                                    <Icon
                                        icon="mdi:pencil-outline"
                                        width="20"
                                    />
                                </v-btn>
                                <v-btn
                                    icon
                                    variant="text"
                                    color="error"
                                    size="small"
                                    @click="deleteItem(item.id)"
                                    v-tooltip="'Hapus Template'"
                                >
                                    <Icon
                                        icon="mdi:trash-can-outline"
                                        width="20"
                                    />
                                </v-btn>
                            </div>
                        </template>
                    </v-data-table>
                </v-card>
            </v-container>

            <!-- Upsert Dialog -->
            <v-dialog v-model="dialog" max-width="700px" persistent>
                <v-card class="rounded-xl">
                    <v-card-title
                        class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex! justify-between items-center"
                    >
                        <span class="text-h6 font-bold flex items-center gap-2">
                            <Icon
                                :icon="
                                    isEditing
                                        ? 'mdi:pencil-circle'
                                        : 'mdi-plus-circle'
                                "
                                width="24"
                                :class="
                                    isEditing ? 'text-primary' : 'text-success'
                                "
                            />
                            {{
                                isEditing
                                    ? "Edit Template"
                                    : "Tambah Template Baru"
                            }}
                        </span>
                        <v-btn
                            icon
                            variant="text"
                            size="small"
                            @click="closeDialog"
                        >
                            <Icon icon="mdi:close" width="20" />
                        </v-btn>
                    </v-card-title>

                    <v-card-text class="pa-6">
                        <v-form @submit.prevent="submit">
                            <v-row>
                                <v-col cols="12">
                                    <v-select
                                        v-model="form.undangan_id"
                                        :items="availableUndangans"
                                        item-title="judul"
                                        item-value="id"
                                        label="Pilih Template Content (Undangan)"
                                        variant="outlined"
                                        :error-messages="
                                            form.errors.undangan_id
                                        "
                                        prepend-inner-icon="mdi-database-outline"
                                        hint="Pilih data konten (undangans) yang akan menjadi dasar template ini"
                                        persistent-hint
                                    ></v-select>
                                </v-col>

                                <v-col cols="12">
                                    <v-text-field
                                        v-model="form.judul_undangan"
                                        label="Judul Undangan"
                                        variant="outlined"
                                        :error-messages="
                                            form.errors.judul_undangan
                                        "
                                        prepend-inner-icon="mdi-format-title"
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12" md="6">
                                    <v-select
                                        v-model="form.kategori_undangan_id"
                                        :items="categories"
                                        item-title="name"
                                        item-value="id"
                                        label="Kategori"
                                        variant="outlined"
                                        :error-messages="
                                            form.errors.kategori_undangan_id
                                        "
                                        prepend-inner-icon="mdi-shape-outline"
                                    ></v-select>
                                </v-col>

                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="form.file_name"
                                        label="Nama File Template"
                                        variant="outlined"
                                        :error-messages="form.errors.file_name"
                                        prepend-inner-icon="mdi-file-code-outline"
                                        hint="Contoh: wedding-floral-gold"
                                        persistent-hint
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12">
                                    <v-file-upload
                                        v-model="form.thumbnail_template"
                                        title="Upload Thumbnail Template"
                                        accept="image/*"
                                        variant="outlined"
                                        density="compact"
                                        :error-messages="
                                            form.errors.thumbnail_template
                                        "
                                    />
                                    <div
                                        v-if="
                                            isEditing &&
                                            !form.thumbnail_template
                                        "
                                        class="text-caption text-gray-400 mt-1 italic"
                                    >
                                        Biarkan kosong jika tidak ingin
                                        mengganti thumbnail.
                                    </div>
                                </v-col>

                                <v-col cols="12">
                                    <v-text-field
                                        v-model="form.music_url"
                                        label="URL Musik Default (Opsional)"
                                        variant="outlined"
                                        :error-messages="form.errors.music_url"
                                        prepend-inner-icon="mdi-music"
                                        placeholder="https://..."
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="6">
                                    <v-switch
                                        v-model="form.template_premium"
                                        label="Template Premium"
                                        color="amber"
                                        hide-details
                                        inset
                                    >
                                        <template #prepend>
                                            <Icon
                                                icon="mdi:crown"
                                                :class="
                                                    form.template_premium
                                                        ? 'text-amber'
                                                        : 'text-gray-300'
                                                "
                                                width="20"
                                                class="mr-2"
                                            />
                                        </template>
                                    </v-switch>
                                </v-col>

                                <v-col cols="6">
                                    <v-switch
                                        v-model="form.is_active"
                                        label="Status Aktif"
                                        color="success"
                                        hide-details
                                        inset
                                    >
                                        <template #prepend>
                                            <Icon
                                                icon="mdi:power"
                                                :class="
                                                    form.is_active
                                                        ? 'text-success'
                                                        : 'text-error'
                                                "
                                                width="20"
                                                class="mr-2"
                                            />
                                        </template>
                                    </v-switch>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-card-text>

                    <v-divider></v-divider>

                    <v-card-actions class="pa-6">
                        <v-spacer></v-spacer>
                        <v-btn
                            variant="text"
                            class="text-none rounded-lg mr-2"
                            @click="closeDialog"
                        >
                            Batal
                        </v-btn>
                        <v-btn
                            color="primary"
                            class="text-none rounded-lg px-6"
                            :loading="form.processing"
                            @click="submit"
                        >
                            {{
                                isEditing
                                    ? "Simpan Perubahan"
                                    : "Simpan Template"
                            }}
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </template>
    </adminDashboardLayout>
</template>

<style scoped>
.v-data-table {
    border-radius: 0 !important;
}
</style>
