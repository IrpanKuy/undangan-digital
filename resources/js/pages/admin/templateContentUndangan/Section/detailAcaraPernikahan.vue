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
                Detail Acara Pernikahan
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1"
                        >Nama Prosesi</label
                    >
                    <input
                        v-model="modelValue.nama_prosesi"
                        type="text"
                        placeholder="Contoh: Akad Nikah"
                        class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                    />
                    <p class="text-[10px] text-gray-500 mt-1">
                        Nama acara pernikahan sesuai kepercayaaan
                    </p>
                    <p
                        v-if="modelValue.errors.nama_prosesi"
                        class="text-[10px] text-red-600 mt-1 font-bold"
                    >
                        {{ modelValue.errors.nama_prosesi }}
                    </p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1"
                        >Tanggal Pernikahan</label
                    >
                    <input
                        v-model="modelValue.tanggal_mulai"
                        type="date"
                        class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                    />
                    <p
                        v-if="modelValue.errors.tanggal_mulai"
                        class="text-[10px] text-red-600 mt-1 font-bold"
                    >
                        {{ modelValue.errors.tanggal_mulai }}
                    </p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1"
                        >Waktu Mulai</label
                    >
                    <input
                        v-model="modelValue.waktu_mulai"
                        type="time"
                        class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                    />
                    <p
                        v-if="modelValue.errors.waktu_mulai"
                        class="text-[10px] text-red-600 mt-1 font-bold"
                    >
                        {{ modelValue.errors.waktu_mulai }}
                    </p>
                </div>
                <div>
                    <label
                        class="block text-[10px] font-bold text-gray-700 mb-1"
                        >Waktu Selesai
                        <span class="text-gray-400 font-normal normal-case"
                            >(opsional)</span
                        ></label
                    >
                    <input
                        v-model="modelValue.waktu_selesai"
                        type="time"
                        class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                    />
                    <p class="text-[10px] text-gray-500 mt-1">
                        kosongkan jika waktu selesai tidak pasti
                    </p>
                    <p
                        v-if="modelValue.errors.waktu_selesai"
                        class="text-[10px] text-red-600 mt-1 font-bold"
                    >
                        {{ modelValue.errors.waktu_selesai }}
                    </p>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1"
                    >Detail Lokasi</label
                >
                <textarea
                    v-model="modelValue.detail_lokasi_nikah"
                    rows="2"
                    class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                    placeholder="Nama gedung, jalan, blok, dll..."
                ></textarea>
                <p
                    v-if="modelValue.errors.detail_lokasi_nikah"
                    class="text-[10px] text-red-600 mt-1 font-bold"
                >
                    {{ modelValue.errors.detail_lokasi_nikah }}
                </p>
            </div>

            <div>
                <div class="flex mb-3 pb-2 border-b border-gray-200">
                    <label class="block text-xs font-bold text-gray-700">
                        Lokasi Peta
                    </label>
                </div>

                <div class="space-y-3 transition-all duration-300">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="items-center cursor-pointer">
                                <label
                                    class="block text-xs font-bold text-gray-700 mb-1"
                                    >Tampilkan Peta Undangan</label
                                >
                                <input
                                    type="checkbox"
                                    v-model="modelValue.show_map"
                                    class="sr-only peer"
                                />
                                <div
                                    class="relative w-9 h-5 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:inset-s-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#004D31]"
                                ></div>
                            </label>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label
                                class="block text-xs font-bold text-gray-700 mb-1"
                                >Zoom:</label
                            >
                            <input
                                v-model.number="modelValue.lokasi_nikah.zoom"
                                type="number"
                                min="1"
                                max="19"
                                class="border border-gray-300 rounded-sm px-2 py-1.5 text-xs outline-none focus:border-[#004D31] w-full"
                                placeholder="Zoom"
                            />
                        </div>

                        <div class="flex flex-col gap-1">
                            <label
                                class="block text-xs font-bold text-gray-700 mb-1"
                                >Latitude:</label
                            >
                            <input
                                v-model.number="modelValue.lokasi_nikah.lat"
                                type="number"
                                step="any"
                                class="border border-gray-300 rounded-sm px-2 py-1.5 text-xs outline-none focus:border-[#004D31] w-full"
                                placeholder="Latitude"
                            />
                        </div>
                        <div class="flex flex-col gap-1">
                            <label
                                class="block text-xs font-bold text-gray-700 mb-1"
                                >Longitude:</label
                            >
                            <input
                                v-model.number="modelValue.lokasi_nikah.lng"
                                type="number"
                                step="any"
                                class="border border-gray-300 rounded-sm px-2 py-1.5 text-xs outline-none focus:border-[#004D31] w-full"
                                placeholder="Longitude"
                            />
                        </div>
                    </div>

                    <div
                        class="border border-gray-400 rounded-sm overflow-hidden h-72 relative z-0"
                    >
                        <LeafletMap v-model="modelValue.lokasi_nikah" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
