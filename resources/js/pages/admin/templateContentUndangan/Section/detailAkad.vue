<script setup>
import LeafletMap from "../../../../components/LeafletMap.vue";

const props = defineProps({
    modelValue: Object,
});
</script>

<template>
    <v-card
        variant="outlined"
        class="mb-6 rounded-xl overflow-hidden border-gray-200"
    >
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <v-icon icon="mdi-church" color="indigo" size="24" />
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
                        placeholder="Contoh: Gedung Serbaguna, Jl. Merdeka No. 1"
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
                    <v-row dense class="mb-2">
                        <v-col cols="6">
                            <v-text-field
                                v-model.number="
                                    modelValue.lokasi_akad_nikah.lat
                                "
                                label="Latitude"
                                variant="outlined"
                                density="compact"
                                type="number"
                                step="any"
                                hide-details="auto"
                                :error-messages="
                                    modelValue.errors['lokasi_akad_nikah.lat']
                                "
                            />
                        </v-col>
                        <v-col cols="6">
                            <v-text-field
                                v-model.number="
                                    modelValue.lokasi_akad_nikah.lng
                                "
                                label="Longitude"
                                variant="outlined"
                                density="compact"
                                type="number"
                                step="any"
                                hide-details="auto"
                                :error-messages="
                                    modelValue.errors['lokasi_akad_nikah.lng']
                                "
                            />
                        </v-col>
                    </v-row>
                    <LeafletMap v-model="modelValue.lokasi_akad_nikah" />
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>
