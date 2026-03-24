<script setup>
import { Icon } from "@iconify/vue";
import { useForm, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({
    komentarData: {
        type: Array,
        default: () => [],
    },
    undanganId: [Number, String],
});

const searchKomentar = ref("");
const filterBalasan = ref("");

// Added computed property to make search & filter functional
const filteredKomentar = computed(() => {
    if (!props.komentarData) return [];
    return props.komentarData.filter((item) => {
        const matchName = item.nama
            .toLowerCase()
            .includes(searchKomentar.value.toLowerCase());

        let matchStatus = true;
        if (filterBalasan.value === "sudah") {
            matchStatus =
                item.balasan_pengantin != null && item.balasan_pengantin !== "";
        } else if (filterBalasan.value === "belum") {
            matchStatus =
                item.balasan_pengantin == null || item.balasan_pengantin === "";
        }

        return matchName && matchStatus;
    });
});

const isReplyModalOpen = ref(false);
const selectedKomentar = ref(null);
const replyForm = useForm({
    balasan_pengantin: "",
});

const openReplyModal = (komentar) => {
    selectedKomentar.value = komentar;
    replyForm.balasan_pengantin = komentar.balasan_pengantin || "";
    isReplyModalOpen.value = true;
};

const closeReplyModal = () => {
    isReplyModalOpen.value = false;
    selectedKomentar.value = null;
    replyForm.reset();
    replyForm.clearErrors();
};

const submitReply = () => {
    if (!selectedKomentar.value) return;
    replyForm.post(
        route("user.undangan.komentar.reply", {
            undanganId: props.undanganId,
            komentarId: selectedKomentar.value.id,
        }),
        {
            onSuccess: () => {
                closeReplyModal();
            },
            preserveScroll: true,
        },
    );
};

const toggleLike = (komentarId) => {
    router.post(
        route("user.undangan.komentar.like", {
            undanganId: props.undanganId,
            komentarId: komentarId,
        }),
        {},
        {
            preserveScroll: true,
        },
    );
};

const formatDate = (dateString) => {
    if (!dateString) return "";
    const options = {
        day: "numeric",
        month: "short",
        hour: "2-digit",
        minute: "2-digit",
    };
    return new Date(dateString).toLocaleDateString("id-ID", options);
};
</script>

<template>
    <div class="animate-fadeIn">
        <div class="flex flex-col sm:flex-row gap-3 mb-6">
            <div class="relative flex-1">
                <span
                    class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 pointer-events-none"
                >
                    <Icon icon="mdi:magnify" width="18" />
                </span>
                <input
                    v-model="searchKomentar"
                    type="text"
                    placeholder="Cari nama pengirim..."
                    class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-sm focus:border-[#004D31] outline-none bg-white transition-colors"
                />
            </div>
            <div class="relative w-full sm:w-48">
                <span
                    class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 pointer-events-none"
                >
                    <Icon icon="mdi:filter-variant" width="18" />
                </span>
                <select
                    v-model="filterBalasan"
                    class="w-full pl-10 pr-10 py-2 text-sm border border-gray-300 rounded-sm focus:border-[#004D31] outline-none bg-white transition-colors appearance-none"
                >
                    <option value="">Semua Status</option>
                    <option value="sudah">Sudah Dibalas</option>
                    <option value="belum">Belum Dibalas</option>
                </select>
                <span
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 pointer-events-none"
                >
                    <Icon icon="mdi:chevron-down" width="18" />
                </span>
            </div>
        </div>

        <div class="space-y-4">
            <div
                v-for="komentar in filteredKomentar"
                :key="komentar.id"
                class="bg-white px-4 py-5 shadow-sm sm:rounded-lg sm:p-5 border border-gray-200 hover:shadow-md transition"
            >
                <div
                    class="flex flex-col sm:flex-row justify-between items-start mb-2 gap-2"
                >
                    <div>
                        <h4 class="text-base font-bold text-gray-900">
                            {{ komentar.nama }}
                        </h4>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ formatDate(komentar.created_at) }}
                        </p>
                    </div>
                    <div
                        class="flex space-x-3 text-sm w-full sm:w-auto justify-end border-t border-gray-100 sm:border-t-0 pt-3 sm:pt-0 mt-2 sm:mt-0"
                    >
                        <button
                            @click="openReplyModal(komentar)"
                            class="text-blue-600 hover:text-blue-900 font-medium transition ease-in-out"
                        >
                            Balas
                        </button>
                        <button
                            @click="toggleLike(komentar.id)"
                            :class="[
                                komentar.is_liked
                                    ? 'text-pink-600 hover:text-pink-900'
                                    : 'text-gray-500 hover:text-gray-800',
                                'font-medium transition ease-in-out flex items-center gap-1',
                            ]"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                :fill="
                                    komentar.is_liked ? 'currentColor' : 'none'
                                "
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                                />
                            </svg>
                            {{ komentar.is_liked ? "Liked" : "Like" }}
                        </button>
                    </div>
                </div>
                <div class="mt-2 text-sm text-gray-700 leading-relaxed">
                    <p>{{ komentar.pesan }}</p>
                </div>
                <div
                    v-if="komentar.balasan_pengantin"
                    class="mt-3 ml-4 p-3 bg-gray-50 border-l-4 border-blue-400 rounded-r-md"
                >
                    <p class="text-xs font-semibold text-gray-800 mb-1">
                        Balasan Anda:
                    </p>
                    <p class="text-sm text-gray-600">
                        {{ komentar.balasan_pengantin }}
                    </p>
                </div>
            </div>

            <div
                v-if="filteredKomentar.length === 0"
                class="py-8 text-center text-gray-500 border border-dashed border-gray-300 rounded-lg"
            >
                Belum ada komentar atau ucapan yang sesuai.
            </div>
        </div>

        <!-- Reply Modal -->
        <Teleport to="body">
            <transition
                enter-active-class="transition duration-150 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="isReplyModalOpen"
                    class="fixed inset-0 z-100 flex items-center justify-center p-4"
                >
                    <div
                        class="absolute inset-0 bg-gray-900/60"
                        @click="!replyForm.processing && closeReplyModal()"
                    ></div>

                    <div
                        class="relative w-full max-w-md bg-white rounded-sm shadow-lg border border-gray-300"
                    >
                        <form @submit.prevent="submitReply">
                            <div
                                class="bg-gray-100 border-b border-gray-300 px-5 py-3 flex items-center justify-between"
                            >
                                <div
                                    class="flex items-center gap-2 text-gray-800"
                                >
                                    <Icon icon="mdi:reply" width="20" />
                                    <h3 class="text-base font-semibold">
                                        Balas Komentar
                                    </h3>
                                </div>
                                <button
                                    type="button"
                                    @click="closeReplyModal"
                                    class="cursor-pointer text-gray-500 hover:text-gray-800"
                                >
                                    <Icon icon="mdi:close" width="20" />
                                </button>
                            </div>

                            <div class="p-5">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        Balasan Anda
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <textarea
                                        rows="3"
                                        v-model="replyForm.balasan_pengantin"
                                        required
                                        placeholder="Tulis balasan..."
                                        :class="[
                                            'w-full border rounded-sm px-3 py-2 text-sm outline-none transition-colors resize-none',
                                            replyForm.errors.balasan_pengantin
                                                ? 'border-red-500 bg-red-50'
                                                : 'border-gray-400 focus:border-blue-500',
                                        ]"
                                    ></textarea>
                                    <p
                                        v-if="
                                            replyForm.errors.balasan_pengantin
                                        "
                                        class="mt-1 text-xs text-red-600"
                                    >
                                        {{ replyForm.errors.balasan_pengantin }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="bg-gray-50 px-5 py-3 border-t border-gray-300 flex justify-end gap-2"
                            >
                                <button
                                    type="button"
                                    @click="closeReplyModal"
                                    :disabled="replyForm.processing"
                                    class="cursor-pointer px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-400 rounded-sm hover:bg-gray-100 disabled:opacity-50 transition-colors"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    :disabled="replyForm.processing"
                                    class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded-sm hover:bg-blue-700 disabled:opacity-50 transition-colors"
                                >
                                    <Icon
                                        v-if="replyForm.processing"
                                        icon="mdi:loading"
                                        class="animate-spin"
                                        width="16"
                                    />
                                    <Icon v-else icon="mdi:send" width="16" />
                                    Kirim Balasan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </transition>
        </Teleport>
    </div>
</template>

<style scoped>
.animate-fadeIn {
    animation: fadeIn 0.3s ease-in-out;
}
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(5px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
