<script setup>
import { ref } from "vue";
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

                // Scroll to the first error (disesuaikan dengan class Tailwind)
                setTimeout(() => {
                    const firstError = document.querySelector(
                        ".border-red-500, .text-red-600",
                    );
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
        <!-- Slot Header -->
        <template #headerTitle>
            <div class="flex items-center gap-2">
                <Icon
                    icon="mdi:cog-outline"
                    width="22"
                    class="text-[#004D31]"
                />
                <span class="font-semibold text-gray-800"
                    >Pengaturan Template</span
                >
            </div>
        </template>

        <!-- Slot Content -->
        <template #content>
            <div class="w-full max-w-4xl pb-10">
                <!-- Navigation Tabs (Flat UI) -->
                <div class="flex border-b border-gray-300 mb-6">
                    <button
                        type="button"
                        @click="
                            router.get(
                                route(
                                    'admin.template-content-undangan.edit-content',
                                    props.template.id,
                                ),
                            )
                        "
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
                        @click="activeTab = 'setting'"
                        :class="[
                            'cursor-pointer flex items-center gap-2 px-6 py-3 font-medium text-sm transition-colors border-b-2 outline-none',
                            activeTab === 'setting'
                                ? 'border-[#004D31] text-[#004D31]'
                                : 'border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300',
                        ]"
                    >
                        <Icon icon="mdi:cog" width="18" />
                        Pengaturan
                    </button>
                </div>

                <!-- Form Pengaturan -->
                <form @submit.prevent="submit">
                    <div class="bg-white border border-gray-300 rounded-sm">
                        <!-- Header Card -->
                        <div
                            class="bg-gray-100 border-b border-gray-300 px-6 py-4 flex items-center gap-2"
                        >
                            <Icon
                                icon="mdi:tune-variant"
                                width="20"
                                class="text-[#004D31]"
                            />
                            <h2 class="font-bold text-gray-800 text-base">
                                Pengaturan Fitur Undangan
                            </h2>
                        </div>

                        <!-- Content Card -->
                        <div class="p-6">
                            <div class="space-y-6">
                                <!-- Toggle: Form Reservasi -->
                                <div
                                    class="flex items-center justify-between group"
                                >
                                    <div class="flex gap-4 items-start">
                                        <Icon
                                            icon="mdi:form-select"
                                            width="24"
                                            class="text-[#004D31] mt-0.5"
                                        />
                                        <div>
                                            <h4
                                                class="font-bold text-gray-800 text-sm"
                                            >
                                                Form Reservasi (RSVP)
                                            </h4>
                                            <p
                                                class="text-xs text-gray-500 mt-0.5"
                                            >
                                                Aktifkan agar tamu dapat
                                                mengirimkan konfirmasi kehadiran
                                            </p>
                                        </div>
                                    </div>
                                    <label
                                        class="relative inline-flex items-center cursor-pointer"
                                    >
                                        <input
                                            type="checkbox"
                                            v-model="form.reservation_form"
                                            class="sr-only peer"
                                        />
                                        <div
                                            class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#004D31]"
                                        ></div>
                                    </label>
                                </div>

                                <hr class="border-gray-200" />

                                <!-- Toggle: Komentar & Ucapan -->
                                <div
                                    class="flex items-center justify-between group"
                                >
                                    <div class="flex gap-4 items-start">
                                        <Icon
                                            icon="mdi:comment-text-outline"
                                            width="24"
                                            class="text-[#004D31] mt-0.5"
                                        />
                                        <div>
                                            <h4
                                                class="font-bold text-gray-800 text-sm"
                                            >
                                                Komentar & Ucapan
                                            </h4>
                                            <p
                                                class="text-xs text-gray-500 mt-0.5"
                                            >
                                                Aktifkan fitur buku tamu digital
                                                (ucapan dan doa)
                                            </p>
                                        </div>
                                    </div>
                                    <label
                                        class="relative inline-flex items-center cursor-pointer"
                                    >
                                        <input
                                            type="checkbox"
                                            v-model="form.komentar_undangan"
                                            class="sr-only peer"
                                        />
                                        <div
                                            class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#004D31]"
                                        ></div>
                                    </label>
                                </div>

                                <hr class="border-gray-200" />

                                <!-- Toggle: Jumlah Kehadiran -->
                                <div
                                    class="flex items-center justify-between group"
                                >
                                    <div class="flex gap-4 items-start">
                                        <Icon
                                            icon="mdi:account-group-outline"
                                            width="24"
                                            class="text-[#004D31] mt-0.5"
                                        />
                                        <div>
                                            <h4
                                                class="font-bold text-gray-800 text-sm"
                                            >
                                                Jumlah Kehadiran
                                            </h4>
                                            <p
                                                class="text-xs text-gray-500 mt-0.5"
                                            >
                                                Tampilkan total tamu yang sudah
                                                melakukan konfirmasi
                                            </p>
                                        </div>
                                    </div>
                                    <label
                                        class="relative inline-flex items-center cursor-pointer"
                                    >
                                        <input
                                            type="checkbox"
                                            v-model="form.jumlah_kehadiran"
                                            class="sr-only peer"
                                        />
                                        <div
                                            class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#004D31]"
                                        ></div>
                                    </label>
                                </div>

                                <hr class="border-gray-200" />

                                <!-- Input: Music URL -->
                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-700 uppercase mb-1 flex items-center gap-1"
                                    >
                                        <Icon
                                            icon="mdi:music-note"
                                            width="16"
                                        />
                                        Background Music URL (Opsional)
                                    </label>
                                    <input
                                        v-model="form.music_url"
                                        type="url"
                                        class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                                        placeholder="https://example.com/music.mp3 atau Link YouTube"
                                    />
                                    <p
                                        class="text-[10px] text-gray-500 mt-1 font-medium"
                                    >
                                        Masukkan URL file audio atau link video
                                        YouTube.
                                    </p>
                                    <p
                                        v-if="form.errors.music_url"
                                        class="text-[10px] text-red-600 mt-1 uppercase font-bold"
                                    >
                                        {{ form.errors.music_url }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 mt-8">
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
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </template>
    </adminDashboardLayout>
</template>
