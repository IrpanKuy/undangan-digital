<script setup>
import { ref } from "vue";
import { VFileUpload } from "vuetify/labs/VFileUpload";
import { Icon } from "@iconify/vue";

const props = defineProps({
    modelValue: Object,
});

const tempFiles = ref([]);

const handleFilesUpdate = (files) => {
    if (!files || files.length === 0) return;

    // Ensure modelValue.galleries is an array
    if (!Array.isArray(props.modelValue.galleries)) {
        props.modelValue.galleries = [];
    }

    // Append new files
    // Note: We might want to avoid duplicates if possible,
    // but for now, we just append as requested "multi upload gallery foto"
    props.modelValue.galleries.push(...files);

    // Reset temp files to allow "upload lagi"
    tempFiles.value = [];
};

const removeFile = (index) => {
    props.modelValue.galleries.splice(index, 1);
};
</script>

<template>
    <v-card
        variant="outlined"
        class="h-full rounded-xl overflow-hidden border-gray-200 mb-6"
    >
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <v-icon icon="mdi-camera-outline" color="purple" size="24" />
                Gallery Foto
            </h2>
        </div>
        <v-card-text class="p-6">
            <div class="mb-4 text-sm font-medium text-gray-700">
                Pilih atau Tambah Foto-Foto Gallery
            </div>

            <!-- Continuous upload: v-model picks, handleFilesUpdate appends and clears -->
            <v-file-upload
                v-model="tempFiles"
                multiple
                label="Pilih atau Drag Foto (Bisa Berkali-kali)"
                accept="image/*"
                :error-messages="modelValue.errors.galleries"
                variant="outlined"
                density="compact"
                show-size
                @update:model-value="handleFilesUpdate"
            />

            <!-- Preview/List of selected files -->
            <div
                v-if="modelValue.galleries && modelValue.galleries.length > 0"
                class="mt-6"
            >
                <div
                    class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 flex justify-between"
                >
                    <span
                        >File Terpilih ({{ modelValue.galleries.length }})</span
                    >
                    <span
                        class="text-primary cursor-pointer"
                        @click="modelValue.galleries = []"
                        >Bersihkan Semua</span
                    >
                </div>
                <v-list density="compact" class="border rounded-lg bg-gray-50">
                    <v-list-item
                        v-for="(file, index) in modelValue.galleries"
                        :key="index"
                        :title="file.name || `Foto ${index + 1}`"
                        :subtitle="
                            file.size
                                ? `${(file.size / 1024).toFixed(1)} KB`
                                : ''
                        "
                        class="px-2"
                    >
                        <template v-slot:prepend>
                            <v-avatar
                                size="40"
                                rounded="sm"
                                color="grey-lighten-3"
                            >
                                <v-icon icon="mdi-image" color="grey" />
                            </v-avatar>
                        </template>
                        <template v-slot:append>
                            <v-btn
                                icon="mdi-close"
                                variant="text"
                                color="error"
                                density="comfortable"
                                @click="removeFile(index)"
                            ></v-btn>
                        </template>
                    </v-list-item>
                </v-list>
            </div>

            <v-alert
                v-else
                type="info"
                variant="tonal"
                density="compact"
                class="mt-4"
                text="Belum ada foto yang dipilih."
            />
        </v-card-text>
    </v-card>
</template>
