<script setup>
import { ref, onMounted } from "vue";
import { useForm, router, Link } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import userDashboardLayout from "../../../layouts/userDashboardLayout.vue";
import { Icon } from "@iconify/vue";

// Import Sections
import InformasiDasar from "./Section/informasiDasar.vue";
import DataMempelai from "./Section/dataMempelai.vue";
import AcaraTambahan from "./Section/acaraTambahan.vue";
import GalleryFoto from "./Section/galleryFoto.vue";
import KisahCinta from "./Section/kisahCinta.vue";
import HadiahAmplop from "./Section/hadiahAmplop.vue";
import DetailAcaraPernikahan from "./Section/detailAcaraPernikahan.vue";

const props = defineProps({
    template: {
        type: Object,
        default: null,
    },
    templateId: "",
});

const activeTab = ref("content");

const form = useForm({
    id: null,
    // Undangan
    template_id: props.templateId ?? null,
    judul: "",
    url: "",
    thumbnail: null,
    thumbnail_path: null,
    salam_pembuka: "Assalamu’alaikum Wr. Br.",
    text_pembuka:
        "Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk menghadiri hari bahagia kami",
    video_youtube_url: "",
    text_penutup:
        "Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir untuk memberikan doa restu kepada kedua pengantin",

    // Data Mempelai
    nama_panggilan_pria: "",
    nama_lengkap_pria: "",
    keterangan_keluarga_pria: "",
    doa_pengantin_pria: "Semoga istri saya sehat selalu dan taat beribadah",
    foto_pria: null,
    foto_pria_path: null,
    instagram_url_pria: "",
    tiktok_url_pria: "",
    x_url_pria: "",
    nama_panggilan_wanita: "",
    nama_lengkap_wanita: "",
    instagram_url_wanita: "",
    tiktok_url_wanita: "",
    x_url_wanita: "",
    keterangan_keluarga_wanita: "",
    foto_wanita: null,
    foto_wanita_path: null,
    doa_pengantin_wanita: "Semoga suami saya sehat selalu dan taat beribadah",

    // Template Undangan Pernikahan
    nama_prosesi: "Akad Nikah",
    tanggal_mulai: "",
    waktu_mulai: "",
    waktu_selesai: "",
    detail_lokasi_nikah: "",
    show_map: true, // Nilai default boolean
    lokasi_nikah: {
        lat: -6.2088,
        lng: 106.8456,
        zoom: 15,
    },
    no_rek_amplop:
        "Bagi yang ingin memberikan hadiah, silahkan transfer ke rekening berikut: [Bank BCA 123456789 Atas nama Pengantin]",
    lokasi_pengiriman_kado:
        "Bagi yang ingin mengirim kado fisik, silahkan kirim ke alamat berikut: [Alamat Pengantin]",

    // Acara
    acaras: [
        {
            nama_acara: "Resepsi",
            tanggal_acara: "",
            waktu_mulai_acara: "",
            waktu_selesai_acara: "",
            detail_lokasi_acara: "",
            show_map: true,
            lokasi_acara: { lat: -6.2088, lng: 106.8456, zoom: 15 },
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
            judul: "",
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
        form.thumbnail_path = props.template.thumbnail_path;

        if (props.template.data_mempelai) {
            const m = props.template.data_mempelai;
            form.nama_panggilan_pria = m.nama_panggilan_pria;
            form.nama_lengkap_pria = m.nama_lengkap_pria;
            form.keterangan_keluarga_pria = m.keterangan_keluarga_pria;
            form.instagram_url_pria = m.instagram_url_pria;
            form.tiktok_url_pria = m.tiktok_url_pria;
            form.x_url_pria = m.x_url_pria;
            form.nama_panggilan_wanita = m.nama_panggilan_wanita;
            form.nama_lengkap_wanita = m.nama_lengkap_wanita;
            form.instagram_url_wanita = m.instagram_url_wanita;
            form.tiktok_url_wanita = m.tiktok_url_wanita;
            form.x_url_wanita = m.x_url_wanita;
            form.keterangan_keluarga_wanita = m.keterangan_keluarga_wanita;
            form.text_penutup = m.text_penutup;
            form.foto_pria_path = m.foto_pria_path;
            form.foto_wanita_path = m.foto_wanita_path;

            // Doa fields are now in the Data Mempelai section in form
            if (props.template.template_undangan_pernikahan) {
                form.doa_pengantin_pria =
                    props.template.template_undangan_pernikahan.doa_pengantin_pria;
                form.doa_pengantin_wanita =
                    props.template.template_undangan_pernikahan.doa_pengantin_wanita;
            }
        }
        if (props.template.template_undangan_pernikahan) {
            const p = props.template.template_undangan_pernikahan;
            form.nama_prosesi = p.nama_prosesi;
            form.tanggal_mulai = p.tanggal_mulai
                ? p.tanggal_mulai.split("T")[0]
                : "";
            form.waktu_mulai = p.waktu_mulai;
            form.waktu_selesai = p.waktu_selesai;
            form.detail_lokasi_nikah = p.detail_lokasi_nikah;
            form.show_map = p.show_map === 1 || p.show_map === true;
            form.no_rek_amplop = p.no_rek_amplop;
            form.lokasi_pengiriman_kado = p.lokasi_pengiriman_kado;
            if (p.latitude && p.longitude) {
                form.lokasi_nikah = {
                    lat: parseFloat(p.latitude),
                    lng: parseFloat(p.longitude),
                    zoom: p.zoom || 15,
                };
            }
        }

        if (props.template.acaras && props.template.acaras.length > 0) {
            form.acaras = props.template.acaras.map((a) => ({
                id: a.id,
                nama_acara: a.nama_acara,
                tanggal_acara: a.tanggal_acara
                    ? a.tanggal_acara.split("T")[0]
                    : "",
                waktu_mulai_acara: a.waktu_mulai_acara,
                waktu_selesai_acara: a.waktu_selesai_acara,
                detail_lokasi_acara: a.detail_lokasi_acara,
                show_map: a.show_map === 1 || a.show_map === true,
                lokasi_acara: {
                    lat: parseFloat(a.latitude_acara) || -6.2088,
                    lng: parseFloat(a.longitude_acara) || 106.8456,
                    zoom: a.zoom || 15,
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
                initial_files: g.image_path
                    ? [
                          {
                              source: `/storage/${g.image_path}`,
                              options: { type: "local" },
                          },
                      ]
                    : [],
            }));
        }

        if (
            props.template.kisah_cintas &&
            props.template.kisah_cintas.length > 0
        ) {
            form.kisah_cintas = props.template.kisah_cintas.map((k) => ({
                id: k.id,
                tanggal: k.tanggal,
                judul: k.judul,
                peristiwa: k.peristiwa,
                foto: null,
                foto_path: k.foto_kisah_cinta_path,
                initial_files: k.foto_kisah_cinta_path
                    ? [
                          {
                              source: `/storage/${k.foto_kisah_cinta_path}`,
                              options: { type: "local" },
                          },
                      ]
                    : [],
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
    <userDashboardLayout>
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
                    <Link
                        class="cursor-pointer flex items-center gap-2 px-6 py-3 font-medium text-sm"
                    >
                        <Icon icon="mdi:book-open-variant" width="18" />
                        Konten Undangan
                    </Link>
                    <Link
                        class="cursor-pointer flex items-center gap-2 px-6 py-3 font-medium text-sm"
                    >
                        <Icon icon="mdi:eye" width="18" />
                        Lihat Preview
                    </Link>
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
                    <DetailAcaraPernikahan v-model="form" />

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
                            @click="router.get(route('user.undangan.index'))"
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
    </userDashboardLayout>
</template>
