<script setup>
import { VFileUpload } from "vuetify/labs/VFileUpload";
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
                <v-icon
                    icon="mdi-account-heart-outline"
                    color="pink"
                    size="24"
                />
                Data Mempelai
            </h2>
        </div>
        <v-card-text class="p-6">
            <v-row>
                <!-- Mempelai Pria -->
                <v-col cols="12" md="6">
                    <h3
                        class="text-md font-bold text-primary mb-4 flex items-center gap-2"
                    >
                        <v-icon icon="mdi-face-man" />
                        Mempelai Pria
                    </h3>
                    <v-text-field
                        v-model="modelValue.nama_lengkap_pria"
                        label="Nama Lengkap Pria"
                        variant="outlined"
                        prepend-inner-icon="mdi-account"
                        :error-messages="modelValue.errors.nama_lengkap_pria"
                        required
                    />
                    <v-text-field
                        v-model="modelValue.nama_panggilan_pria"
                        label="Nama Panggilan Pria"
                        variant="outlined"
                        prepend-inner-icon="mdi-account-outline"
                        :error-messages="modelValue.errors.nama_panggilan_pria"
                        required
                    />
                    <v-textarea
                        v-model="modelValue.keterangan_keluarga_pria"
                        label="Keterangan Keluarga Pria (Opsional)"
                        placeholder="Putra dari Bapak... & Ibu..."
                        variant="outlined"
                        rows="2"
                        :error-messages="
                            modelValue.errors.keterangan_keluarga_pria
                        "
                    />
                    <div class="mb-2 text-sm font-medium text-gray-700">
                        Foto Mempelai Pria (Opsional)
                    </div>
                    <v-file-upload
                        v-model="modelValue.foto_pria"
                        accept="image/*"
                        label="Pilih atau Drag Foto Pria"
                        :error-messages="modelValue.errors.foto_pria"
                        density="compact"
                        variant="outlined"
                    />
                </v-col>

                <!-- Mempelai Wanita -->
                <v-col cols="12" md="6">
                    <h3
                        class="text-md font-bold text-pink-700 mb-4 flex items-center gap-2"
                    >
                        <v-icon icon="mdi-face-woman" />
                        Mempelai Wanita
                    </h3>
                    <v-text-field
                        v-model="modelValue.nama_lengkap_wanita"
                        label="Nama Lengkap Wanita"
                        variant="outlined"
                        prepend-inner-icon="mdi-account"
                        :error-messages="modelValue.errors.nama_lengkap_wanita"
                        required
                    />
                    <v-text-field
                        v-model="modelValue.nama_panggilan_wanita"
                        label="Nama Panggilan Wanita"
                        variant="outlined"
                        prepend-inner-icon="mdi-account-outline"
                        :error-messages="
                            modelValue.errors.nama_panggilan_wanita
                        "
                        required
                    />
                    <v-textarea
                        v-model="modelValue.keterangan_keluarga_wanita"
                        label="Keterangan Keluarga Wanita (Opsional)"
                        placeholder="Putri dari Bapak... & Ibu..."
                        variant="outlined"
                        rows="2"
                        :error-messages="
                            modelValue.errors.keterangan_keluarga_wanita
                        "
                    />
                    <div class="mb-2 text-sm font-medium text-gray-700">
                        Foto Mempelai Wanita (Opsional)
                    </div>
                    <v-file-upload
                        v-model="modelValue.foto_wanita"
                        accept="image/*"
                        label="Pilih atau Drag Foto Wanita"
                        :error-messages="modelValue.errors.foto_wanita"
                        density="compact"
                        variant="outlined"
                    />
                </v-col>
                <v-col cols="12">
                    <v-textarea
                        v-model="modelValue.text_penutup"
                        label="Text Penutup (Opsional)"
                        placeholder="Tiada kesan tanpa kehadiran Anda..."
                        variant="outlined"
                        rows="2"
                        :error-messages="modelValue.errors.text_penutup"
                    />
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>
