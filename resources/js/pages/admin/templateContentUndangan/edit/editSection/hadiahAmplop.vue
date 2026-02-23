<script setup>
const props = defineProps({
    modelValue: Object,
});
</script>

<template>
    <v-card variant="outlined" class="mt-6 mb-6 rounded-xl border-gray-200">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h2 class="text-lg font-bold flex items-center gap-2">
                <v-icon icon="mdi-gift-outline" color="teal" />
                Hadiah & Amplop Digital
            </h2>
        </div>
        <v-card-text class="p-6">
            <v-row>
                <v-col cols="12" md="6">
                    <v-textarea
                        v-model="modelValue.no_rek_amplop"
                        label="Daftar No. Rekening / E-Wallet (Opsional)"
                        placeholder="Contoh:&#10;BCA: 12345678 a/n Budi&#10;Dana: 0812345678 a/n Budi"
                        rows="4"
                        variant="outlined"
                        :error-messages="modelValue.errors.no_rek_amplop"
                    />
                </v-col>
                <v-col cols="12" md="6">
                    <v-textarea
                        v-model="modelValue.lokasi_pengiriman_kado"
                        label="Alamat Pengiriman Kado (Opsional)"
                        placeholder="Contoh:&#10;Jl. Mawar No. 123, Jakarta Selatan"
                        rows="4"
                        variant="outlined"
                        :error-messages="
                            modelValue.errors.lokasi_pengiriman_kado
                        "
                    />
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>
