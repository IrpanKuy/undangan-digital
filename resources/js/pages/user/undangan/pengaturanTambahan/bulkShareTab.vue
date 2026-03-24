<script setup>
import { Icon } from "@iconify/vue";
import { useForm, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import Swal from "sweetalert2";

const props = defineProps({
    kontakData: {
        type: Array,
        default: () => [],
    },
    undanganId: [Number, String],
    device: {
        type: Array,
        default: () => [],
    },
});
console.log("device", props.device);

const searchWa = ref("");
const filterWa = ref("");
const selectedKontak = ref([]);

const filteredWa = computed(() => {
    if (!props.kontakData) return [];
    return props.kontakData.filter((item) => {
        const matchName = item.nama
            .toLowerCase()
            .includes(searchWa.value.toLowerCase());
        const matchStatus =
            filterWa.value === "" || item.status === filterWa.value;
        return matchName && matchStatus;
    });
});

const selectAll = computed({
    get: () =>
        selectedKontak.value.length === filteredWa.value.length &&
        filteredWa.value.length > 0,
    set: (val) => {
        if (val) {
            selectedKontak.value = filteredWa.value.map((k) => ({
                nama: k.nama,
                no_hp: k.no_hp,
                pesan: k.pesan,
            }));
        } else {
            selectedKontak.value = [];
        }
    },
});

const formatNumber = (number) => {
    if (number.startsWith("0")) return "62" + number.substring(1);
    return number;
};

const isModalOpen = ref(false);
const deviceModalOpen = ref(false);
const deviceCreateModalOpen = ref(false);

const form = useForm({
    id: null,
    nama: "",
    no_hp: "",
    pesan: "",
});

const deviceForm = useForm({
    no_hp: "",
});

const openModal = () => {
    form.reset();
    form.clearErrors();
    form.id = null;
    isModalOpen.value = true;
};

const openDeviceModal = () => {
    deviceForm.reset();
    deviceForm.clearErrors();
    deviceModalOpen.value = true;
};

const closeDeviceModal = () => {
    deviceModalOpen.value = false;
    deviceForm.clearErrors();
};

const openCreateDeviceModal = () => {
    deviceForm.reset();
    deviceForm.clearErrors();
    deviceCreateModalOpen.value = true;
};

const closeCreateDeviceModal = () => {
    deviceCreateModalOpen.value = false;
    deviceForm.clearErrors();
};
const openEditModal = (kontak) => {
    form.reset();
    form.clearErrors();
    form.id = kontak.id;
    form.nama = kontak.nama;
    form.no_hp = kontak.no_hp;
    form.pesan = kontak.pesan;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.clearErrors();
};

const submitKontak = () => {
    if (form.id) {
        form.put(route("user.undangan.kontak.update", form.id), {
            onSuccess: () => closeModal(),
            preserveScroll: true,
        });
    } else {
        form.post(route("user.undangan.kontak.store", props.undanganId), {
            onSuccess: () => closeModal(),
            preserveScroll: true,
        });
    }
};

const deleteKontak = (id) => {
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Menghapus kontak ini tidak dapat dibatalkan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("user.undangan.kontak.destroy", id), {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire(
                        "Terhapus!",
                        "Data kontak berhasil dihapus.",
                        "success",
                    );
                },
            });
        }
    });
};

const submitDevice = () => {
    deviceForm.post(route("user.device.store"), {
        onSuccess: () => closeCreateDeviceModal(),
        preserveScroll: true,
    });
};

const postBulkShare = () => {
    if (selectedKontak.value.length === 0) return;

    const url = route("user.bulk.share.wa");

    router.post(
        url,
        {
            kontaks: selectedKontak.value,
        },
        {
            onSuccess: () => {
                selectedKontak.value = [];
            },
            preserveScroll: true,
        },
    );
};
</script>

