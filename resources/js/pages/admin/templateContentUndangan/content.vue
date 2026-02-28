<script setup>
import { ref, onMounted } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import adminDashboardLayout from "../../layouts/adminDashboardLayout.vue";
import { Icon } from "@iconify/vue";

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

            if (p.lokasi_akad_nikah) {
                form.lokasi_akad_nikah = p.lokasi_akad_nikah;
            }
        }

        if (props.template.acaras && props.template.acaras.length > 0) {
            form.acaras = props.template.acaras.map((a) => ({
                id: a.id,
                nama_acara: a.nama_acara,
                waktu_acara: a.waktu_acara,
                detail_lokasi_acara: a.detail_lokasi_acara,
                lokasi_acara: a.lokasi_acara || {
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
                foto: null,
                foto_path: k.foto_kisah_cinta_path,
            }));
        }
    }
});

const submit = () => {
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
            console.error("Validation Errors:", errors);
            Swal.fire({
                icon: "error",
                title: "Gagal!",
                text: "Silakan periksa kembali inputan Anda. Beberapa bidang wajib diisi atau formatnya salah.",
                timer: 4000,
            });

            // Scroll to the first error (Mendukung Vuetify lama & Tailwind baru)
            setTimeout(() => {
                const firstError = document.querySelector(
                    ".v-input--error, .border-red-500, .text-red-600",
                );
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: "smooth",
                        block: "center",
                    });
                }
            }, 100);
        },
    });
};
</script>

<template>
    <adminDashboardLayout>
        <!-- Slot Header -->
        <template #headerTitle>
            <div class="flex items-center gap-2">
                <Icon
                    :icon="
                        form.id
                            ? 'mdi:pencil-outline'
                            : 'mdi:pencil-plus-outline'
                    "
                    width="22"
                    class="text-[#004D31]"
                />
                <span class="font-semibold text-gray-800">
                    {{ form.id ? "Edit" : "Buat" }} Konten Template
                </span>
            </div>
        </template>

        <!-- Slot Content -->
        <template #content>
            <div class="w-full max-w-7xl pb-10">
                <!-- Navigation Tabs (Flat UI) -->
                <div class="flex border-b border-gray-300 mb-6">
                    <button
                        type="button"
                        @click="activeTab = 'content'"
                        :class="[
                            'cursor-pointer flex items-center gap-2 px-6 py-3 font-medium text-sm transition-colors border-b-2 outline-none',
                            activeTab === 'content'
                                ? 'border-[#004D31] text-[#004D31]'
                                : 'border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300',
                        ]"
                    >
                        <Icon icon="mdi:book-open-variant" width="18" />
                        Konten Undangan
                    </button>
                    <button
                        type="button"
                        @click="
                            form.id
                                ? router.get(
                                      route(
                                          'admin.template-content-undangan.edit-setting',
                                          form.id,
                                      ),
                                  )
                                : null
                        "
                        :disabled="!form.id"
                        :class="[
                            'flex items-center gap-2 px-6 py-3 font-medium text-sm transition-colors border-b-2 outline-none',
                            activeTab === 'setting'
                                ? 'border-[#004D31] text-[#004D31]'
                                : 'border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300',
                            !form.id
                                ? 'opacity-50 cursor-not-allowed'
                                : 'cursor-pointer',
                        ]"
                    >
                        <Icon icon="mdi:cog" width="18" />
                        Pengaturan
                    </button>
                </div>

                <!-- Error Alert Box -->
                <div
                    v-if="Object.keys(form.errors).length > 0"
                    class="mb-6 bg-red-50 border border-red-200 rounded-sm p-4 flex items-start gap-3 shadow-sm"
                >
                    <Icon
                        icon="mdi:alert-circle-outline"
                        class="text-red-600 mt-0.5"
                        width="22"
                    />
                    <div>
                        <h4
                            class="text-sm font-bold text-red-800 uppercase tracking-tight"
                        >
                            Ditemukan Kesalahan Validasi
                        </h4>
                        <p class="text-xs font-medium text-red-700 mt-1">
                            Ada {{ Object.keys(form.errors).length }} kolom yang
                            perlu diperbaiki. Pastikan semua file yang wajib
                            diunggah sudah terpilih.
                        </p>
                    </div>
                </div>

                <!-- Main Form Wrapper -->
                <form @submit.prevent="submit" class="space-y-8">
                    <!-- Section 1: Informasi Dasar -->
                    <InformasiDasar v-model="form" />

                    <!-- Section 2: Data Mempelai -->
                    <DataMempelai v-model="form" />

                    <!-- Section 3: Detail Akad Nikah -->
                    <DetailAkad v-model="form" />

                    <!-- Section 4: Acara Tambahan -->
                    <AcaraTambahan v-model="form" />

                    <!-- Section 5: Gallery & Kisah Cinta -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="h-full bg-transparent">
                            <GalleryFoto v-model="form" />
                        </div>
                        <div class="h-full bg-transparent">
                            <KisahCinta v-model="form" />
                        </div>
                    </div>

                    <!-- Section 6: Hadiah & Amplop Digital -->
                    <HadiahAmplop v-model="form" />

                    <!-- Form Actions / Buttons -->
                    <div
                        class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-200"
                    >
                        <button
                            type="button"
                            @click="
                                router.get(
                                    route(
                                        'admin.template-content-undangan.index',
                                    ),
                                )
                            "
                            class="cursor-pointer px-6 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-400 rounded-sm hover:bg-gray-100 transition-colors uppercase tracking-tighter"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="cursor-pointer inline-flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-[#004D31] border border-[#004D31] rounded-sm hover:bg-[#003824] transition-colors uppercase tracking-tighter disabled:opacity-50"
                        >
                            <Icon
                                v-if="form.processing"
                                icon="mdi:loading"
                                class="animate-spin"
                                width="18"
                            />
                            <Icon
                                v-else
                                icon="mdi:content-save-outline"
                                width="18"
                            />
                            {{ form.id ? "Perbarui" : "Simpan" }} Konten
                        </button>
                    </div>
                </form>
            </div>
        </template>
    </adminDashboardLayout>
</template>
