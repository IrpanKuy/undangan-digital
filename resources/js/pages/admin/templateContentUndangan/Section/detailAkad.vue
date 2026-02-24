<script setup>
import { Icon } from "@iconify/vue";
import LeafletMap from "../../../../components/LeafletMap.vue";

const props = defineProps({
    modelValue: Object,
});

const emit = defineEmits(["update:modelValue"]);
</script>

<template>
    <div class="bg-white border border-gray-300 rounded-sm">
        <div
            class="bg-gray-100 border-b border-gray-300 px-5 py-3 flex items-center gap-2"
        >
            <Icon icon="mdi:ring" width="20" class="text-gray-700" />
            <h3
                class="font-bold text-gray-800 uppercase tracking-tight text-sm"
            >
                Detail Akad Nikah
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label
                        class="block text-xs font-bold text-gray-700 uppercase mb-1"
                        >Tanggal Akad</label
                    >
                    <input
                        v-model="modelValue.tanggal_mulai_akad"
                        type="date"
                        class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                    />
                </div>
                <div>
                    <label
                        class="block text-xs font-bold text-gray-700 uppercase mb-1"
                        >Waktu Mulai</label
                    >
                    <input
                        v-model="modelValue.waktu_mulai_akad"
                        type="time"
                        class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                    />
                </div>
                <div>
                    <label
                        class="block text-xs font-bold text-gray-700 uppercase mb-1"
                        >Waktu Selesai</label
                    >
                    <input
                        v-model="modelValue.waktu_selesai_akad"
                        type="time"
                        class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label
                        class="block text-xs font-bold text-gray-700 uppercase mb-1"
                        >Doa Pengantin Pria (Opsional)</label
                    >
                    <textarea
                        v-model="modelValue.doa_pengantinn_pria"
                        rows="3"
                        class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                        placeholder="Doa untuk mempelai pria..."
                    ></textarea>
                </div>
                <div>
                    <label
                        class="block text-xs font-bold text-gray-700 uppercase mb-1"
                        >Doa Pengantin Wanita (Opsional)</label
                    >
                    <textarea
                        v-model="modelValue.doa_pengantin_wanita"
                        rows="3"
                        class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                        placeholder="Doa untuk mempelai wanita..."
                    ></textarea>
                </div>
            </div>

            <div>
                <label
                    class="block text-xs font-bold text-gray-700 uppercase mb-1"
                    >Detail Lokasi</label
                >
                <textarea
                    v-model="modelValue.detail_lokasi_akad_nikah"
                    rows="2"
                    class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                    placeholder="Nama gedung, jalan, blok, dll..."
                ></textarea>
            </div>

            <div>
                <label
                    class="block text-xs font-bold text-gray-700 uppercase mb-1"
                    >Pin Lokasi Peta</label
                >
                <div
                    class="border border-gray-400 rounded-sm overflow-hidden h-72"
                >
                    <LeafletMap v-model="modelValue.lokasi_akad_nikah" />
                </div>
            </div>
        </div>
    </div>
</template>