<template>
    <div class="animate-fadeIn">
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-5"
        >
            <h3 class="text-lg font-bold text-gray-900">Broadcast Undangan</h3>
            <div class="flex w-full sm:w-auto gap-2">
                <button
                    @click="openModal"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-sm font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition"
                >
                    + Tambah Kontak
                </button>
                <button
                    @click="openCreateDeviceModal"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-sm font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition"
                >
                    Device
                </button>
                <button
                    @click="postBulkShare"
                    :disabled="selectedKontak.length === 0"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 bg-[#004D31] border border-transparent rounded-sm font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#003622] disabled:opacity-50 transition gap-2"
                >
                    <Icon icon="mdi:send" width="14" />
                    Kirim ({{ selectedKontak.length }})
                </button>
            </div>
        </div>

        <div
            class="flex flex-col lg:flex-row gap-4 mb-6 lg:items-center bg-gray-50 p-3 rounded-sm border border-gray-200"
        >
            <div
                class="flex items-center shrink-0 pr-4 border-gray-300 lg:border-r"
            >
                <input
                    id="selectAll"
                    type="checkbox"
                    v-model="selectAll"
                    class="w-5 h-5 rounded-sm border-gray-300 text-[#004D31] focus:ring-[#004D31]"
                />
                <label
                    for="selectAll"
                    class="ml-2 text-sm font-bold text-gray-700 whitespace-nowrap"
                    >Pilih Semua</label
                >
            </div>

            <div class="flex flex-col sm:flex-row flex-1 gap-3">
                <div class="relative flex-1">
                    <span
                        class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"
                    >
                        <Icon icon="mdi:magnify" width="18" />
                    </span>
                    <input
                        v-model="searchWa"
                        type="text"
                        placeholder="Cari nama kontak..."
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
                        v-model="filterWa"
                        class="w-full pl-10 pr-10 py-2 text-sm border border-gray-300 rounded-sm focus:border-[#004D31] outline-none bg-white transition-colors appearance-none"
                    >
                        <option value="">Semua Status</option>
                        <option :value="true">Sudah Terkirim</option>
                        <option :value="false">Belum Dikirim</option>
                    </select>
                    <span
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 pointer-events-none"
                    >
                        <Icon icon="mdi:chevron-down" width="18" />
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            <div
                v-for="kontak in filteredWa"
                :key="kontak.id"
                class="bg-white rounded-lg border border-gray-200 shadow-sm flex flex-col hover:shadow-md transition overflow-hidden"
            >
                <div class="p-4 flex items-start gap-3 border-b border-gray-50">
                    <div class="pt-1">
                        <input
                            type="checkbox"
                            :value="{
                                nama: kontak.nama,
                                no_hp: kontak.no_hp,
                                pesan: kontak.pesan,
                            }"
                            v-model="selectedKontak"
                            class="w-5 h-5 rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500"
                        />
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <h4 class="font-bold text-gray-900 text-base">
                                {{ kontak.nama }}
                            </h4>
                        </div>
                        <p
                            class="text-sm text-gray-500 font-medium mt-1 flex items-center gap-1.5"
                        >
                            <svg
                                class="w-4 h-4 text-green-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                />
                            </svg>
                            <a
                                :href="
                                    'https://wa.me/' +
                                    formatNumber(kontak.no_hp)
                                "
                                target="_blank"
                                class="hover:underline"
                                >{{ kontak.no_hp }}</a
                            >
                        </p>
                    </div>
                </div>

                <div class="p-4 flex-1 bg-gray-50/50">
                    <p
                        class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2"
                    >
                        Pesan Tambahan:
                    </p>
                    <div
                        v-if="kontak.pesan"
                        class="text-sm text-gray-700 italic bg-white p-3 rounded border border-gray-100 shadow-sm relative"
                    >
                        <div
                            class="absolute -left-1 top-4 w-2 h-2 bg-white border-l border-b border-gray-100 transform rotate-45"
                        ></div>
                        "{{ kontak.pesan }}"
                    </div>
                    <div v-else class="text-sm text-gray-400 italic">
                        - Tidak ada pesan custom -
                    </div>
                </div>

                <div
                    class="px-4 py-3 bg-white border-t border-gray-100 flex justify-end gap-3 text-sm font-medium"
                >
                    <button
                        @click="openEditModal(kontak)"
                        class="text-blue-600 hover:text-blue-900 transition flex items-center gap-1"
                    >
                        <Icon icon="mdi:pencil" width="16" /> Edit
                    </button>
                    <button
                        @click="deleteKontak(kontak.id)"
                        class="text-red-600 hover:text-red-900 transition flex items-center gap-1"
                    >
                        <Icon icon="mdi:delete" width="16" /> Hapus
                    </button>
                </div>
            </div>

            <div
                v-if="filteredWa.length === 0"
                class="col-span-full py-8 text-center text-gray-500"
            >
                Tidak ada kontak WhatsApp yang ditemukan.
            </div>
        </div>

        <!-- Tambah Kontak Modal -->
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
                    v-if="isModalOpen"
                    class="fixed inset-0 z-100 flex items-center justify-center p-4"
                >
                    <div
                        class="absolute inset-0 bg-gray-900/60"
                        @click="!form.processing && closeModal()"
                    ></div>

                    <div
                        class="relative w-full max-w-md bg-white rounded-sm shadow-lg border border-gray-300"
                    >
                        <form @submit.prevent="submitKontak">
                            <div
                                class="bg-gray-100 border-b border-gray-300 px-5 py-3 flex items-center justify-between"
                            >
                                <div
                                    class="flex items-center gap-2 text-gray-800"
                                >
                                    <Icon icon="mdi:account-plus" width="20" />
                                    <h3 class="text-base font-semibold">
                                        {{
                                            form.id
                                                ? "Edit Kontak WhatsApp"
                                                : "Tambah Kontak WhatsApp"
                                        }}
                                    </h3>
                                </div>
                                <button
                                    type="button"
                                    @click="closeModal"
                                    class="cursor-pointer text-gray-500 hover:text-gray-800"
                                >
                                    <Icon icon="mdi:close" width="20" />
                                </button>
                            </div>

                            <div class="p-5">
                                <div class="space-y-4">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-1"
                                        >
                                            Nama Kontak
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            v-model="form.nama"
                                            required
                                            placeholder="Contoh: Budi Santoso"
                                            :class="[
                                                'w-full border rounded-sm px-3 py-2 text-sm outline-none transition-colors',
                                                form.errors.nama
                                                    ? 'border-red-500 bg-red-50'
                                                    : 'border-gray-400 focus:border-blue-500',
                                            ]"
                                        />
                                        <p
                                            v-if="form.errors.nama"
                                            class="mt-1 text-xs text-red-600"
                                        >
                                            {{ form.errors.nama }}
                                        </p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-1"
                                        >
                                            No. WhatsApp
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            v-model="form.no_hp"
                                            required
                                            placeholder="Contoh: 08123456789"
                                            :class="[
                                                'w-full border rounded-sm px-3 py-2 text-sm outline-none transition-colors',
                                                form.errors.no_hp
                                                    ? 'border-red-500 bg-red-50'
                                                    : 'border-gray-400 focus:border-blue-500',
                                            ]"
                                        />
                                        <p class="text-xs text-gray-500 mt-1">
                                            Gunakan format yang valid (contoh:
                                            08... atau 628...).
                                        </p>
                                        <p
                                            v-if="form.errors.no_hp"
                                            class="mt-1 text-xs text-red-600"
                                        >
                                            {{ form.errors.no_hp }}
                                        </p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-1"
                                        >
                                            Pesan Custom (Opsional)
                                        </label>
                                        <textarea
                                            rows="3"
                                            v-model="form.pesan"
                                            placeholder="Isi pesan khusus untuk kontak ini..."
                                            :class="[
                                                'w-full border rounded-sm px-3 py-2 text-sm outline-none transition-colors resize-none',
                                                form.errors.pesan
                                                    ? 'border-red-500 bg-red-50'
                                                    : 'border-gray-400 focus:border-blue-500',
                                            ]"
                                        ></textarea>
                                        <p
                                            v-if="form.errors.pesan"
                                            class="mt-1 text-xs text-red-600"
                                        >
                                            {{ form.errors.pesan }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-gray-50 px-5 py-3 border-t border-gray-300 flex justify-end gap-2"
                            >
                                <button
                                    type="button"
                                    @click="closeModal"
                                    :disabled="form.processing"
                                    class="cursor-pointer px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-400 rounded-sm hover:bg-gray-100 disabled:opacity-50 transition-colors"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded-sm hover:bg-blue-700 disabled:opacity-50 transition-colors"
                                >
                                    <Icon
                                        v-if="form.processing"
                                        icon="mdi:loading"
                                        class="animate-spin"
                                        width="16"
                                    />
                                    <Icon
                                        v-else
                                        icon="mdi:content-save"
                                        width="16"
                                    />
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </transition>
        </Teleport>

        <!-- Tambah device -->
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
                    v-if="deviceCreateModalOpen"
                    class="fixed inset-0 z-100 flex items-center justify-center p-4"
                >
                    <div
                        class="absolute inset-0 bg-gray-900/60"
                        @click="!form.processing && closeCreateDeviceModal()"
                    ></div>

                    <div
                        class="relative w-full max-w-md bg-white rounded-sm shadow-lg border border-gray-300"
                    >
                        <form @submit.prevent="submitDevice">
                            <div
                                class="bg-gray-100 border-b border-gray-300 px-5 py-3 flex items-center justify-between"
                            >
                                <div
                                    class="flex items-center gap-2 text-gray-800"
                                >
                                    <Icon icon="mdi:account-plus" width="20" />
                                    <h3 class="text-base font-semibold">
                                        Tambah Device
                                    </h3>
                                </div>
                                <button
                                    type="button"
                                    @click="closeModal"
                                    class="cursor-pointer text-gray-500 hover:text-gray-800"
                                >
                                    <Icon icon="mdi:close" width="20" />
                                </button>
                            </div>

                            <div class="p-5">
                                <div class="space-y-4">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-1"
                                        >
                                            No Hp
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            v-model="deviceForm.no_hp"
                                            required
                                            placeholder="Contoh: 08123456789"
                                            :class="[
                                                'w-full border rounded-sm px-3 py-2 text-sm outline-none transition-colors',
                                                deviceForm.errors.no_hp
                                                    ? 'border-red-500 bg-red-50'
                                                    : 'border-gray-400 focus:border-blue-500',
                                            ]"
                                        />
                                        <p class="text-xs text-gray-500 mt-1">
                                            Gunakan format diawali 0, contoh:
                                            08123456789.
                                        </p>
                                        <p
                                            v-if="deviceForm.errors.no_hp"
                                            class="mt-1 text-xs text-red-600"
                                        >
                                            {{ deviceForm.errors.no_hp }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-gray-50 px-5 py-3 border-t border-gray-300 flex justify-end gap-2"
                            >
                                <button
                                    type="button"
                                    @click="closeCreateDeviceModal"
                                    :disabled="form.processing"
                                    class="cursor-pointer px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-400 rounded-sm hover:bg-gray-100 disabled:opacity-50 transition-colors"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded-sm hover:bg-blue-700 disabled:opacity-50 transition-colors"
                                >
                                    <Icon
                                        v-if="form.processing"
                                        icon="mdi:loading"
                                        class="animate-spin"
                                        width="16"
                                    />
                                    <Icon
                                        v-else
                                        icon="mdi:content-save"
                                        width="16"
                                    />
                                    Simpan
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
