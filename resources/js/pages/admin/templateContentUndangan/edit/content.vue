<script setup>
import { ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import adminDashboardLayout from "../../../layouts/adminDashboardLayout.vue";

// Import Sections
import InformasiDasar from "./editSection/informasiDasar.vue";
import DataMempelai from "./editSection/dataMempelai.vue";
import DetailAkad from "./editSection/detailAkad.vue";
import AcaraTambahan from "./editSection/acaraTambahan.vue";
import GalleryFoto from "./editSection/galleryFoto.vue";
import KisahCinta from "./editSection/kisahCinta.vue";
import HadiahAmplop from "./editSection/hadiahAmplop.vue";

const props = defineProps({
    template: Object,
});

const activeTab = ref("content");

const parseGeo = (geo) => {
    if (!geo) return { lat: -6.2088, lng: 106.8456 };
    if (typeof geo === "string" && geo.startsWith("POINT")) {
        const coords = geo.match(/\((.*)\)/)[1].split(" ");
        return { lat: parseFloat(coords[1]), lng: parseFloat(coords[0]) };
    }
    if (geo.coordinates) {
        return { lat: geo.coordinates[1], lng: geo.coordinates[0] };
    }
    return { lat: -6.2088, lng: 106.8456 };
};

const form = useForm({
    _method: "PUT",
    judul: props.template.judul || "",
    url: props.template.url || "",
    thumbnail: null,
    salam_pembuka: props.template.salam_pembuka || "",
    text_pembuka: props.template.text_pembuka || "",
    video_youtube_url: props.template.video_youtube_url || "",

    // Data Mempelai
    nama_panggilan_pria:
        props.template.data_mempelai?.nama_panggilan_pria || "",
    nama_lengkap_pria: props.template.data_mempelai?.nama_lengkap_pria || "",
    keterangan_keluarga_pria:
        props.template.data_mempelai?.keterangan_keluarga_pria || "",
    foto_pria: null,
    nama_panggilan_wanita:
        props.template.data_mempelai?.nama_panggilan_wanita || "",
    nama_lengkap_wanita:
        props.template.data_mempelai?.nama_lengkap_wanita || "",
    keterangan_keluarga_wanita:
        props.template.data_mempelai?.keterangan_keluarga_wanita || "",
    foto_wanita: null,
    text_penutup: props.template.data_mempelai?.text_penutup || "",

    // Template Undangan Pernikahan
    tanggal_mulai_akad:
        props.template.template_undangan_pernikahan?.tanggal_mulai_akad || "",
    waktu_mulai_akad:
        props.template.template_undangan_pernikahan?.waktu_mulai_akad || "",
    waktu_selesai_akad:
        props.template.template_undangan_pernikahan?.waktu_selesai_akad || "",
    detail_lokasi_akad_nikah:
        props.template.template_undangan_pernikahan?.detail_lokasi_akad_nikah ||
        "",
    lokasi_akad_nikah: parseGeo(
        props.template.template_undangan_pernikahan?.lokasi_akad_nikah,
    ),
    doa_pengantinn_pria:
        props.template.template_undangan_pernikahan?.doa_pengantinn_pria || "",
    doa_pengantin_wanita:
        props.template.template_undangan_pernikahan?.doa_pengantin_wanita || "",
    no_rek_amplop:
        props.template.template_undangan_pernikahan?.no_rek_amplop || "",
    lokasi_pengiriman_kado:
        props.template.template_undangan_pernikahan?.lokasi_pengiriman_kado ||
        "",

    // Acara
    acaras:
        props.template.acaras?.length > 0
            ? props.template.acaras.map((a) => ({
                  id: a.id,
                  nama_acara: a.nama_acara,
                  waktu_acara: a.waktu_acara,
                  detail_lokasi_acara: a.detail_lokasi_acara,
                  lokasi_acara: parseGeo(a.lokasi_acara),
              }))
            : [
                  {
                      nama_acara: "Resepsi",
                      waktu_acara: "",
                      detail_lokasi_acara: "",
                      lokasi_acara: { lat: -6.2088, lng: 106.8456 },
                  },
              ],

    // Kisah Cinta
    kisah_cintas:
        props.template.kisah_cintas?.length > 0
            ? props.template.kisah_cintas.map((k) => ({
                  id: k.id,
                  tanggal: k.tanggal,
                  peristiwa: k.peristiwa,
                  foto: null,
                  existing_foto: k.foto_kisah_cinta_path,
              }))
            : [{ tanggal: "", peristiwa: "", foto: null }],

    // Gallery
    galleries: [],
    existing_galleries: props.template.gallery_undangans || [],
    remove_galleries: [],
});

const submit = () => {
    form.post(
        route("admin.template-content-undangan.update", props.template.id),
        {
            onSuccess: () => {
                Swal.fire({
                    icon: "success",
                    title: "Berhasil!",
                    text: "Konten template berhasil diperbarui.",
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: "top-end",
                });
            },
            onError: () => {
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
        },
    );
};
</script>

<template>
    <adminDashboardLayout>
        <template #headerTitle>
            <v-icon icon="mdi-pencil" class="mr-2" color="primary" />
            Edit Konten Template: {{ template.judul }}
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
                                    'admin.template-content-undangan.edit-setting',
                                    template.id,
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
                    <InformasiDasar v-model="form" :template="template" />

                    <!-- Section 2: Data Mempelai -->
                    <DataMempelai v-model="form" :template="template" />

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
                            Update Konten
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
