<script setup>
import LeafletMap from "../../../../components/LeafletMap.vue";

const props = defineProps({
    modelValue: Object,
});

const addAcara = () => {
    props.modelValue.acaras.push({
        nama_acara: "Resepsi",
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
    <v-card
        variant="outlined"
        class="mb-6 rounded-xl overflow-hidden border-gray-200"
    >
        <div
            class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center"
        >
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <v-icon icon="mdi-calendar-multiple" color="orange" size="24" />
                Acara Tambahan (Resepsi, dll)
            </h2>
            <v-btn
                color="orange"
                size="small"
                prepend-icon="mdi-plus"
                @click="addAcara"
            >
                Tambah Acara
            </v-btn>
        </div>
        <v-card-text class="p-6">
            <div
                v-for="(acara, index) in modelValue.acaras"
                :key="index"
                class="mb-8 p-6! border rounded-xl relative bg-gray-50/50"
            >
                <v-btn
                    v-if="modelValue.acaras.length > 1"
                    icon="mdi-delete"
                    color="error"
                    variant="text"
                    class="absolute top-2 right-2"
                    @click="removeAcara(index)"
                />
                <v-row>
                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="acara.nama_acara"
                            label="Nama Acara"
                            variant="outlined"
                            :error-messages="
                                modelValue.errors[`acaras.${index}.nama_acara`]
                            "
                            required
                        />
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-text-field
                            v-model="acara.waktu_acara"
                            type="time"
                            label="Waktu"
                            variant="outlined"
                            :error-messages="
                                modelValue.errors[`acaras.${index}.waktu_acara`]
                            "
                            required
                        />
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-textarea
                            v-model="acara.detail_lokasi_acara"
                            label="Detail Lokasi"
                            variant="outlined"
                            rows="3"
                            :error-messages="
                                modelValue.errors[
                                    `acaras.${index}.detail_lokasi_acara`
                                ]
                            "
                            required
                        />
                    </v-col>
                    <v-col cols="12" md="6">
                        <div class="mb-2 text-sm font-medium">
                            Pilih Lokasi Acara (Opsional)
                        </div>
                        <v-row dense class="mb-2">
                            <v-col cols="6">
                                <v-text-field
                                    v-model.number="acara.lokasi_acara.lat"
                                    label="Latitude"
                                    variant="outlined"
                                    density="compact"
                                    type="number"
                                    step="any"
                                    hide-details="auto"
                                    :error-messages="
                                        modelValue.errors[
                                            `acaras.${index}.lokasi_acara.lat`
                                        ]
                                    "
                                />
                            </v-col>
                            <v-col cols="6">
                                <v-text-field
                                    v-model.number="acara.lokasi_acara.lng"
                                    label="Longitude"
                                    variant="outlined"
                                    density="compact"
                                    type="number"
                                    step="any"
                                    hide-details="auto"
                                    :error-messages="
                                        modelValue.errors[
                                            `acaras.${index}.lokasi_acara.lng`
                                        ]
                                    "
                                />
                            </v-col>
                        </v-row>
                        <LeafletMap v-model="acara.lokasi_acara" />
                    </v-col>
                </v-row>
            </div>
        </v-card-text>
    </v-card>
</template>
