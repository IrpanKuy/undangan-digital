<script setup>
import { VFileUpload } from "vuetify/labs/VFileUpload";
const props = defineProps({
    modelValue: Object,
});

const addKisahCinta = () => {
    props.modelValue.kisah_cintas.push({
        tanggal: "",
        peristiwa: "",
        foto: null,
    });
};

const removeKisahCinta = (index) => {
    props.modelValue.kisah_cintas.splice(index, 1);
};
</script>

<template>
    <v-card
        variant="outlined"
        class="h-full rounded-xl overflow-hidden border-gray-200 mb-6"
    >
        <div
            class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center"
        >
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <v-icon icon="mdi-heart-outline" color="pink" size="24" />
                Kisah Cinta
            </h2>
            <v-btn
                color="pink"
                size="small"
                variant="text"
                prepend-icon="mdi-plus"
                @click="addKisahCinta"
            >
                Tambah Momen
            </v-btn>
        </div>
        <v-card-text class="p-6">
            <div
                v-for="(kisah, index) in modelValue.kisah_cintas"
                :key="index"
                class="mb-6 p-6! bg-gray-50 rounded-xl relative border"
            >
                <div class="flex justify-between items-center mb-4">
                    <span class="font-bold text-gray-700"
                        >Momen #{{ index + 1 }}</span
                    >
                    <v-btn
                        v-if="modelValue.kisah_cintas.length > 1"
                        size="x-small"
                        color="error"
                        icon="mdi-close"
                        variant="text"
                        @click="removeKisahCinta(index)"
                    />
                </div>
                <v-row>
                    <v-col cols="12" md="12">
                        <v-text-field
                            v-model="kisah.tanggal"
                            type="date"
                            label="Tanggal Kejadian"
                            :error-messages="
                                modelValue.errors[
                                    `kisah_cintas.${index}.tanggal`
                                ]
                            "
                            required
                        />
                    </v-col>
                    <v-col cols="12" md="12">
                        <v-textarea
                            v-model="kisah.peristiwa"
                            label="Peristiwa"
                            rows="2"
                            :error-messages="
                                modelValue.errors[
                                    `kisah_cintas.${index}.peristiwa`
                                ]
                            "
                            required
                        />
                    </v-col>
                    <v-col cols="12">
                        <div class="mb-2 text-sm font-medium text-gray-400">
                            *bisa dikosongi jika tidak ingin ada foto
                        </div>
                        <v-file-upload
                            v-model="kisah.foto"
                            accept="image/*"
                            title="Upload Foto Momen"
                            :error-messages="
                                modelValue.errors[`kisah_cintas.${index}.foto`]
                            "
                            density="compact"
                            variant="outlined"
                            hide-details="auto"
                        />
                    </v-col>
                </v-row>
            </div>
        </v-card-text>
    </v-card>
</template>
