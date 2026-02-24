<script setup>
import { Icon } from "@iconify/vue";
import LeafletMap from "../../../../components/LeafletMap.vue";

const props = defineProps({
    modelValue: Object,
});

const emit = defineEmits(["update:modelValue"]);

const addAcara = () => {
    props.modelValue.acaras.push({
        nama_acara: "",
        waktu_acara: "",
        detail_lokasi_acara: "",
        lokasi_acara: { lat: -6.2088, lng: 106.8456 },
    });
};

const removeAcara = (index) => {
    props.modelValue.acaras.splice(index, 1);
};
</script>

<template>
    <div class="bg-white border border-gray-300 rounded-sm">
        <div
            class="bg-gray-100 border-b border-gray-300 px-5 py-3 flex items-center justify-between"
        >
            <div class="flex items-center gap-2">
                <Icon
                    icon="mdi:calendar-plus"
                    width="20"
                    class="text-gray-700"
                />
                <h3
                    class="font-bold text-gray-800 uppercase tracking-tight text-sm"
                >
                    Acara Tambahan
                </h3>
            </div>
            <button
                type="button"
                @click="addAcara"
                class="cursor-pointer text-xs font-bold text-[#004D31] bg-[#004D31]/10 hover:bg-[#004D31]/20 px-3 py-1.5 rounded-sm transition-colors border border-[#004D31]/30 flex items-center gap-1"
            >
                <Icon icon="mdi:plus" /> Tambah Acara
            </button>
        </div>

        <div class="p-6">
            <div
                v-if="modelValue.acaras.length === 0"
                class="text-center py-6 border border-dashed border-gray-300 rounded-sm bg-gray-50 text-gray-500 text-sm"
            >
                Belum ada acara tambahan.
            </div>

            <div class="space-y-6">
                <div
                    v-for="(acara, index) in modelValue.acaras"
                    :key="index"
                    class="p-5 border border-gray-200 bg-gray-50 rounded-sm relative"
                >
                    <!-- Delete Button -->
                    <button
                        @click="removeAcara(index)"
                        type="button"
                        title="Hapus Acara"
                        class="absolute top-3 right-3 text-red-500 hover:text-white bg-red-100 hover:bg-red-600 p-1.5 rounded-sm cursor-pointer transition-colors"
                    >
                        <Icon icon="mdi:trash-can-outline" width="16" />
                    </button>

                    <h4
                        class="font-bold text-sm text-[#004D31] uppercase border-b border-gray-300 pb-2 mb-4"
                    >
                        Acara #{{ index + 1 }}
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-700 uppercase mb-1"
                                >Nama Acara</label
                            >
                            <input
                                v-model="acara.nama_acara"
                                type="text"
                                class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                                placeholder="Contoh: Resepsi / Ngunduh Mantu"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-700 uppercase mb-1"
                                >Waktu Acara</label
                            >
                            <input
                                v-model="acara.waktu_acara"
                                type="time"
                                class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                                placeholder="Contoh: 10:00 - Selesai"
                            />
                        </div>
                    </div>

                    <div class="mb-4">
                        <label
                            class="block text-xs font-bold text-gray-700 uppercase mb-1"
                            >Detail Lokasi</label
                        >
                        <textarea
                            v-model="acara.detail_lokasi_acara"
                            rows="2"
                            class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                            placeholder="Alamat lengkap acara..."
                        ></textarea>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-gray-700 uppercase mb-1"
                            >Pin Lokasi Peta</label
                        >
                        <div
                            class="border border-gray-400 rounded-sm overflow-hidden h-64"
                        >
                            <LeafletMap v-model="acara.lokasi_acara" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
