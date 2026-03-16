<script setup>
import { Icon } from "@iconify/vue";
import { usePage, Link, router, useForm } from "@inertiajs/vue3";
import { ref, computed, onMounted } from "vue";
import UserDashboardLayout from "../../layouts/userDashboardLayout.vue";
import Chart from "chart.js/auto";

const props = defineProps({
    undanganId: [Number, String],
    chartData: Object,
    rsvpData: Array,
    totalHadir: Number,
    komentarData: Array,
    kontakData: Array,
});

const activeTab = ref("rsvp");

const chartRef = ref(null);
onMounted(() => {
    if (chartRef.value) {
        new Chart(chartRef.value.getContext("2d"), {
            type: "bar",
            data: {
                labels: [
                    "RSVP Masuk",
                    "Komentar",
                    "Pengunjung Web",
                    "Kontak WA",
                ],
                datasets: [
                    {
                        label: "Jumlah Interaksi",
                        data: [
                            props.chartData?.rsvp || 0,
                            props.chartData?.komentar || 0,
                            props.chartData?.pengunjung || 0,
                            props.chartData?.kontak || 0,
                        ],
                        backgroundColor: [
                            "rgba(59, 130, 246, 0.8)",
                            "rgba(236, 72, 153, 0.8)",
                            "rgba(16, 185, 129, 0.8)",
                            "rgba(245, 158, 11, 0.8)",
                        ],
                        borderWidth: 0,
                        borderRadius: 4,
                        barPercentage: 0.6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: "#e5e7eb" },
                        border: { display: false },
                    },
                    x: { grid: { display: false }, border: { display: false } },
                },
            },
        });
    }
});

const searchRsvp = ref("");
const filterRsvp = ref("");

const filteredRsvp = computed(() => {
    if (!props.rsvpData) return [];
    return props.rsvpData.filter((item) => {
        const matchName = item.nama
            .toLowerCase()
            .includes(searchRsvp.value.toLowerCase());
        const matchStatus =
            filterRsvp.value === "" || item.kehadiran === filterRsvp.value;
        return matchName && matchStatus;
    });
});

const formatKehadiran = (status) => {
    if (status === "hadir") return "Hadir";
    if (status === "tidak_hadir") return "Tidak Hadir";
    return "Masih Ragu";
};

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
            selectedKontak.value = filteredWa.value.map((k) => k.id);
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
const form = useForm({
    nama: "",
    no_hp: "",
    pesan: "",
});

const openModal = () => {
    form.reset();
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.clearErrors();
};

const submitKontak = () => {
    form.post(route("user.undangan.kontak.store", props.undanganId), {
        onSuccess: () => closeModal(),
        preserveScroll: true,
    });
};

const deleteKontak = (id) => {
    if (confirm("Apakah Anda yakin ingin menghapus kontak ini?")) {
        router.delete(route("user.undangan.kontak.destroy", id), {
            preserveScroll: true,
        });
    }
};

