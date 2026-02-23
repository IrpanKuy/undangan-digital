<script setup>
import { ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import adminDashboardLayout from "../../../layouts/adminDashboardLayout.vue";

const activeTab = ref("setting");

const form = useForm({
    reservation_form: true,
    komentar_undangan: true,
    jumlah_kehadiran: true,
    music_url: "",
});

const submit = () => {
    // This would typically save defaults or be part of a larger store process
    // For now, we'll just show a success message as this is a "Template" setting
    Swal.fire({
        icon: "success",
        title: "Pengaturan Disimpan",
        text: "Pengaturan awal untuk template berhasil diatur.",
        timer: 2000,
        showConfirmButton: false,
        toast: true,
        position: "top-end",
    });
};
</script>

<template>
    <adminDashboardLayout>
        <template #headerTitle>
            <Icon icon="mdi:cog-outline" width="22" color="#004D31" />
            Pengaturan Template
        </template>

        <template #content>
            <v-container fluid class="max-w-4xl">
                <!-- Tabs Navigation -->
                <v-tabs
                    v-model="activeTab"
                    color="primary"
                    class="mb-6 border-b"
                >
                    <v-tab
                        value="content"
                        @click="
                            router.get(
                                route(
                                    'admin.template-content-undangan.create-content',
                                ),
                            )
                        "
                    >
                        <v-icon start icon="mdi-book-open-variant" />
                        Konten Undangan
                    </v-tab>
                    <v-tab value="setting">
                        <v-icon start icon="mdi-cog" />
                        Pengaturan
                    </v-tab>
                </v-tabs>

                <form @submit.prevent="submit">
                    <v-card
                        variant="outlined"
                        class="rounded-xl overflow-hidden border-gray-200"
                    >
                        <div
                            class="bg-gray-50 px-6 py-4 border-b border-gray-200"
                        >
                            <h2
                                class="text-lg font-bold text-gray-800 flex items-center gap-2"
                            >
                                <v-icon
                                    icon="mdi-tune-variant"
                                    color="primary"
                                    size="24"
                                />
                                Pengaturan Fitur Undangan
                            </h2>
                        </div>
                        <v-card-text class="p-6">
                            <v-list lines="two" class="bg-transparent">
                                <v-list-item class="px-0">
                                    <template #prepend>
                                        <v-icon
                                            icon="mdi-form-select"
                                            color="primary"
                                            class="mr-4"
                                        />
                                    </template>
                                    <v-list-item-title class="font-bold"
                                        >Form Reservasi
                                        (RSVP)</v-list-item-title
                                    >
                                    <v-list-item-subtitle
                                        >Aktifkan agar tamu dapat mengirimkan
                                        konfirmasi
                                        kehadiran</v-list-item-subtitle
                                    >
                                    <template #append>
                                        <v-switch
                                            v-model="form.reservation_form"
                                            color="primary"
                                            hide-details
                                        />
                                    </template>
                                </v-list-item>

                                <v-divider class="my-2" />

                                <v-list-item class="px-0">
                                    <template #prepend>
                                        <v-icon
                                            icon="mdi-comment-text-outline"
                                            color="primary"
                                            class="mr-4"
                                        />
                                    </template>
                                    <v-list-item-title class="font-bold"
                                        >Komentar & Ucapan</v-list-item-title
                                    >
                                    <v-list-item-subtitle
                                        >Aktifkan fitur buku tamu digital
                                        (ucapan dan doa)</v-list-item-subtitle
                                    >
                                    <template #append>
                                        <v-switch
                                            v-model="form.komentar_undangan"
                                            color="primary"
                                            hide-details
                                        />
                                    </template>
                                </v-list-item>

                                <v-divider class="my-2" />

                                <v-list-item class="px-0">
                                    <template #prepend>
                                        <v-icon
                                            icon="mdi-account-group-outline"
                                            color="primary"
                                            class="mr-4"
                                        />
                                    </template>
                                    <v-list-item-title class="font-bold"
                                        >Jumlah Kehadiran</v-list-item-title
                                    >
                                    <v-list-item-subtitle
                                        >Tampilkan total tamu yang sudah
                                        melakukan
                                        konfirmasi</v-list-item-subtitle
                                    >
                                    <template #append>
                                        <v-switch
                                            v-model="form.jumlah_kehadiran"
                                            color="primary"
                                            hide-details
                                        />
                                    </template>
                                </v-list-item>

                                <v-divider class="my-4" />

                                <v-text-field
                                    v-model="form.music_url"
                                    label="Background Music URL (Opsional)"
                                    placeholder="https://example.com/music.mp3"
                                    variant="outlined"
                                    prepend-inner-icon="mdi-music-note"
                                    hint="Masukkan URL file audio atau link YouTube"
                                    persistent-hint
                                    class="mt-4"
                                />
                            </v-list>
                        </v-card-text>
                    </v-card>

                    <div class="flex justify-end gap-3 mt-8">
                        <v-btn
                            variant="outlined"
                            color="gray-500"
                            @click="
                                router.get(
                                    route(
                                        'admin.template-content-undangan.index',
                                    ),
                                )
                            "
                            >Batal</v-btn
                        >
                        <v-btn
                            type="submit"
                            color="primary"
                            size="large"
                            :loading="form.processing"
                            >Simpan Pengaturan</v-btn
                        >
                    </div>
                </form>
            </v-container>
        </template>
    </adminDashboardLayout>
</template>
