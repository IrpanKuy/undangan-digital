<script setup>
import { ref, onMounted } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import adminDashboardLayout from "../../../layouts/adminDashboardLayout.vue";
import LeafletMap from "../../../../components/LeafletMap.vue";
import axios from "axios";

const props = defineProps({
    template: Object,
});

const activeTab = ref("content");

// Utility to parse geography (assuming format from Laravel/PostGIS via GeoJSON or simple point)
const parseGeo = (geo) => {
    if (!geo) return { lat: -6.2088, lng: 106.8456 };
    // If it's a string POINT(lng lat)
    if (typeof geo === "string" && geo.startsWith("POINT")) {
        const coords = geo.match(/\((.*)\)/)[1].split(" ");
        return { lat: parseFloat(coords[1]), lng: parseFloat(coords[0]) };
    }
    // If it's already an object (e.g. from a previous save or casting)
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
        },
    );
};

const addAcara = () =>
    form.acaras.push({
        nama_acara: "",
        waktu_acara: "",
        detail_lokasi_acara: "",
        lokasi_acara: { lat: -6.2088, lng: 106.8456 },
    });
const removeAcara = (index) => form.acaras.splice(index, 1);

const addGallery = (event) => {
    const files = event.target.files;
    for (let i = 0; i < files.length; i++) {
        form.galleries.push(files[i]);
    }
};
const removeExistingGallery = (id) => form.remove_galleries.push(id);
const removeNewGallery = (index) => form.galleries.splice(index, 1);

const addKisahCinta = () =>
    form.kisah_cintas.push({ tanggal: "", peristiwa: "", foto: null });
const removeKisahCinta = (index) => form.kisah_cintas.splice(index, 1);
</script>