const markAsSent = () => {
    if (selectedKontak.value.length === 0) return;

    router.post(
        route("user.undangan.kontak.markSent", props.undanganId),
        {
            kontak_ids: selectedKontak.value,
        },
        {
            onSuccess: () => {
                selectedKontak.value = [];
            },
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
    <UserDashboardLayout>
        <template #content>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
                <header class="mb-6">
                    <h2
                        class="font-semibold text-xl text-gray-800 leading-tight"
                    >
                        Dashboard Statistik Undangan
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Pantau performa undangan dan kelola tamu Anda dengan
                        mudah.
                    </p>
                </header>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Ringkasan Interaksi
                        </h3>
                        <div class="relative h-60 md:h-72 w-full">
                            <canvas ref="chartRef"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200">
                        <nav
                            class="flex -mb-px overflow-x-auto hide-scrollbar"
                            aria-label="Tabs"
                        >
                            <button
                                @click="activeTab = 'rsvp'"
                                :class="[
                                    activeTab === 'rsvp'
                                        ? 'border-blue-500 text-blue-600'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                    'w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm whitespace-nowrap focus:outline-none transition',
                                ]"
                            >
                                Data RSVP
                            </button>
                            <button
                                @click="activeTab = 'komentar'"
                                :class="[
                                    activeTab === 'komentar'
                                        ? 'border-blue-500 text-blue-600'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                    'w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm whitespace-nowrap focus:outline-none transition',
                                ]"
                            >
                                Komentar
                            </button>
                            <button
                                @click="activeTab = 'wa'"
                                :class="[
                                    activeTab === 'wa'
                                        ? 'border-blue-500 text-blue-600'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                    'w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm whitespace-nowrap focus:outline-none transition',
                                ]"
                            >
                                Bulk Share WA
                            </button>
                        </nav>
                    </div>

                    <div class="p-6">
                        <div
                            v-show="activeTab === 'rsvp'"
                            class="animate-fadeIn"
                        >
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div
                                    class="bg-white p-4 rounded-md shadow-sm border border-gray-200 flex flex-col justify-center"
                                >
                                    <dt
                                        class="text-sm font-medium text-gray-500 truncate"
                                    >
                                        Total RSVP Masuk
                                    </dt>
                                    <dd
                                        class="mt-1 text-3xl font-semibold text-gray-900"
                                    >
                                        {{ rsvpData.length }}
                                    </dd>
                                </div>
                                <div
                                    class="bg-white p-4 rounded-md shadow-sm border border-gray-200 flex flex-col justify-center"
                                >
                                    <dt
                                        class="text-sm font-medium text-gray-500 truncate"
                                    >
                                        Total Orang Hadir
                                    </dt>
                                    <dd
                                        class="mt-1 text-3xl font-semibold text-gray-900"
                                    >
                                        {{ totalHadir }}
                                        <span
                                            class="text-sm font-medium text-gray-500"
                                            >Orang</span
                                        >
                                    </dd>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3 mb-6">
                                <div class="relative flex-1">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                    >
                                        <svg
                                            class="h-5 w-5 text-gray-400"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                            ></path>
                                        </svg>
                                    </div>
                                    <input
                                        v-model="searchRsvp"
                                        type="text"
                                        placeholder="Cari nama tamu..."
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition"
                                    />
                                </div>
                                <select
                                    v-model="filterRsvp"
                                    class="block w-full sm:w-48 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md transition bg-white"
                                >
                                    <option value="">Semua Status</option>
                                    <option value="hadir">Hadir</option>
                                    <option value="tidak_hadir">
                                        Tidak Hadir
                                    </option>
                                    <option value="ragu">Masih Ragu</option>
                                </select>
                            </div>

                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"
                            >
                                <div
                                    v-for="item in filteredRsvp"
                                    :key="item.id"
                                    class="bg-white rounded-lg border border-gray-200 shadow-sm p-5 hover:shadow-md transition"
                                >
                                    <div
                                        class="flex justify-between items-start mb-3"
                                    >
                                        <h4
                                            class="font-bold text-gray-900 text-lg"
                                        >
                                            {{ item.nama }}
                                        </h4>
                                        <span
                                            :class="[
                                                item.kehadiran === 'hadir'
                                                    ? 'bg-green-100 text-green-800'
                                                    : item.kehadiran ===
                                                        'tidak_hadir'
                                                      ? 'bg-red-100 text-red-800'
                                                      : 'bg-yellow-100 text-yellow-800',
                                                'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
                                            ]"
                                        >
                                            {{
                                                formatKehadiran(item.kehadiran)
                                            }}
                                        </span>
                                    </div>
                                    <div
                                        class="text-sm text-gray-600 space-y-2"
                                    >
                                        <p class="flex items-center gap-2">
                                            <svg
                                                class="w-4 h-4 text-gray-400"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                                />
                                            </svg>
                                            {{ item.jumlah_orang }} Orang
                                        </p>
                                        <p class="flex items-center gap-2">
                                            <svg
                                                class="w-4 h-4 text-gray-400"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            {{ formatDate(item.created_at) }}
                                        </p>
                                    </div>
                                </div>

                                <div
                                    v-if="filteredRsvp.length === 0"
                                    class="col-span-full py-8 text-center text-gray-500"
                                >
                                    Tidak ada data RSVP yang sesuai.
                                </div>
                            </div>
                        </div>

                        <div
                            v-show="activeTab === 'komentar'"
                            class="animate-fadeIn"
                        >
                            <div class="mb-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    Kelola Komentar & Ucapan
                                </h3>
                            </div>
                            <div class="space-y-4">
                                <div
                                    v-for="komentar in komentarData"
                                    :key="komentar.id"
                                    class="bg-white px-4 py-5 shadow-sm sm:rounded-lg sm:p-5 border border-gray-200 hover:shadow-md transition"
                                >
                                    <div
                                        class="flex flex-col sm:flex-row justify-between items-start mb-2 gap-2"
                                    >
                                        <div>
                                            <h4
                                                class="text-base font-bold text-gray-900"
                                            >
                                                {{ komentar.nama }}
                                            </h4>
                                            <p
                                                class="text-xs text-gray-500 mt-0.5"
                                            >
                                                {{
                                                    formatDate(
                                                        komentar.created_at,
                                                    )
                                                }}
                                            </p>
                                        </div>
                                        <div
                                            class="flex space-x-3 text-sm w-full sm:w-auto justify-end border-t border-gray-100 sm:border-t-0 pt-3 sm:pt-0 mt-2 sm:mt-0"
                                        >
                                            <button
                                                class="text-blue-600 hover:text-blue-900 font-medium transition ease-in-out"
                                            >
                                                Balas
                                            </button>
                                            <button
                                                class="text-pink-600 hover:text-pink-900 font-medium transition ease-in-out flex items-center gap-1"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4"
                                                    fill="none"
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
                                                Like
                                            </button>
                                        </div>
                                    </div>
                                    <div
                                        class="mt-2 text-sm text-gray-700 leading-relaxed"
                                    >
                                        <p>{{ komentar.pesan }}</p>
                                    </div>
                                </div>

                                <div
                                    v-if="komentarData.length === 0"
                                    class="py-8 text-center text-gray-500 border border-dashed border-gray-300 rounded-lg"
                                >
                                    Belum ada komentar atau ucapan.
                                </div>
                            </div>
                        </div>

                        <div v-show="activeTab === 'wa'" class="animate-fadeIn">
                            <div
                                class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-5"
                            >
                                <h3
                                    class="text-lg font-medium text-gray-900 hidden sm:block"
                                >
                                    Broadcast Undangan
                                </h3>
                                <div class="flex w-full sm:w-auto gap-2">
                                    <button
                                        @click="openModal"
                                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition"
                                    >
                                        + Tambah Kontak
                                    </button>
                                    <button
                                        @click="markAsSent"
                                        :disabled="selectedKontak.length === 0"
                                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 transition gap-2"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"
                                            />
                                        </svg>
                                        Kirim Terpilih ({{
                                            selectedKontak.length
                                        }})
                                    </button>
                                </div>
                            </div>

                            <div
                                class="flex flex-col sm:flex-row gap-3 mb-6 items-center"
                            >
                                <div
                                    class="w-full sm:w-auto flex items-center mb-2 sm:mb-0 mr-4"
                                >
                                    <input
                                        id="selectAll"
                                        type="checkbox"
                                        v-model="selectAll"
                                        class="w-5 h-5 rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500"
                                    />
                                    <label
                                        for="selectAll"
                                        class="ml-2 text-sm font-medium text-gray-700"
                                        >Pilih Semua</label
                                    >
                                </div>
                                <div class="relative flex-1 w-full">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                    >
                                        <svg
                                            class="h-5 w-5 text-gray-400"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                            ></path>
                                        </svg>
                                    </div>
                                    <input
                                        v-model="searchWa"
                                        type="text"
                                        placeholder="Cari nama kontak..."
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm transition"
                                    />
                                </div>
                                <select
                                    v-model="filterWa"
                                    class="block w-full sm:w-48 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md transition bg-white"
                                >
                                    <option value="">Semua Status</option>
                                    <option :value="true">
                                        Sudah Terkirim
                                    </option>
                                    <option :value="false">
                                        Belum Dikirim
                                    </option>
                                </select>
                            </div>

                            <div
                                class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5"
                            >
                                <div
                                    v-for="kontak in filteredWa"
                                    :key="kontak.id"
                                    class="bg-white rounded-lg border border-gray-200 shadow-sm flex flex-col hover:shadow-md transition overflow-hidden"
                                >
                                    <div
                                        class="p-4 flex items-start gap-3 border-b border-gray-50"
                                    >
                                        <div class="pt-1">
                                            <input
                                                type="checkbox"
                                                :value="kontak.id"
                                                v-model="selectedKontak"
                                                class="w-5 h-5 rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500"
                                            />
                                        </div>
                                        <div class="flex-1">
                                            <div
                                                class="flex justify-between items-start"
                                            >
                                                <h4
                                                    class="font-bold text-gray-900 text-base"
                                                >
                                                    {{ kontak.nama }}
                                                </h4>
                                                <span
                                                    :class="[
                                                        kontak.status
                                                            ? 'bg-green-100 text-green-700'
                                                            : 'bg-gray-100 text-gray-600',
                                                        'px-2 py-1 inline-flex text-[10px] leading-4 font-semibold rounded-md',
                                                    ]"
                                                >
                                                    {{
                                                        kontak.status
                                                            ? "Terkirim"
                                                            : "Belum Dikirim"
                                                    }}
                                                </span>
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
                                                        formatNumber(
                                                            kontak.no_hp,
                                                        )
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
                                        <div
                                            v-else
                                            class="text-sm text-gray-400 italic"
                                        >
                                            - Tidak ada pesan custom -
                                        </div>
                                    </div>

                                    <div
                                        class="px-4 py-3 bg-white border-t border-gray-100 flex justify-end gap-3 text-sm font-medium"
                                    >
                                        <button
                                            @click="deleteKontak(kontak.id)"
                                            class="text-red-600 hover:text-red-900 transition flex items-center gap-1"
                                        >
                                            <svg
                                                class="w-4 h-4"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                />
                                            </svg>
                                            Hapus
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
                        </div>
                    </div>
                </div>
            </div>

            <div
                v-if="isModalOpen"
                class="fixed inset-0 z-50 overflow-y-auto"
                aria-labelledby="modal-title"
                role="dialog"
                aria-modal="true"
            >
                <div
                    class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
                >
                    <div
                        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                        @click="closeModal"
                        aria-hidden="true"
                    ></div>
                    <span
                        class="hidden sm:inline-block sm:align-middle sm:h-screen"
                        aria-hidden="true"
                        >&#8203;</span
                    >

                    <div
                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full"
                    >
                        <form @submit.prevent="submitKontak">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div
                                        class="mt-3 text-center sm:mt-0 sm:text-left w-full"
                                    >
                                        <h3
                                            class="text-lg leading-6 font-medium text-gray-900"
                                            id="modal-title"
                                        >
                                            Tambah Kontak WhatsApp
                                        </h3>
                                        <div class="mt-4 space-y-4">
                                            <div>
                                                <label
                                                    class="block font-medium text-sm text-gray-700"
                                                    >Nama Kontak
                                                    <span class="text-red-500"
                                                        >*</span
                                                    ></label
                                                >
                                                <input
                                                    type="text"
                                                    v-model="form.nama"
                                                    required
                                                    class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm sm:text-sm"
                                                    placeholder="Contoh: Budi Santoso"
                                                />
                                                <p
                                                    v-if="form.errors.nama"
                                                    class="text-red-500 text-xs mt-1"
                                                >
                                                    {{ form.errors.nama }}
                                                </p>
                                            </div>
                                            <div>
                                                <label
                                                    class="block font-medium text-sm text-gray-700"
                                                    >No. WhatsApp
                                                    <span class="text-red-500"
                                                        >*</span
                                                    ></label
                                                >
                                                <input
                                                    type="text"
                                                    v-model="form.no_hp"
                                                    required
                                                    class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm sm:text-sm"
                                                    placeholder="Contoh: 08123456789"
                                                />
                                                <p
                                                    class="text-xs text-gray-500 mt-1"
                                                >
                                                    Gunakan format yang valid
                                                    (contoh: 08... atau 628...).
                                                </p>
                                                <p
                                                    v-if="form.errors.no_hp"
                                                    class="text-red-500 text-xs mt-1"
                                                >
                                                    {{ form.errors.no_hp }}
                                                </p>
                                            </div>
                                            <div>
                                                <label
                                                    class="block font-medium text-sm text-gray-700"
                                                    >Pesan Custom
                                                    (Opsional)</label
                                                >
                                                <textarea
                                                    rows="3"
                                                    v-model="form.pesan"
                                                    class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm sm:text-sm resize-none"
                                                    placeholder="Isi pesan khusus untuk kontak ini..."
                                                ></textarea>
                                                <p
                                                    v-if="form.errors.pesan"
                                                    class="text-red-500 text-xs mt-1"
                                                >
                                                    {{ form.errors.pesan }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
                            >
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition disabled:opacity-50"
                                >
                                    Simpan Kontak
                                </button>
                                <button
                                    type="button"
                                    @click="closeModal"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition"
                                >
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </UserDashboardLayout>
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
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
