<script setup>
import { ref } from "vue";
import UserDashboardLayout from "../../layouts/userDashboardLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import Swal from "sweetalert2";

const props = defineProps({
    undangans: {
        type: Array,
        default: () => [],
    },
});

const copiedSlug = ref(null);

// Fungsi simulasi copy link ke clipboard
const shareLink = (slug) => {
    const url = `${window.location.origin}/undangan/${slug}`;
    navigator.clipboard.writeText(url).then(() => {
        copiedSlug.value = slug;

        Swal.fire({
            icon: "success",
            title: "Link Disalin!",
            text: "Link undangan berhasil disalin ke clipboard.",
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: "top-end",
        });

        setTimeout(() => {
            copiedSlug.value = null;
        }, 2000);
    });
};

// Helper untuk format tanggal
const formatDate = (dateString) => {
    if (!dateString) return "-";
    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
        day: "numeric",
        month: "short",
        year: "numeric",
    });
};
</script>

<template>
    <UserDashboardLayout>
        <!-- Slot: Header Title -->
        <template #headerTitle>
            <div class="flex items-center gap-2">
                <Icon
                    icon="mdi:email-multiple-outline"
                    width="22"
                    class="text-[#004D31]"
                />
                <span class="font-semibold text-gray-800">Undangan Saya</span>
            </div>
        </template>

        <!-- Slot: Content -->
        <template #content>
            <div class="w-full p-5 bg-white">
                <!-- Page Header -->
                <div
                    class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8"
                >
                    <div>
                        <h1 class="text-xl font-bold text-gray-800 mb-1">
                            Daftar Undangan
                        </h1>
                        <p class="text-sm text-gray-600">
                            Kelola dan pantau semua undangan digital Anda di
                            sini.
                        </p>
                    </div>
                    <div>
                        <Link
                            :href="route('user.undangan.pilih-template')"
                            class="cursor-pointer inline-flex items-center gap-2 bg-[#004D31] hover:bg-[#003824] text-white px-4 py-2.5 rounded-sm font-medium transition-colors border border-[#004D31]"
                        >
                            <Icon icon="mdi:plus" width="18" />
                            Buat Undangan Baru
                        </Link>
                    </div>
                </div>

                <!-- EMPTY STATE -->
                <div
                    v-if="undangans.length === 0"
                    class="bg-white border border-gray-300 rounded-sm p-12 text-center flex flex-col items-center justify-center shadow-sm"
                >
                    <div
                        class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4"
                    >
                        <Icon
                            icon="mdi:email-open-heart-outline"
                            class="text-4xl text-gray-400"
                        />
                    </div>
                    <h2 class="text-lg font-bold text-gray-800 mb-2">
                        Belum Ada Undangan
                    </h2>
                    <p class="text-sm text-gray-500 max-w-md mx-auto mb-6">
                        Anda belum membuat undangan apapun. Mulai perjalanan
                        Anda dengan memilih template elegan yang telah kami
                        sediakan.
                    </p>
                    <Link
                        :href="route('user.undangan.pilih-template')"
                        class="bg-[#004D31] hover:bg-[#003824] text-white px-6 py-2 rounded-sm font-medium transition-colors"
                    >
                        Mulai Buat Undangan
                    </Link>
                </div>

                <!-- GRID UNDANGAN -->
                <div
                    v-else
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    <div
                        v-for="undangan in undangans"
                        :key="undangan.id"
                        class="bg-white border border-gray-300 rounded-sm overflow-hidden flex flex-col group transition-all hover:border-[#004D31]/50 hover:shadow-md"
                    >
                        <!-- Thumbnail Preview -->
                        <div
                            class="relative aspect-video overflow-hidden bg-gray-100 border-b border-gray-200"
                        >
                            <img
                                :src="
                                    undangan.template_undangan?.thumbnail ||
                                    'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=2070&auto=format&fit=crop'
                                "
                                alt="Thumbnail"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            />

                            <!-- Badges -->
                            <div
                                class="absolute top-3 left-3 flex flex-col gap-2"
                            >
                                <span
                                    v-if="
                                        undangan.template_undangan?.is_premium
                                    "
                                    class="bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-sm uppercase tracking-wider flex items-center gap-1 shadow-sm"
                                >
                                    <Icon icon="mdi:crown" width="12" />
                                    Premium
                                </span>
                            </div>

                            <div class="absolute top-3 right-3">
                                <span
                                    class="text-[10px] font-bold px-2 py-0.5 rounded-sm uppercase tracking-wider shadow-sm border"
                                    :class="
                                        undangan.status === 'aktif'
                                            ? 'bg-green-50 text-green-700 border-green-200'
                                            : 'bg-gray-50 text-gray-700 border-gray-200'
                                    "
                                >
                                    {{ undangan.status || "Draft" }}
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4 grow">
                            <h2
                                class="text-base font-bold text-gray-800 mb-1 truncate"
                            >
                                {{ undangan.title }}
                            </h2>
                            <p
                                class="text-[11px] text-gray-500 mb-4 flex items-center gap-1"
                            >
                                <Icon icon="mdi:calendar" width="14" />
                                {{
                                    formatDate(
                                        undangan.tanggal_acara ||
                                            undangan.created_at,
                                    )
                                }}
                            </p>

                            <!-- Quick Stats/Info -->
                            <div class="space-y-2 mb-4">
                                <div
                                    class="flex justify-between text-xs py-1.5 border-b border-dashed border-gray-200"
                                >
                                    <span class="text-gray-500 italic"
                                        >Tema</span
                                    >
                                    <span class="font-medium text-gray-700">{{
                                        undangan.template_undangan?.name ||
                                        "Standard"
                                    }}</span>
                                </div>
                                <div
                                    class="flex justify-between text-xs py-1.5 border-b border-dashed border-gray-200"
                                >
                                    <span class="text-gray-500 italic"
                                        >Dibuat</span
                                    >
                                    <span class="font-medium text-gray-700">{{
                                        formatDate(undangan.created_at)
                                    }}</span>
                                </div>
                            </div>

                            <!-- Primary Actions -->
                            <div class="flex gap-2">
                                <a
                                    :href="`/undangan/${undangan.slug}`"
                                    target="_blank"
                                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold py-2 rounded-sm flex items-center justify-center gap-1.5 transition-colors border border-gray-300"
                                >
                                    <Icon icon="mdi:eye-outline" width="16" />
                                    Preview
                                </a>
                                <button
                                    @click="shareLink(undangan.slug)"
                                    class="flex-1 bg-white hover:bg-gray-50 text-[#004D31] text-xs font-semibold py-2 rounded-sm flex items-center justify-center gap-1.5 transition-colors border border-[#004D31]"
                                >
                                    <Icon
                                        :icon="
                                            copiedSlug === undangan.slug
                                                ? 'mdi:check'
                                                : 'mdi:share-variant-outline'
                                        "
                                        width="16"
                                    />
                                    {{
                                        copiedSlug === undangan.slug
                                            ? "Disalin"
                                            : "Bagikan"
                                    }}
                                </button>
                            </div>
                        </div>

                        <!-- Footer Menu -->
                        <div class="bg-gray-50 border-t border-gray-200 p-3">
                            <div class="grid grid-cols-2 gap-2">
                                <button
                                    class="flex items-center gap-2 px-3 py-2 text-[11px] font-medium text-gray-600 hover:text-[#004D31] hover:bg-white rounded-sm transition-all border border-transparent hover:border-gray-200"
                                >
                                    <Icon
                                        icon="mdi:square-edit-outline"
                                        width="16"
                                    />
                                    Edit Konten
                                </button>
                                <button
                                    class="flex items-center gap-2 px-3 py-2 text-[11px] font-medium text-gray-600 hover:text-[#004D31] hover:bg-white rounded-sm transition-all border border-transparent hover:border-gray-200"
                                >
                                    <Icon icon="mdi:cog-outline" width="16" />
                                    Pengaturan
                                </button>
                                <button
                                    class="flex items-center gap-2 px-3 py-2 text-[11px] font-medium text-gray-600 hover:text-[#004D31] hover:bg-white rounded-sm transition-all border border-transparent hover:border-gray-200"
                                >
                                    <Icon
                                        icon="mdi:book-open-variant"
                                        width="16"
                                    />
                                    Buku Tamu
                                </button>
                                <button
                                    class="flex items-center gap-2 px-3 py-2 text-[11px] font-medium text-gray-600 hover:text-red-600 hover:bg-white rounded-sm transition-all border border-transparent hover:border-gray-200"
                                >
                                    <Icon
                                        icon="mdi:delete-outline"
                                        width="16"
                                    />
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </UserDashboardLayout>
</template>

<style scoped>
/* Transisi halus untuk hover */
.group:hover {
    transform: translateY(-2px);
}
</style>
