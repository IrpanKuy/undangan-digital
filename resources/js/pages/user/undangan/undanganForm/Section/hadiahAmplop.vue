<script setup>
import { Icon } from "@iconify/vue";

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
            <Icon icon="mdi:wallet-giftcard" width="20" class="text-gray-700" />
            <h3
                class="font-bold text-gray-800 uppercase tracking-tight text-sm"
            >
                Hadiah & Amplop Digital
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Rekening Bank -->
                <div>
                    <label
                        class="text-xs font-bold text-gray-700 uppercase mb-1 flex items-center gap-1"
                    >
                        <Icon icon="mdi:bank-outline" /> Nomor Rekening /
                        E-Wallet
                    </label>
                    <textarea
                        v-model="modelValue.no_rek_amplop"
                        rows="4"
                        class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                        placeholder="Contoh:&#10;BCA 123456789 a/n Nama&#10;Dana 0812345678 a/n Nama"
                    ></textarea>
                    <p class="text-[10px] text-gray-500 mt-1">
                        Gunakan baris baru (Enter) untuk menambahkan rekening
                        lebih dari satu.
                    </p>
                </div>

                <!-- Alamat Kado -->
                <div>
                    <label
                        class="text-xs font-bold text-gray-700 uppercase mb-1 flex items-center gap-1"
                    >
                        <Icon icon="mdi:package-variant-closed" /> Alamat
                        Pengiriman Kado Fisik
                    </label>
                    <textarea
                        v-model="modelValue.lokasi_pengiriman_kado"
                        rows="4"
                        class="w-full border border-gray-400 rounded-sm px-3 py-2 text-sm outline-none focus:border-[#004D31]"
                        placeholder="Alamat lengkap penerima kado..."
                    ></textarea>
                </div>
            </div>
        </div>
    </div>
</template>
