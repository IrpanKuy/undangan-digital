<script setup>
import { ref, reactive, watch } from "vue";
import { useForm, router, Link } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import adminDashboardLayout from "../../../layouts/adminDashboardLayout.vue";
import LeafletMap from "../../../../components/LeafletMap.vue";
import axios from "axios";

// Tab state for navigation (simulating tabs between pages)
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

    // Acara (Array for multiple)
    acaras: [
        {
            nama_acara: "Resepsi",
            waktu_acara: "",
            detail_lokasi_acara: "",
            lokasi_acara: { lat: -6.2088, lng: 106.8456 },
        },
    ],

    // Gallery (Array for multiple)
    galleries: [],

    // Kisah Cinta (Array for multiple)
    kisah_cintas: [
        {
            tanggal: "",
            peristiwa: "",
            foto: null,
        },
    ],
});

// URL Uniq Check
const urlStatus = ref(null); // null, 'checking', 'available', 'taken'
const checkUrl = async () => {
    if (!form.url) return;
    urlStatus.value = "checking";
    try {
        const response = await axios.post(
            route("admin.template-content-undangan.check-url"),
            {
                url: form.url,
            },
        );
        urlStatus.value = response.data.available ? "available" : "taken";
    } catch (error) {
        urlStatus.value = null;
    }
};

// Form submission
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
            Swal.fire({
                icon: "error",
                title: "Gagal!",
                text: "Silakan periksa kembali inputan Anda.",
                timer: 3000,
            });
            // Scroll to the first error
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

// Handlers for dynamic arrays
const addAcara = () => {
    form.acaras.push({
        nama_acara: "",
        waktu_acara: "",
        detail_lokasi_acara: "",
        lokasi_acara: { lat: -6.2088, lng: 106.8456 },
    });
};
const removeAcara = (index) => form.acaras.splice(index, 1);

const addGallery = (event) => {
    const files = event.target.files;
    for (let i = 0; i < files.length; i++) {
        form.galleries.push(files[i]);
    }
};
const removeGallery = (index) => form.galleries.splice(index, 1);

const addKisahCinta = () => {
    form.kisah_cintas.push({
        tanggal: "",
        peristiwa: "",
        foto: null,
    });
};
const removeKisahCinta = (index) => form.kisah_cintas.splice(index, 1);
</script>

