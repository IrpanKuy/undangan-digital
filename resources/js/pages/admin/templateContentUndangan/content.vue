<script setup>
import { ref, onMounted } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import adminDashboardLayout from "../../layouts/adminDashboardLayout.vue";

// Import Sections
import InformasiDasar from "./Section/informasiDasar.vue";
import DataMempelai from "./Section/dataMempelai.vue";
import DetailAkad from "./Section/detailAkad.vue";
import AcaraTambahan from "./Section/acaraTambahan.vue";
import GalleryFoto from "./Section/galleryFoto.vue";
import KisahCinta from "./Section/kisahCinta.vue";
import HadiahAmplop from "./Section/hadiahAmplop.vue";

const props = defineProps({
    template: {
        type: Object,
        default: null,
    },
});

const activeTab = ref("content");

const form = useForm({
    id: null,
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
    galleries: [
        {
            id: null,
            file: null,
            image_path: null,
        },
    ],
    remove_galleries: [], // To track deleted gallery images in edit mode

    // Kisah Cinta
    kisah_cintas: [
        {
            tanggal: "",
            peristiwa: "",
            foto: null,
        },
    ],
});

onMounted(() => {
    if (props.template) {
        form.id = props.template.id;
        form.judul = props.template.judul;
        form.url = props.template.url;
        form.salam_pembuka = props.template.salam_pembuka;
        form.text_pembuka = props.template.text_pembuka;
        form.video_youtube_url = props.template.video_youtube_url;

        if (props.template.data_mempelai) {
            const m = props.template.data_mempelai;
            form.nama_panggilan_pria = m.nama_panggilan_pria;
            form.nama_lengkap_pria = m.nama_lengkap_pria;
            form.keterangan_keluarga_pria = m.keterangan_keluarga_pria;
            form.nama_panggilan_wanita = m.nama_panggilan_wanita;
            form.nama_lengkap_wanita = m.nama_lengkap_wanita;
            form.keterangan_keluarga_wanita = m.keterangan_keluarga_wanita;
            form.text_penutup = m.text_penutup;
        }

        if (props.template.template_undangan_pernikahan) {
            const p = props.template.template_undangan_pernikahan;
            form.tanggal_mulai_akad = p.tanggal_mulai_akad;
            form.waktu_mulai_akad = p.waktu_mulai_akad;
            form.waktu_selesai_akad = p.waktu_selesai_akad;
            form.detail_lokasi_akad_nikah = p.detail_lokasi_akad_nikah;
            form.doa_pengantinn_pria = p.doa_pengantinn_pria;
            form.doa_pengantin_wanita = p.doa_pengantin_wanita;
            form.no_rek_amplop = p.no_rek_amplop;
            form.lokasi_pengiriman_kado = p.lokasi_pengiriman_kado;

            // Handle Geography point if needed, or simple lat/lng from backend
            if (p.lokasi_akad_nikah_decoded) {
                form.lokasi_akad_nikah = p.lokasi_akad_nikah_decoded;
            }
        }

        if (props.template.acaras && props.template.acaras.length > 0) {
            form.acaras = props.template.acaras.map((a) => ({
                id: a.id,
                nama_acara: a.nama_acara,
                waktu_acara: a.waktu_acara,
                detail_lokasi_acara: a.detail_lokasi_acara,
                lokasi_acara: a.lokasi_acara_decoded || {
                    lat: -6.2088,
                    lng: 106.8456,
                },
            }));
        }

        if (
            props.template.gallery_undangans &&
            props.template.gallery_undangans.length > 0
        ) {
            form.galleries = props.template.gallery_undangans.map((g) => ({
                id: g.id,
                file: null,
                image_path: g.image_path,
            }));
        }

        if (
            props.template.kisah_cintas &&
            props.template.kisah_cintas.length > 0
        ) {
            form.kisah_cintas = props.template.kisah_cintas.map((k) => ({
                id: k.id,
                tanggal: k.tanggal,
                peristiwa: k.peristiwa,
                foto: null, // Reset for new upload
                foto_path: k.foto_kisah_cinta_path, // To show existing preview
            }));
        }
    }
});

const submit = () => {
    // If editing, use update (via POST with _method PUT if needed by Laravel, or just POST to store which handles update)
    // Actually, our consolidated store() handles ID, so we can always POST
    form.post(route("admin.template-content-undangan.store"), {
        onSuccess: () => {
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: form.id
                    ? "Konten template berhasil diperbarui."
                    : "Konten template berhasil disimpan.",
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
                :icon="
                    form.id ? 'mdi-pencil-outline' : 'mdi-pencil-plus-outline'
                "
                class="mr-2"
                color="primary"
            />
            {{ form.id ? "Edit" : "Buat" }} Konten Template
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
                            form.id
                                ? router.get(
                                      route(
                                          'admin.template-content-undangan.edit-setting',
                                          form.id,
                                      ),
                                  )
                                : router.get(
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
                            {{ form.id ? "Perbarui" : "Simpan" }} Konten
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
