<script setup>
import { ref, onMounted } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import Swal from "sweetalert2";
import adminDashboardLayout from "../../layouts/adminDashboardLayout.vue";

const props = defineProps({
    template: Object,
});

const activeTab = ref("setting");

const form = useForm({
    id: props.template.id,
    reservation_form:
        props.template.pengaturan_undangan?.reservation_form ?? true,
    komentar_undangan:
        props.template.pengaturan_undangan?.komentar_undangan ?? true,
    jumlah_kehadiran:
        props.template.pengaturan_undangan?.jumlah_kehadiran ?? true,
    music_url: props.template.pengaturan_undangan?.music_url ?? "",
});

const submit = () => {
    form.put(
        route(
            "admin.template-content-undangan.update-setting",
            props.template.id,
        ),
        {
            onSuccess: () => {
                Swal.fire({
                    icon: "success",
                    title: "Berhasil!",
                    text: "Pengaturan template berhasil diperbarui.",
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

                // Scroll to the first error
                setTimeout(() => {
                    const firstError =
                        document.querySelector(".v-input--error");
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: "smooth",
                            block: "center",
                        });
                    }
                }, 100);
            },
        },
    );
};
</script>

<template>
    <adminDashboardLayout>
        <template #headerTitle>
            <div class="flex items-center gap-2">
                <Icon icon="mdi:cog-outline" width="24" class="text-primary" />
                <span>Pengaturan Template</span>
            </div>
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
                                    'admin.template-content-undangan.edit-content',
                                    props.template.id,
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
                            color="secondary"
                            class="rounded-lg text-none"
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
                            class="rounded-lg text-none"
                            :loading="form.processing"
                        >
                            Simpan Pengaturan
                        </v-btn>
                    </div>
                </form>
            </v-container>
        </template>
    </adminDashboardLayout>
</template>
