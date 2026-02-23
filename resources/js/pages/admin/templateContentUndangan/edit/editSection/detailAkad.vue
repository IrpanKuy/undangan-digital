<script setup>
import LeafletMap from "../../../../../components/LeafletMap.vue";

const props = defineProps({
    modelValue: Object,
});
</script>

<template>
    <v-card variant="outlined" class="mb-6 rounded-xl border-gray-200">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h2 class="text-lg font-bold flex items-center gap-2">
                <v-icon icon="mdi-church" color="primary" />
                Detail Akad Nikah
            </h2>
        </div>
        <v-card-text class="p-6">
            <v-row>
                <v-col cols="12" md="4">
                    <v-text-field
                        v-model="modelValue.tanggal_mulai_akad"
                        type="date"
                        label="Tanggal Akad"
                        variant="outlined"
                        prepend-inner-icon="mdi-calendar"
                        :error-messages="modelValue.errors.tanggal_mulai_akad"
                        required
                    />
                </v-col>
                <v-col cols="12" md="4">
                    <v-text-field
                        v-model="modelValue.waktu_mulai_akad"
                        type="time"
                        label="Waktu Mulai"
                        variant="outlined"
                        prepend-inner-icon="mdi-clock-outline"
                        :error-messages="modelValue.errors.waktu_mulai_akad"
                        required
                    />
                </v-col>
                <v-col cols="12" md="4">
                    <v-text-field
                        v-model="modelValue.waktu_selesai_akad"
                        type="time"
                        label="Waktu Selesai (Opsional)"
                        variant="outlined"
                        prepend-inner-icon="mdi-clock-check-outline"
                        :error-messages="modelValue.errors.waktu_selesai_akad"
                    />
                </v-col>
                <v-col cols="12" md="6">
                    <v-textarea
                        v-model="modelValue.detail_lokasi_akad_nikah"
                        label="Detail Lokasi Akad"
                        variant="outlined"
                        rows="3"
                        :error-messages="
                            modelValue.errors.detail_lokasi_akad_nikah
                        "
                        required
                    />
                </v-col>
                <v-col cols="12" md="6">
                    <div class="mb-2 text-sm font-medium">
                        Pilih Lokasi di Peta (Opsional)
                    </div>
                    <LeafletMap v-model="modelValue.lokasi_akad_nikah" />
                    <div class="mt-2 text-xs text-gray-500">
                        Lat: {{ modelValue.lokasi_akad_nikah.lat.toFixed(6) }},
                        Lng: {{ modelValue.lokasi_akad_nikah.lng.toFixed(6) }}
                    </div>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12" md="6">
                    <v-textarea
                        v-model="modelValue.doa_pengantinn_pria"
                        label="Doa untuk Pengantin Pria"
                        variant="outlined"
                        rows="2"
                        :error-messages="modelValue.errors.doa_pengantinn_pria"
                        required
                    />
                </v-col>
                <v-col cols="12" md="6">
                    <v-textarea
                        v-model="modelValue.doa_pengantin_wanita"
                        label="Doa untuk Pengantin Wanita"
                        variant="outlined"
                        rows="2"
                        :error-messages="modelValue.errors.doa_pengantin_wanita"
                        required
                    />
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>
