<script setup>
import { VFileUpload } from "vuetify/labs/VFileUpload";
const props = defineProps({
    modelValue: Object,
});

// Since v-file-upload is used with v-model, it should handle the files directly.
// For the "better way" to show previews, we'll let v-file-upload handle its own UI as much as possible,
// or use a helper to clean up object URLs if we still need manual previews.
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
                Pilih Foto-Foto Gallery
            </div>
            <v-file-upload
                v-model="modelValue.galleries"
                multiple
                label="Pilih atau Drag Beberapa Foto"
                accept="image/*"
                :error-messages="modelValue.errors.galleries"
                variant="outlined"
                density="compact"
                show-size
                clearable
            />

            <v-alert
                v-if="modelValue.galleries && modelValue.galleries.length > 0"
                type="info"
                variant="tonal"
                density="compact"
                class="mt-4"
            >
                {{ modelValue.galleries.length }} foto terpilih.
            </v-alert>
        </v-card-text>
    </v-card>
</template>
