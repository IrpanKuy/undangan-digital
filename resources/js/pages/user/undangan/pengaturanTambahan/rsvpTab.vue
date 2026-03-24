<script setup>
import { Icon } from "@iconify/vue";
import { ref, computed } from "vue";

const props = defineProps({
    rsvpData: {
        type: Array,
        default: () => [],
    },
    totalHadir: {
        type: Number,
        default: 0,
    },
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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div
                class="bg-linear-to-br from-yellow-400 to-yellow-600 p-4 rounded-md shadow-sm border border-gray-200 flex flex-col justify-center"
            >
                <dt class="text-sm font-medium text-white truncate">
                    Total RSVP Masuk
                </dt>
                <dd class="mt-1 text-3xl font-semibold text-white">
                    {{ rsvpData.length }}
                </dd>
            </div>
            <div
                class="bg-linear-to-br from-green-400 to-green-600 p-4 rounded-md shadow-sm border border-gray-200 flex flex-col justify-center"
            >
                <dt class="text-sm font-medium text-white truncate">
                    Total Orang Hadir
                </dt>
                <dd class="mt-1 text-3xl font-semibold text-white">
                    {{ totalHadir }}
                    <span class="text-sm font-medium text-white">Orang</span>
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
                <div class="relative min-w-[240px]">
                    <span
                        class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"
                    >
                        <Icon icon="mdi:magnify" width="18" />
                    </span>
                    <input
                        v-model="searchRsvp"
                        type="text"
                        placeholder="Cari nama tamu..."
                        class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-sm focus:border-[#004D31] outline-none bg-white transition-colors"
                    />
                </div>
            </div>
            <div class="relative w-full sm:w-48">
                <span
                    class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 pointer-events-none"
                >
                    <Icon icon="mdi:filter-variant" width="18" />
                </span>

                <select
                    v-model="filterRsvp"
                    class="w-full pl-10 pr-10 py-2 text-sm border border-gray-300 rounded-sm focus:border-[#004D31] outline-none bg-white transition-colors appearance-none"
                >
                    <option value="">Semua Status</option>
                    <option value="hadir">Hadir</option>
                    <option value="tidak_hadir">Tidak Hadir</option>
                    <option value="ragu">Masih Ragu</option>
                </select>

                <span
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 pointer-events-none"
                >
                    <Icon icon="mdi:chevron-down" width="18" />
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div
                v-for="item in filteredRsvp"
                :key="item.id"
                class="bg-white rounded-lg border border-gray-200 shadow-sm p-5 hover:shadow-md transition"
            >
                <div class="flex justify-between items-start mb-3">
                    <h4 class="font-bold text-gray-900 text-lg">
                        {{ item.nama }}
                    </h4>
                    <span
                        :class="[
                            item.kehadiran === 'hadir'
                                ? 'bg-green-500 text-white'
                                : item.kehadiran === 'tidak_hadir'
                                  ? 'bg-red-500 text-white'
                                  : 'bg-yellow-500 text-white',
                            'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
                        ]"
                    >
                        {{ formatKehadiran(item.kehadiran) }}
                    </span>
                </div>
                <div class="text-sm text-gray-600 space-y-2.5">
                    <p class="flex items-center gap-2">
                        <svg
                            class="w-4 h-4 text-[#004D31]"
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
                        <span class="text-gray-500">Jumlah Hadir:</span>
                        <span class="font-semibold text-gray-800"
                            >{{ item.jumlah_hadir }} Orang</span
                        >
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
                        <span class="text-gray-500">Dikirim pada:</span>
                        <span class="text-gray-700">{{
                            formatDate(item.created_at)
                        }}</span>
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
