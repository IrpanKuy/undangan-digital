<script setup>
import { Icon } from "@iconify/vue";
import { usePage, Link, router, useForm } from "@inertiajs/vue3";
import { ref, computed, onMounted } from "vue";
import UserDashboardLayout from "../../layouts/userDashboardLayout.vue";
import Chart from "chart.js/auto";
import Swal from "sweetalert2";

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
const form = useForm({
    id: null,
    nama: "",
    no_hp: "",
    pesan: "",
});

const openModal = () => {
    form.reset();
    form.clearErrors();
    form.id = null;
    isModalOpen.value = true;
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

const postBulkShare = () => {
    console.log(selectedKontak.value);

    if (selectedKontak.value.length === 0) return;

    // 1. Panggil route TANPA melempar data ke dalamnya
    const url = route("user.bulk.share.wa");

    // 2. Kirim data di argumen KEDUA (sebagai body request)
    router.post(
        url,
        {
            kontaks: selectedKontak.value, // Data dikirim di sini
        },
        {
            // 3. Opsi/Hooks ada di argumen KETIGA
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
            <div
                class="max-w-7xl bg-white mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6"
            >
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
                                                @click="
                                                    openReplyModal(komentar)
                                                "
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
                                                        komentar.is_liked
                                                            ? 'currentColor'
                                                            : 'none'
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
                                                {{
                                                    komentar.is_liked
                                                        ? "Liked"
                                                        : "Like"
                                                }}
                                            </button>
                                        </div>
                                    </div>
                                    <div
                                        class="mt-2 text-sm text-gray-700 leading-relaxed"
                                    >
                                        <p>{{ komentar.pesan }}</p>
                                    </div>
                                    <div
                                        v-if="komentar.balasan_pengantin"
                                        class="mt-3 ml-4 p-3 bg-gray-50 border-l-4 border-blue-400 rounded-r-md"
                                    >
                                        <p
                                            class="text-xs font-semibold text-gray-800 mb-1"
                                        >
                                            Balasan Anda:
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            {{ komentar.balasan_pengantin }}
                                        </p>
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
                                        @click="postBulkShare"
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
                                            @click="openEditModal(kontak)"
                                            class="text-blue-600 hover:text-blue-900 transition flex items-center gap-1"
                                        >
                                            <Icon
                                                icon="mdi:pencil"
                                                width="16"
                                            />
                                            Edit
                                        </button>
                                        <button
                                            @click="deleteKontak(kontak.id)"
                                            class="text-red-600 hover:text-red-900 transition flex items-center gap-1"
                                        >
                                            <Icon
                                                icon="mdi:delete"
                                                width="16"
                                            />
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
                        <!-- Backdrop -->
                        <div
                            class="absolute inset-0 bg-gray-900/60"
                            @click="!form.processing && closeModal()"
                        ></div>

                        <!-- Modal Panel -->
                        <div
                            class="relative w-full max-w-md bg-white rounded-sm shadow-lg border border-gray-300"
                        >
                            <form @submit.prevent="submitKontak">
                                <!-- Header -->
                                <div
                                    class="bg-gray-100 border-b border-gray-300 px-5 py-3 flex items-center justify-between"
                                >
                                    <div
                                        class="flex items-center gap-2 text-gray-800"
                                    >
                                        <Icon
                                            icon="mdi:account-plus"
                                            width="20"
                                        />
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

                                <!-- Body -->
                                <div class="p-5">
                                    <div class="space-y-4">
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                            >
                                                Nama Kontak
                                                <span class="text-red-500"
                                                    >*</span
                                                >
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
                                                <span class="text-red-500"
                                                    >*</span
                                                >
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
                                            <p
                                                class="text-xs text-gray-500 mt-1"
                                            >
                                                Gunakan format yang valid
                                                (contoh: 08... atau 628...).
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

                                <!-- Footer -->
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
                        <!-- Backdrop -->
                        <div
                            class="absolute inset-0 bg-gray-900/60"
                            @click="!replyForm.processing && closeReplyModal()"
                        ></div>

                        <!-- Modal Panel -->
                        <div
                            class="relative w-full max-w-md bg-white rounded-sm shadow-lg border border-gray-300"
                        >
                            <form @submit.prevent="submitReply">
                                <!-- Header -->
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

                                <!-- Body -->
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
                                            v-model="
                                                replyForm.balasan_pengantin
                                            "
                                            required
                                            placeholder="Tulis balasan..."
                                            :class="[
                                                'w-full border rounded-sm px-3 py-2 text-sm outline-none transition-colors resize-none',
                                                replyForm.errors
                                                    .balasan_pengantin
                                                    ? 'border-red-500 bg-red-50'
                                                    : 'border-gray-400 focus:border-blue-500',
                                            ]"
                                        ></textarea>
                                        <p
                                            v-if="
                                                replyForm.errors
                                                    .balasan_pengantin
                                            "
                                            class="mt-1 text-xs text-red-600"
                                        >
                                            {{
                                                replyForm.errors
                                                    .balasan_pengantin
                                            }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Footer -->
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
                                        <Icon
                                            v-else
                                            icon="mdi:send"
                                            width="16"
                                        />
                                        Kirim Balasan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </transition>
            </Teleport>
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
