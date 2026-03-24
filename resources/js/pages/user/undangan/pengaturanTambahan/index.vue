<script setup>
import { usePage, Link, router } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import UserDashboardLayout from "../../../layouts/userDashboardLayout.vue";
import Chart from "chart.js/auto";

// Import komponen tab
import RsvpTab from "./rsvpTab.vue";
import KomentarTab from "./komentarTab.vue";
import BulkShareTab from "./bulkShareTab.vue";

const props = defineProps({
    undanganId: [Number, String],
    chartData: Object,
    rsvpData: Array,
    totalHadir: Number,
    komentarData: Array,
    kontakData: Array,
    device: Array,
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
                                        ? 'border-green-500 text-green-600'
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
                                        ? 'border-green-500 text-green-600'
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
                                        ? 'border-green-500 text-green-600'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                    'w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm whitespace-nowrap focus:outline-none transition',
                                ]"
                            >
                                Bulk Share WA
                            </button>
                        </nav>
                    </div>

                    <div class="p-6">
                        <!-- Komponen Tab Data RSVP -->
                        <RsvpTab
                            v-show="activeTab === 'rsvp'"
                            :rsvpData="rsvpData"
                            :totalHadir="totalHadir"
                        />

                        <!-- Komponen Tab Komentar -->
                        <KomentarTab
                            v-show="activeTab === 'komentar'"
                            :komentarData="komentarData"
                            :undanganId="undanganId"
                        />

                        <!-- Komponen Tab Bulk Share WA -->
                        <BulkShareTab
                            v-show="activeTab === 'wa'"
                            :kontakData="kontakData"
                            :undanganId="undanganId"
                            :device="device"
                        />
                    </div>
                </div>
            </div>
        </template>
    </UserDashboardLayout>
</template>

<style scoped>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
