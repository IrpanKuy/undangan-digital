<script setup>
import { VFileUpload } from "vuetify/labs/VFileUpload";
const props = defineProps({
    modelValue: Object,
});

const addGallery = () => {
    props.modelValue.galleries.push({
        id: null,
        file: null,
        image_path: null,
    });
};

const removeGallery = (index) => {
    const item = props.modelValue.galleries[index];
    if (item.id) {
        props.modelValue.remove_galleries.push(item.id);
    }
    props.modelValue.galleries.splice(index, 1);
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
                <v-icon icon="mdi-camera-outline" color="purple" size="24" />
                Gallery Foto
            </h2>
            <v-btn
                color="purple"
                size="small"
                variant="text"
                prepend-icon="mdi-plus"
                @click="addGallery"
            >
                Tambah Foto
            </v-btn>
        </div>
        <v-card-text class="p-6">
            <div
                v-if="modelValue.galleries.length === 0"
                class="text-center py-8"
            >
                <v-icon
                    icon="mdi-image-plus"
                    size="48"
                    color="grey-lighten-1"
                />
                <div class="text-grey-darken-1 mt-2">
                    Belum ada foto gallery.
                </div>
                <v-btn
                    variant="tonal"
                    color="purple"
                    size="small"
                    class="mt-4"
                    prepend-icon="mdi-plus"
                    @click="addGallery"
                >
                    Tambah Pertama
                </v-btn>
            </div>

            <v-row v-else>
                <v-col
                    v-for="(item, index) in modelValue.galleries"
                    :key="index"
                    cols="12"
                    sm="12"
                    md="12"
                >
                    <div class="relative p-4! border rounded-xl bg-gray-50">
                        <div class="flex justify-between items-center mb-2">
                            <span
                                class="text-xs font-bold text-gray-500 uppercase"
                                >Foto #{{ index + 1 }}</span
                            >
                            <v-btn
                                icon="mdi-close"
                                variant="text"
                                color="error"
                                density="comfortable"
                                @click="removeGallery(index)"
                            />
                        </div>

                        <!-- Existing Image Preview -->
                        <div v-if="item.image_path && !item.file" class="mb-3">
                            <v-img
                                :src="`/storage/${item.image_path}`"
                                height="120"
                                cover
                                class="rounded-lg border shadow-sm"
                            />
                            <div
                                class="text-[10px] text-center mt-1 text-gray-400"
                            >
                                Ganti foto dengan upload baru
                            </div>
                        </div>

                        <v-file-upload
                            v-model="item.file"
                            accept="image/*"
                            title="Upload Foto"
                            density="compact"
                            variant="outlined"
                            :error-messages="
                                modelValue.errors[`galleries.${index}.file`]
                            "
                        />
                    </div>
                </v-col>
            </v-row>

            <v-alert
                v-if="modelValue.errors.galleries"
                type="error"
                variant="tonal"
                density="compact"
                class="mt-4"
            >
                {{ modelValue.errors.galleries }}
            </v-alert>
        </v-card-text>
    </v-card>
</template>