<template>
    <adminDashboardLayout>
        <template #headerTitle>
            <Icon icon="mdi:pencil-plus-outline" width="22" color="#004D31" />
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
                    <v-card
                        variant="outlined"
                        class="mb-6 rounded-xl overflow-hidden border-gray-200"
                    >
                        <div
                            class="bg-gray-50 px-6 py-4 border-b border-gray-200"
                        >
                            <h2
                                class="text-lg font-bold text-gray-800 flex items-center gap-2"
                            >
                                <v-icon
                                    icon="mdi-information-outline"
                                    color="primary"
                                    size="24"
                                />
                                Informasi Dasar Undangan
                            </h2>
                        </div>
                        <v-card-text class="p-6">
                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="form.judul"
                                        label="Judul Undangan"
                                        placeholder="Contoh: Pernikahan Budi & Ani"
                                        :error-messages="form.errors.judul"
                                        required
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="form.url"
                                        label="Custom URL"
                                        placeholder="budi-ani"
                                        prefix="undangan.com/"
                                        :error-messages="form.errors.url"
                                        @blur="checkUrl"
                                        required
                                    >
                                        <template #append-inner>
                                            <v-progress-circular
                                                v-if="urlStatus === 'checking'"
                                                indeterminate
                                                size="20"
                                                width="2"
                                            />
                                            <v-icon
                                                v-else-if="
                                                    urlStatus === 'available'
                                                "
                                                icon="mdi-check-circle"
                                                color="success"
                                            />
                                            <v-icon
                                                v-else-if="
                                                    urlStatus === 'taken'
                                                "
                                                icon="mdi-alert-circle"
                                                color="error"
                                            />
                                        </template>
                                    </v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-file-input
                                        v-model="form.thumbnail"
                                        label="Thumbnail Undangan"
                                        prepend-icon=""
                                        prepend-inner-icon="mdi-camera"
                                        accept="image/*"
                                        :error-messages="form.errors.thumbnail"
                                        required
                                        show-size
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-textarea
                                        v-model="form.salam_pembuka"
                                        label="Salam Pembuka"
                                        placeholder="Contoh: Assalamu'alaikum Wr. Wb."
                                        rows="2"
                                        :error-messages="
                                            form.errors.salam_pembuka
                                        "
                                        required
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-textarea
                                        v-model="form.text_pembuka"
                                        label="Text Pembuka"
                                        placeholder="Maha suci Allah yang telah menciptakan makhluk-Nya berpasang-pasangan..."
                                        rows="2"
                                        :error-messages="
                                            form.errors.text_pembuka
                                        "
                                        required
                                    />
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="form.video_youtube_url"
                                        label="Video YouTube URL (Opsional di DB tapi REQUIRED di Form)"
                                        placeholder="https://www.youtube.com/watch?v=..."
                                        prepend-inner-icon="mdi-youtube"
                                        :error-messages="
                                            form.errors.video_youtube_url
                                        "
                                        required
                                    />
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>

                    <!-- Section 2: Data Mempelai -->
                    <v-card
                        variant="outlined"
                        class="mb-6 rounded-xl overflow-hidden border-gray-200"
                    >
                        <div
                            class="bg-gray-50 px-6 py-4 border-b border-gray-200"
                        >
                            <h2
                                class="text-lg font-bold text-gray-800 flex items-center gap-2"
                            >
                                <v-icon
                                    icon="mdi-account-heart-outline"
                                    color="pink"
                                    size="24"
                                />
                                Data Mempelai
                            </h2>
                        </div>
                        <v-card-text class="p-6">
                            <v-row>
                                <!-- Mempelai Pria -->
                                <v-col cols="12" md="6">
                                    <h3
                                        class="font-bold mb-4 text-primary border-b pb-2"
                                    >
                                        Mempelai Pria
                                    </h3>
                                    <v-text-field
                                        v-model="form.nama_lengkap_pria"
                                        label="Nama Lengkap Pria"
                                        :error-messages="
                                            form.errors.nama_lengkap_pria
                                        "
                                        required
                                    />
                                    <v-text-field
                                        v-model="form.nama_panggilan_pria"
                                        label="Nama Panggilan Pria"
                                        :error-messages="
                                            form.errors.nama_panggilan_pria
                                        "
                                        required
                                    />
                                    <v-textarea
                                        v-model="form.keterangan_keluarga_pria"
                                        label="Keterangan Keluarga Pria"
                                        placeholder="Putra pertama dari Bapak X dan Ibu Y"
                                        rows="2"
                                        :error-messages="
                                            form.errors.keterangan_keluarga_pria
                                        "
                                        required
                                    />
                                    <v-file-input
                                        v-model="form.foto_pria"
                                        label="Foto Mempelai Pria"
                                        prepend-inner-icon="mdi-camera"
                                        prepend-icon=""
                                        accept="image/*"
                                        :error-messages="form.errors.foto_pria"
                                        required
                                    />
                                </v-col>
                                <!-- Mempelai Wanita -->
                                <v-col cols="12" md="6">
                                    <h3
                                        class="font-bold mb-4 text-pink-600 border-b pb-2"
                                    >
                                        Mempelai Wanita
                                    </h3>
                                    <v-text-field
                                        v-model="form.nama_lengkap_wanita"
                                        label="Nama Lengkap Wanita"
                                        :error-messages="
                                            form.errors.nama_lengkap_wanita
                                        "
                                        required
                                    />
                                    <v-text-field
                                        v-model="form.nama_panggilan_wanita"
                                        label="Nama Panggilan Wanita"
                                        :error-messages="
                                            form.errors.nama_panggilan_wanita
                                        "
                                        required
                                    />
                                    <v-textarea
                                        v-model="
                                            form.keterangan_keluarga_wanita
                                        "
                                        label="Keterangan Keluarga Wanita"
                                        placeholder="Putri kedua dari Bapak A dan Ibu B"
                                        rows="2"
                                        :error-messages="
                                            form.errors
                                                .keterangan_keluarga_wanita
                                        "
                                        required
                                    />
                                    <v-file-input
                                        v-model="form.foto_wanita"
                                        label="Foto Mempelai Wanita"
                                        prepend-inner-icon="mdi-camera"
                                        prepend-icon=""
                                        accept="image/*"
                                        :error-messages="
                                            form.errors.foto_wanita
                                        "
                                        required
                                    />
                                </v-col>
                                <v-col cols="12">
                                    <v-textarea
                                        v-model="form.text_penutup"
                                        label="Text Penutup"
                                        placeholder="Tiada kesan tanpa kehadiran Anda..."
                                        rows="2"
                                        :error-messages="
                                            form.errors.text_penutup
                                        "
                                        required
                                    />
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>

                    <!-- Section 3: Data Pernikahan (Akad) -->
                    <v-card
                        variant="outlined"
                        class="mb-6 rounded-xl overflow-hidden border-gray-200"
                    >
                        <div
                            class="bg-gray-50 px-6 py-4 border-b border-gray-200"
                        >
                            <h2
                                class="text-lg font-bold text-gray-800 flex items-center gap-2"
                            >
                                <v-icon
                                    icon="mdi-church"
                                    color="indigo"
                                    size="24"
                                />
                                Detail Akad Nikah
                            </h2>
                        </div>
                        <v-card-text class="p-6">
                            <v-row>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        v-model="form.tanggal_mulai_akad"
                                        type="date"
                                        label="Tanggal Akad"
                                        :error-messages="
                                            form.errors.tanggal_mulai_akad
                                        "
                                        required
                                    />
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        v-model="form.waktu_mulai_akad"
                                        type="time"
                                        label="Waktu Mulai"
                                        :error-messages="
                                            form.errors.waktu_mulai_akad
                                        "
                                        required
                                    />
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        v-model="form.waktu_selesai_akad"
                                        type="time"
                                        label="Waktu Selesai"
                                        :error-messages="
                                            form.errors.waktu_selesai_akad
                                        "
                                        required
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-textarea
                                        v-model="form.detail_lokasi_akad_nikah"
                                        label="Detail Lokasi Akad"
                                        placeholder="Nama Gedung / Masjid, Lantai, No"
                                        rows="3"
                                        :error-messages="
                                            form.errors.detail_lokasi_akad_nikah
                                        "
                                        required
                                    />
                                    <v-textarea
                                        v-model="form.doa_pengantinn_pria"
                                        label="Doa Pengantin Pria"
                                        rows="2"
                                        :error-messages="
                                            form.errors.doa_pengantinn_pria
                                        "
                                        required
                                    />
                                    <v-textarea
                                        v-model="form.doa_pengantin_wanita"
                                        label="Doa Pengantin Wanita"
                                        rows="2"
                                        :error-messages="
                                            form.errors.doa_pengantin_wanita
                                        "
                                        required
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <p class="text-sm font-bold mb-2">
                                        Pilih Lokasi di Map (Drag Marker)
                                    </p>
                                    <LeafletMap
                                        v-model="form.lokasi_akad_nikah"
                                    />
                                    <div class="mt-2 text-xs text-gray-500">
                                        Lat:
                                        {{
                                            form.lokasi_akad_nikah.lat.toFixed(
                                                6,
                                            )
                                        }}, Lng:
                                        {{
                                            form.lokasi_akad_nikah.lng.toFixed(
                                                6,
                                            )
                                        }}
                                    </div>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>

                    <!-- Section 4: Acara Lainnya -->
                    <v-card
                        variant="outlined"
                        class="mb-6 rounded-xl overflow-hidden border-gray-200"
                    >
                        <div
                            class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center"
                        >
                            <h2
                                class="text-lg font-bold text-gray-800 flex items-center gap-2"
                            >
                                <v-icon
                                    icon="mdi-calendar-multiple"
                                    color="orange"
                                    size="24"
                                />
                                Acara Tambahan (Resepsi, dll)
                            </h2>
                            <v-btn
                                color="orange"
                                size="small"
                                prepend-icon="mdi-plus"
                                @click="addAcara"
                                >Tambah Acara</v-btn
                            >
                        </div>
                        <v-card-text class="p-6">
                            <div
                                v-for="(acara, index) in form.acaras"
                                :key="index"
                                class="mb-8 pb-8 border-b last:border-0 last:mb-0 last:pb-0"
                            >
                                <div
                                    class="flex justify-between items-center mb-4"
                                >
                                    <h3 class="font-bold">
                                        Acara #{{ index + 1 }}
                                    </h3>
                                    <v-btn
                                        v-if="form.acaras.length > 1"
                                        color="error"
                                        variant="text"
                                        icon="mdi-trash-can-outline"
                                        @click="removeAcara(index)"
                                    />
                                </div>
                                <v-row>
                                    <v-col cols="12" md="6">
                                        <v-text-field
                                            v-model="acara.nama_acara"
                                            label="Nama Acara"
                                            placeholder="Contoh: Resepsi Pernikahan"
                                            :error-messages="
                                                form.errors[
                                                    `acaras.${index}.nama_acara`
                                                ]
                                            "
                                            required
                                        />
                                        <v-text-field
                                            v-model="acara.waktu_acara"
                                            type="datetime-local"
                                            label="Waktu Acara"
                                            :error-messages="
                                                form.errors[
                                                    `acaras.${index}.waktu_acara`
                                                ]
                                            "
                                            required
                                        />
                                        <v-textarea
                                            v-model="acara.detail_lokasi_acara"
                                            label="Detail Lokasi Acara"
                                            rows="3"
                                            :error-messages="
                                                form.errors[
                                                    `acaras.${index}.detail_lokasi_acara`
                                                ]
                                            "
                                            required
                                        />
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <LeafletMap
                                            v-model="acara.lokasi_acara"
                                        />
                                    </v-col>
                                </v-row>
                            </div>
                        </v-card-text>
                    </v-card>

                    <!-- Section 5: Gallery & Kisah Cinta -->
                    <v-row>
                        <v-col cols="12" md="6">
                            <v-card
                                variant="outlined"
                                class="h-full rounded-xl overflow-hidden border-gray-200"
                            >
                                <div
                                    class="bg-gray-50 px-6 py-4 border-b border-gray-200"
                                >
                                    <h2
                                        class="text-lg font-bold text-gray-800 flex items-center gap-2"
                                    >
                                        <v-icon
                                            icon="mdi-camera-outline"
                                            color="purple"
                                            size="24"
                                        />
                                        Gallery Foto
                                    </h2>
                                </div>
                                <v-card-text class="p-6">
                                    <input
                                        type="file"
                                        multiple
                                        accept="image/*"
                                        class="hidden"
                                        id="galleryInput"
                                        @change="addGallery"
                                    />
                                    <v-btn
                                        block
                                        color="purple"
                                        variant="flat"
                                        prepend-icon="mdi-plus"
                                        class="mb-4"
                                        @click="
                                            () =>
                                                document
                                                    .getElementById(
                                                        'galleryInput',
                                                    )
                                                    .click()
                                        "
                                        >Upload Foto</v-btn
                                    >
                                    <v-row>
                                        <v-col
                                            v-for="(img, idx) in form.galleries"
                                            :key="idx"
                                            cols="4"
                                        >
                                            <v-img
                                                :src="URL.createObjectURL(img)"
                                                aspect-ratio="1"
                                                cover
                                                class="rounded-lg bg-gray-100"
                                            >
                                                <div class="flex justify-end">
                                                    <v-btn
                                                        icon="mdi-close"
                                                        size="x-small"
                                                        color="error"
                                                        class="m-1"
                                                        @click="
                                                            removeGallery(idx)
                                                        "
                                                    />
                                                </div>
                                            </v-img>
                                        </v-col>
                                    </v-row>
                                </v-card-text>
                            </v-card>
                        </v-col>
                        <v-col cols="12" md="6">
                            <v-card
                                variant="outlined"
                                class="h-full rounded-xl overflow-hidden border-gray-200"
                            >
                                <div
                                    class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center"
                                >
                                    <h2
                                        class="text-lg font-bold text-gray-800 flex items-center gap-2"
                                    >
                                        <v-icon
                                            icon="mdi-heart-outline"
                                            color="pink"
                                            size="24"
                                        />
                                        Kisah Cinta
                                    </h2>
                                    <v-btn
                                        color="pink"
                                        size="small"
                                        variant="text"
                                        prepend-icon="mdi-plus"
                                        @click="addKisahCinta"
                                        >Tambah</v-btn
                                    >
                                </div>
                                <v-card-text class="p-6">
                                    <div
                                        v-for="(
                                            kisah, index
                                        ) in form.kisah_cintas"
                                        :key="index"
                                        class="mb-4 p-4 bg-gray-50 rounded-lg"
                                    >
                                        <div
                                            class="flex justify-between items-center mb-2"
                                        >
                                            <span class="font-bold"
                                                >Momen #{{ index + 1 }}</span
                                            >
                                            <v-btn
                                                v-if="
                                                    form.kisah_cintas.length > 1
                                                "
                                                size="x-small"
                                                color="error"
                                                icon="mdi-close"
                                                @click="removeKisahCinta(index)"
                                            />
                                        </div>
                                        <v-text-field
                                            v-model="kisah.tanggal"
                                            type="date"
                                            label="Tanggal Kejadian"
                                            density="compact"
                                            required
                                        />
                                        <v-textarea
                                            v-model="kisah.peristiwa"
                                            label="Peristiwa"
                                            rows="2"
                                            density="compact"
                                            required
                                        />
                                        <v-file-input
                                            v-model="kisah.foto"
                                            label="Foto Momen"
                                            prepend-inner-icon="mdi-camera"
                                            density="compact"
                                            accept="image/*"
                                            required
                                        />
                                    </div>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>

                    <!-- Section 6: Hadiah -->
                    <v-card
                        variant="outlined"
                        class="mt-6 mb-6 rounded-xl overflow-hidden border-gray-200"
                    >
                        <div
                            class="bg-gray-50 px-6 py-4 border-b border-gray-200"
                        >
                            <h2
                                class="text-lg font-bold text-gray-800 flex items-center gap-2"
                            >
                                <v-icon
                                    icon="mdi-gift-outline"
                                    color="teal"
                                    size="24"
                                />
                                Hadiah & Amplop Digital
                            </h2>
                        </div>
                        <v-card-text class="p-6">
                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-textarea
                                        v-model="form.no_rek_amplop"
                                        label="Daftar No. Rekening / E-Wallet"
                                        placeholder="BCA: 12345678 a/n Budi&#10;Dana: 0812345678 a/n Budi"
                                        rows="3"
                                        :error-messages="
                                            form.errors.no_rek_amplop
                                        "
                                        required
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-textarea
                                        v-model="form.lokasi_pengiriman_kado"
                                        label="Alamat Pengiriman Kado"
                                        placeholder="Jl. Mawar No. 123, Jakarta Selatan"
                                        rows="3"
                                        :error-messages="
                                            form.errors.lokasi_pengiriman_kado
                                        "
                                        required
                                    />
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>

                    <!-- Actions -->
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
                            >Simpan Konten</v-btn
                        >
                    </div>
                </form>
            </v-container>
        </template>
    </adminDashboardLayout>
</template>

<style scoped>
.v-container {
    padding-bottom: 100px;
}
</style>