<template>
    <adminDashboardLayout>
        <template #headerTitle>
            <v-icon icon="mdi-pencil" class="mr-2" color="primary" />
            Edit Konten Template: {{ template.judul }}
        </template>

        <template #content>
            <v-container fluid class="max-w-7xl">
                <v-tabs
                    v-model="activeTab"
                    color="primary"
                    class="mb-6 border-b"
                >
                    <v-tab value="content">Konten Undangan</v-tab>
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
                        >Pengaturan</v-tab
                    >
                </v-tabs>

                <form @submit.prevent="submit">
                    <!-- Section 1: Informasi Dasar -->
                    <v-card
                        variant="outlined"
                        class="mb-6 rounded-xl border-gray-200"
                    >
                        <div class="bg-gray-50 px-6 py-4 border-b">
                            <h2
                                class="text-lg font-bold flex items-center gap-2"
                            >
                                <v-icon
                                    icon="mdi-information-outline"
                                    color="primary"
                                />
                                Informasi Dasar
                            </h2>
                        </div>
                        <v-card-text class="p-6">
                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="form.judul"
                                        label="Judul Undangan"
                                        :error-messages="form.errors.judul"
                                        required
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="form.url"
                                        label="Custom URL"
                                        :error-messages="form.errors.url"
                                        required
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-img
                                        v-if="template.thumbnail_path"
                                        :src="
                                            '/storage/' +
                                            template.thumbnail_path
                                        "
                                        height="150"
                                        width="200"
                                        cover
                                        class="rounded-lg mb-2"
                                    />
                                    <v-file-input
                                        v-model="form.thumbnail"
                                        label="Update Thumbnail"
                                        accept="image/*"
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="form.video_youtube_url"
                                        label="Video YouTube URL"
                                        :error-messages="
                                            form.errors.video_youtube_url
                                        "
                                        required
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-textarea
                                        v-model="form.salam_pembuka"
                                        label="Salam Pembuka"
                                        rows="2"
                                        required
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-textarea
                                        v-model="form.text_pembuka"
                                        label="Text Pembuka"
                                        rows="2"
                                        required
                                    />
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>

                    <!-- Section 2: Data Mempelai -->
                    <v-card
                        variant="outlined"
                        class="mb-6 rounded-xl border-gray-200"
                    >
                        <div class="bg-gray-50 px-6 py-4 border-b">
                            <h2
                                class="text-lg font-bold flex items-center gap-2"
                            >
                                <v-icon
                                    icon="mdi-account-heart-outline"
                                    color="pink"
                                />
                                Data Mempelai
                            </h2>
                        </div>
                        <v-card-text class="p-6">
                            <v-row>
                                <v-col cols="12" md="6">
                                    <h3 class="font-bold mb-4 text-primary">
                                        Mempelai Pria
                                    </h3>
                                    <v-text-field
                                        v-model="form.nama_lengkap_pria"
                                        label="Nama Lengkap Pria"
                                        required
                                    />
                                    <v-text-field
                                        v-model="form.nama_panggilan_pria"
                                        label="Nama Panggilan Pria"
                                        required
                                    />
                                    <v-textarea
                                        v-model="form.keterangan_keluarga_pria"
                                        label="Keluarga Pria"
                                        rows="2"
                                        required
                                    />
                                    <v-img
                                        v-if="
                                            template.data_mempelai
                                                ?.foto_pria_path
                                        "
                                        :src="
                                            '/storage/' +
                                            template.data_mempelai
                                                .foto_pria_path
                                        "
                                        height="100"
                                        width="100"
                                        cover
                                        class="rounded-lg mb-2"
                                    />
                                    <v-file-input
                                        v-model="form.foto_pria"
                                        label="Update Foto Pria"
                                        accept="image/*"
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <h3 class="font-bold mb-4 text-pink-600">
                                        Mempelai Wanita
                                    </h3>
                                    <v-text-field
                                        v-model="form.nama_lengkap_wanita"
                                        label="Nama Lengkap Wanita"
                                        required
                                    />
                                    <v-text-field
                                        v-model="form.nama_panggilan_wanita"
                                        label="Nama Panggilan Wanita"
                                        required
                                    />
                                    <v-textarea
                                        v-model="
                                            form.keterangan_keluarga_wanita
                                        "
                                        label="Keluarga Wanita"
                                        rows="2"
                                        required
                                    />
                                    <v-img
                                        v-if="
                                            template.data_mempelai
                                                ?.foto_wanita_path
                                        "
                                        :src="
                                            '/storage/' +
                                            template.data_mempelai
                                                .foto_wanita_path
                                        "
                                        height="100"
                                        width="100"
                                        cover
                                        class="rounded-lg mb-2"
                                    />
                                    <v-file-input
                                        v-model="form.foto_wanita"
                                        label="Update Foto Wanita"
                                        accept="image/*"
                                    />
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>

                    <!-- Section 3: Akad & Acara -->
                    <v-card
                        variant="outlined"
                        class="mb-6 rounded-xl border-gray-200"
                    >
                        <div class="bg-gray-50 px-6 py-4 border-b">
                            <h2 class="text-lg font-bold">
                                Detail Akad & Acara
                            </h2>
                        </div>
                        <v-card-text class="p-6">
                            <h3 class="font-bold mb-4">Akad Nikah</h3>
                            <v-row>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        v-model="form.tanggal_mulai_akad"
                                        type="date"
                                        label="Tanggal"
                                        required
                                    />
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        v-model="form.waktu_mulai_akad"
                                        type="time"
                                        label="Mulai"
                                        required
                                    />
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        v-model="form.waktu_selesai_akad"
                                        type="time"
                                        label="Selesai"
                                        required
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-textarea
                                        v-model="form.detail_lokasi_akad_nikah"
                                        label="Lokasi"
                                        rows="3"
                                        required
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <LeafletMap
                                        v-model="form.lokasi_akad_nikah"
                                    />
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>

                    <!-- Section 3: Akad Nikah -->
                    <v-card
                        variant="outlined"
                        class="mb-6 rounded-xl border-gray-200"
                    >
                        <div class="bg-gray-50 px-6 py-4 border-b">
                            <h2
                                class="text-lg font-bold flex items-center gap-2"
                            >
                                <v-icon
                                    icon="mdi-calendar-check"
                                    color="primary"
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
                                        required
                                    />
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        v-model="form.waktu_mulai_akad"
                                        type="time"
                                        label="Waktu Mulai"
                                        required
                                    />
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        v-model="form.waktu_selesai_akad"
                                        type="time"
                                        label="Waktu Selesai"
                                        required
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-textarea
                                        v-model="form.detail_lokasi_akad_nikah"
                                        label="Detail Lokasi"
                                        rows="3"
                                        required
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <LeafletMap
                                        v-model="form.lokasi_akad_nikah"
                                    />
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-textarea
                                        v-model="form.doa_pengantinn_pria"
                                        label="Doa untuk Pengantin Pria"
                                        rows="2"
                                        required
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-textarea
                                        v-model="form.doa_pengantin_wanita"
                                        label="Doa untuk Pengantin Wanita"
                                        rows="2"
                                        required
                                    />
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>

                    <!-- Section 4: Acara Tambahan -->
                    <v-card
                        variant="outlined"
                        class="mb-6 rounded-xl border-gray-200"
                    >
                        <div
                            class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center"
                        >
                            <h2
                                class="text-lg font-bold flex items-center gap-2"
                            >
                                <v-icon
                                    icon="mdi-calendar-plus"
                                    color="primary"
                                />
                                Acara Tambahan (Resepsi, dll)
                            </h2>
                            <v-btn
                                color="primary"
                                variant="tonal"
                                prepend-icon="mdi-plus"
                                @click="addAcara"
                                >Tambah Acara</v-btn
                            >
                        </div>
                        <v-card-text class="p-6">
                            <div
                                v-for="(acara, index) in form.acaras"
                                :key="index"
                                class="mb-8 p-4 border rounded-xl relative"
                            >
                                <v-btn
                                    v-if="form.acaras.length > 1"
                                    icon="mdi-delete"
                                    color="error"
                                    variant="text"
                                    class="absolute top-2 right-2"
                                    @click="removeAcara(index)"
                                />
                                <v-row>
                                    <v-col cols="12" md="6">
                                        <v-text-field
                                            v-model="acara.nama_acara"
                                            label="Nama Acara"
                                            required
                                        />
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-text-field
                                            v-model="acara.waktu_acara"
                                            type="time"
                                            label="Waktu"
                                            required
                                        />
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-textarea
                                            v-model="acara.detail_lokasi_acara"
                                            label="Detail Lokasi"
                                            rows="3"
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

                    <!-- Section 5: Gallery Foto -->
                    <v-card
                        variant="outlined"
                        class="mb-6 rounded-xl border-gray-200"
                    >
                        <div
                            class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center"
                        >
                            <h2
                                class="text-lg font-bold flex items-center gap-2"
                            >
                                <v-icon
                                    icon="mdi-image-multiple"
                                    color="primary"
                                />
                                Gallery Foto
                            </h2>
                            <v-btn
                                color="primary"
                                variant="tonal"
                                prepend-icon="mdi-upload"
                                @click="$refs.galleryInput.click()"
                                >Upload Foto</v-btn
                            >
                            <input
                                ref="galleryInput"
                                type="file"
                                multiple
                                class="hidden"
                                accept="image/*"
                                @change="addGallery"
                            />
                        </div>
                        <v-card-text class="p-6">
                            <!-- Existing Gallery -->
                            <h3
                                class="font-bold mb-4"
                                v-if="
                                    form.existing_galleries.filter(
                                        (g) =>
                                            !form.remove_galleries.includes(
                                                g.id,
                                            ),
                                    ).length > 0
                                "
                            >
                                Foto Saat Ini
                            </h3>
                            <v-row class="mb-6">
                                <v-col
                                    v-for="item in form.existing_galleries"
                                    :key="item.id"
                                    cols="6"
                                    sm="4"
                                    md="2"
                                    v-show="
                                        !form.remove_galleries.includes(item.id)
                                    "
                                >
                                    <v-card
                                        variant="flat"
                                        class="rounded-xl overflow-hidden group relative"
                                    >
                                        <v-img
                                            :src="'/storage/' + item.image_path"
                                            cover
                                            height="120"
                                        />
                                        <div
                                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center"
                                        >
                                            <v-btn
                                                icon="mdi-delete"
                                                color="error"
                                                size="small"
                                                @click="
                                                    removeExistingGallery(
                                                        item.id,
                                                    )
                                                "
                                            />
                                        </div>
                                    </v-card>
                                </v-col>
                            </v-row>

                            <!-- New Uploads -->
                            <h3
                                class="font-bold mb-4"
                                v-if="form.galleries.length > 0"
                            >
                                Upload Baru
                            </h3>
                            <v-row>
                                <v-col
                                    v-for="(file, index) in form.galleries"
                                    :key="index"
                                    cols="6"
                                    sm="4"
                                    md="2"
                                >
                                    <v-card
                                        variant="flat"
                                        class="rounded-xl overflow-hidden group relative"
                                    >
                                        <v-img
                                            :src="URL.createObjectURL(file)"
                                            cover
                                            height="120"
                                        />
                                        <div
                                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center"
                                        >
                                            <v-btn
                                                icon="mdi-delete"
                                                color="error"
                                                size="small"
                                                @click="removeNewGallery(index)"
                                            />
                                        </div>
                                    </v-card>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>

                    <!-- Section 6: Kisah Cinta -->
                    <v-card
                        variant="outlined"
                        class="mb-6 rounded-xl border-gray-200"
                    >
                        <div
                            class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center"
                        >
                            <h2
                                class="text-lg font-bold flex items-center gap-2"
                            >
                                <v-icon icon="mdi-heart-multiple" color="red" />
                                Kisah Cinta
                            </h2>
                            <v-btn
                                color="primary"
                                variant="tonal"
                                prepend-icon="mdi-plus"
                                @click="addKisahCinta"
                                >Tambah Kisah</v-btn
                            >
                        </div>
                        <v-card-text class="p-6">
                            <div
                                v-for="(kisah, index) in form.kisah_cintas"
                                :key="index"
                                class="mb-8 p-4 border rounded-xl relative"
                            >
                                <v-btn
                                    icon="mdi-delete"
                                    color="error"
                                    variant="text"
                                    class="absolute top-2 right-2"
                                    @click="removeKisahCinta(index)"
                                />
                                <v-row>
                                    <v-col cols="12" md="4">
                                        <v-text-field
                                            v-model="kisah.tanggal"
                                            type="date"
                                            label="Tanggal Peristiwa"
                                            required
                                        />
                                    </v-col>
                                    <v-col cols="12" md="8">
                                        <v-textarea
                                            v-model="kisah.peristiwa"
                                            label="Ceritakan Peristiwa"
                                            rows="3"
                                            required
                                        />
                                    </v-col>
                                    <v-col cols="12">
                                        <div class="flex items-center gap-4">
                                            <v-img
                                                v-if="kisah.existing_foto"
                                                :src="
                                                    '/storage/' +
                                                    kisah.existing_foto
                                                "
                                                height="80"
                                                width="80"
                                                cover
                                                class="rounded-lg"
                                            />
                                            <v-img
                                                v-if="kisah.foto"
                                                :src="
                                                    URL.createObjectURL(
                                                        kisah.foto,
                                                    )
                                                "
                                                height="80"
                                                width="80"
                                                cover
                                                class="rounded-lg border-2 border-primary"
                                            />
                                            <v-file-input
                                                v-model="kisah.foto"
                                                label="Upload Foto Kisah"
                                                accept="image/*"
                                                prepend-icon="mdi-camera"
                                            />
                                        </div>
                                    </v-col>
                                </v-row>
                            </div>
                        </v-card-text>
                    </v-card>

                    <!-- Section 7: Hadiah & Amplop Digital -->
                    <v-card
                        variant="outlined"
                        class="mb-6 rounded-xl border-gray-200"
                    >
                        <div class="bg-gray-50 px-6 py-4 border-b">
                            <h2
                                class="text-lg font-bold flex items-center gap-2"
                            >
                                <v-icon
                                    icon="mdi-gift-outline"
                                    color="orange"
                                />
                                Hadiah & Amplop Digital
                            </h2>
                        </div>
                        <v-card-text class="p-6">
                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-textarea
                                        v-model="form.no_rek_amplop"
                                        label="Data Rekening Bank (Nomor & Atas Nama)"
                                        rows="4"
                                        placeholder="Contoh:\nBCA 123456789 a/n Nama\nMandiri 987654321 a/n Nama"
                                        required
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-textarea
                                        v-model="form.lokasi_pengiriman_kado"
                                        label="Alamat Pengiriman Kado"
                                        rows="4"
                                        placeholder="Masukkan alamat lengkap untuk pengiriman kado fisik"
                                        required
                                    />
                                </v-col>
                            </v-row>
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
                            >Kembali</v-btn
                        >
                        <v-btn
                            type="submit"
                            color="primary"
                            size="large"
                            :loading="form.processing"
                            >Simpan Perubahan</v-btn
                        >
                    </div>
                </form>
            </v-container>
        </template>
    </adminDashboardLayout>
</template>
