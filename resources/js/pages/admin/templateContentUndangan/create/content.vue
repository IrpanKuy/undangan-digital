<script setup>
import { ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import adminDashboardLayout from "../../../layouts/adminDashboardLayout.vue";

// Import Sections
import InformasiDasar from "./createSection/informasiDasar.vue";
import DataMempelai from "./createSection/dataMempelai.vue";
import DetailAkad from "./createSection/detailAkad.vue";
import AcaraTambahan from "./createSection/acaraTambahan.vue";
import GalleryFoto from "./createSection/galleryFoto.vue";
import KisahCinta from "./createSection/kisahCinta.vue";
import HadiahAmplop from "./createSection/hadiahAmplop.vue";

const activeTab = ref("content");

const form = useForm({
    // Undangan
    judul: "",
    url: "",
    thumbnail: null,
    salam_pembuka: "",
    text_pembuka: "",
    video_youtube_url: "",

    // Data Mempelai
    nama_panggilan_pria: "",
    nama_lengkap_pria: "",
    keterangan_keluarga_pria: "",
    foto_pria: null,
    nama_panggilan_wanita: "",
    nama_lengkap_wanita: "",
    keterangan_keluarga_wanita: "",
    foto_wanita: null,
    text_penutup: "",

    // Template Undangan Pernikahan
    tanggal_mulai_akad: "",
    waktu_mulai_akad: "",
    waktu_selesai_akad: "",
    detail_lokasi_akad_nikah: "",
    lokasi_akad_nikah: { lat: -6.2088, lng: 106.8456 },
    doa_pengantinn_pria: "",
    doa_pengantin_wanita: "",
    no_rek_amplop: "",
    lokasi_pengiriman_kado: "",

    // Acara
    acaras: [
        {
            nama_acara: "Resepsi",
            waktu_acara: "",
            detail_lokasi_acara: "",
            lokasi_acara: { lat: -6.2088, lng: 106.8456 },
        },
    ],

    // Gallery
    galleries: [],

    // Kisah Cinta
    kisah_cintas: [
        {
            tanggal: "",
            peristiwa: "",
            foto: null,
        },
    ],
});

const submit = () => {
    form.post(route("admin.template-content-undangan.store"), {
        onSuccess: () => {
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: "Konten template berhasil disimpan.",
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: "top-end",
            });
        },
        onError: (errors) => {
            console.log(errors);
            Swal.fire({
                icon: "error",
                title: "Gagal!",
                text: "Silakan periksa kembali inputan Anda.",
                timer: 3000,
            });
            const errorElement = document.querySelector(".v-input--error");
            if (errorElement) {
                errorElement.scrollIntoView({
                    behavior: "smooth",
                    block: "center",
                });
            }
        },
    });
};
</script>

<template>
    <adminDashboardLayout>
        <template #headerTitle>
            <v-icon
                icon="mdi-pencil-plus-outline"
                class="mr-2"
                color="primary"
            />
            Buat Konten Template
        </template>

        <template #content>
            <v-container fluid class="max-w-7xl">
                <!-- Tabs Navigation -->
                <v-tabs
                    v-model="activeTab"
                    color="primary"
                    class="mb-6 border-b"
                >
                    <v-tab value="content">
                        <v-icon start icon="mdi-book-open-variant" />
                        Konten Undangan
                    </v-tab>
                    <v-tab
                        value="setting"
                        @click="
                            router.get(
                                route(
                                    'admin.template-content-undangan.create-setting',
                                ),
                            )
                        "
                    >
                        <v-icon start icon="mdi-cog" />
                        Pengaturan
                    </v-tab>
                </v-tabs>

                <form @submit.prevent="submit">
                    <!-- Section 1: Informasi Dasar -->
                    <InformasiDasar v-model="form" />

                    <!-- Section 2: Data Mempelai -->
                    <DataMempelai v-model="form" />

                    <!-- Section 3: Detail Akad Nikah -->
                    <DetailAkad v-model="form" />

                    <!-- Section 4: Acara Tambahan -->
                    <AcaraTambahan v-model="form" />

                    <!-- Section 5: Gallery & Kisah Cinta -->
                    <v-row>
                        <v-col cols="12" md="6">
                            <v-card
                                variant="flat"
                                class="bg-transparent h-full"
                            >
                                <GalleryFoto v-model="form" />
                            </v-card>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-card
                                variant="flat"
                                class="bg-transparent h-full"
                            >
                                <KisahCinta v-model="form" />
                            </v-card>
                        </v-col>
                    </v-row>

                    <!-- Section 6: Hadiah & Amplop Digital -->
                    <HadiahAmplop v-model="form" />

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 mt-8 pb-10">
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
                        >
                            Batal
                        </v-btn>
                        <v-btn
                            type="submit"
                            color="primary"
                            size="large"
                            :loading="form.processing"
                        >
                            Simpan Konten
                        </v-btn>
                    </div>
                </form>
            </v-container>
        </template>
    </adminDashboardLayout>
</template>

<style scoped>
.v-container {
    padding-bottom: 50px;
}
</style>
