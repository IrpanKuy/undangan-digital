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
        tanggal_acara: "",
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
                class="flex flex-col gap-2 py-6 border border-dashed border-gray-300 rounded-sm bg-gray-50"
            >
                <p class="text-center text-gray-500 text-sm">
                    Tambah acara jika ada acara tambahan.
                </p>
                <button
                    type="button"
                    @click="addAcara"
                    class="cursor-pointer mx-auto text-xs font-bold text-[#004D31] bg-[#004D31]/10 hover:bg-[#004D31]/20 px-3 py-1.5 rounded-sm transition-colors border border-[#004D31]/30 flex items-center gap-1"
                >
                    <Icon icon="mdi:plus" /> Tambah Acara
                </button>
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
                        class="font-bold text-sm text-[#004D31] border-b border-gray-300 pb-2 mb-4"
                    >
                        Acara #{{ index + 1 }}
                    </h4>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-700 mb-1"
                                >Nama Acara</label
                            >
                            <input
                                v-model="acara.nama_acara"
                                type="text"
                                class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                                placeholder="Contoh: Resepsi"
                            />
                            <p
                                v-if="
                                    modelValue.errors &&
                                    modelValue.errors[
                                        `acaras.${index}.nama_acara`
                                    ]
                                "
                                class="text-[10px] text-red-600 mt-1 font-bold"
                            >
                                {{
                                    modelValue.errors[
                                        `acaras.${index}.nama_acara`
                                    ]
                                }}
                            </p>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-700 mb-1"
                                >Tanggal Acara</label
                            >
                            <input
                                v-model="acara.tanggal_acara"
                                type="date"
                                class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                            />
                            <p
                                v-if="
                                    modelValue.errors &&
                                    modelValue.errors[
                                        `acaras.${index}.tanggal_acara`
                                    ]
                                "
                                class="text-[10px] text-red-600 mt-1 font-bold"
                            >
                                {{
                                    modelValue.errors[
                                        `acaras.${index}.tanggal_acara`
                                    ]
                                }}
                            </p>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-700 mb-1"
                                >Waktu Mulai Acara</label
                            >
                            <input
                                v-model="acara.waktu_mulai_acara"
                                type="time"
                                class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                            />
                            <p
                                v-if="
                                    modelValue.errors &&
                                    modelValue.errors[
                                        `acaras.${index}.waktu_mulai_acara`
                                    ]
                                "
                                class="text-[10px] text-red-600 mt-1 font-bold"
                            >
                                {{
                                    modelValue.errors[
                                        `acaras.${index}.waktu_mulai_acara`
                                    ]
                                }}
                            </p>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-700 mb-1"
                                >Waktu Selesai Acara</label
                            >
                            <input
                                v-model="acara.waktu_selesai_acara"
                                type="time"
                                class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                            />
                            <p
                                v-if="
                                    modelValue.errors &&
                                    modelValue.errors[
                                        `acaras.${index}.waktu_selesai_acara`
                                    ]
                                "
                                class="text-[10px] text-red-600 mt-1 font-bold"
                            >
                                {{
                                    modelValue.errors[
                                        `acaras.${index}.waktu_selesai_acara`
                                    ]
                                }}
                            </p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label
                            class="block text-xs font-bold text-gray-700 mb-1"
                            >Detail Lokasi</label
                        >
                        <textarea
                            v-model="acara.detail_lokasi_acara"
                            rows="2"
                            class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31] bg-white"
                            placeholder="Alamat lengkap acara..."
                        ></textarea>
                        <p
                            v-if="
                                modelValue.errors &&
                                modelValue.errors[
                                    `acaras.${index}.detail_lokasi_acara`
                                ]
                            "
                            class="text-[10px] text-red-600 mt-1 font-bold"
                        >
                            {{
                                modelValue.errors[
                                    `acaras.${index}.detail_lokasi_acara`
                                ]
                            }}
                        </p>
                    </div>

                    <div>
                        <div class="flex mb-3 pb-2 border-b border-gray-200">
                            <label
                                class="block text-xs font-bold text-gray-700 uppercase"
                            >
                                Lokasi Peta
                            </label>
                        </div>

                        <div
                            v-if="modelValue.show_map"
                            class="space-y-3 transition-all duration-300"
                        >
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="items-center cursor-pointer">
                                        <p
                                            class="text-[10px] mb-1 font-bold text-gray-600"
                                        >
                                            Tampilkan Peta
                                        </p>
                                        <input
                                            type="checkbox"
                                            v-model="modelValue.tampilkan_peta"
                                            class="sr-only peer"
                                        />
                                        <div
                                            class="relative w-9 h-5 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:inset-s-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#004D31]"
                                        ></div>
                                    </label>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label
                                        class="text-[10px] font-bold text-gray-500 uppercase"
                                        >Zoom:</label
                                    >
                                    <input
                                        v-model.number="
                                            modelValue.lokasi_nikah.zoom
                                        "
                                        type="number"
                                        min="1"
                                        max="19"
                                        class="border border-gray-300 rounded-sm px-2 py-1.5 text-xs outline-none focus:border-[#004D31] w-full"
                                        placeholder="Zoom"
                                    />
                                </div>

                                <div class="flex flex-col gap-1">
                                    <label
                                        class="text-[10px] font-bold text-gray-500 uppercase"
                                        >Latitude:</label
                                    >
                                    <input
                                        v-model.number="
                                            modelValue.lokasi_nikah.lat
                                        "
                                        type="number"
                                        step="any"
                                        class="border border-gray-300 rounded-sm px-2 py-1.5 text-xs outline-none focus:border-[#004D31] w-full"
                                        placeholder="Latitude"
                                    />
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label
                                        class="text-[10px] font-bold text-gray-500 uppercase"
                                        >Longitude:</label
                                    >
                                    <input
                                        v-model.number="
                                            modelValue.lokasi_nikah.lng
                                        "
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
        </div>
    </div>
</template>
