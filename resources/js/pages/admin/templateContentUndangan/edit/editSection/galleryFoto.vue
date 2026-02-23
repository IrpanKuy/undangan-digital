<script setup>
import { VFileUpload } from "vuetify/labs/VFileUpload";
const props = defineProps({
    modelValue: Object,
});

const removeExistingGallery = (id) => {
    if (!props.modelValue.remove_galleries.includes(id)) {
        props.modelValue.remove_galleries.push(id);
    }
};

const undoRemoveExistingGallery = (id) => {
    const index = props.modelValue.remove_galleries.indexOf(id);
    if (index > -1) {
        props.modelValue.remove_galleries.splice(index, 1);
    }
};

const isRemoved = (id) => props.modelValue.remove_galleries.includes(id);

const activeExistingGalleriesCount = () => {
    return props.modelValue.existing_galleries.filter((g) => !isRemoved(g.id))
        .length;
};
</script>

<template>
    <v-card variant="outlined" class="mb-6 rounded-xl border-gray-200">
        <div
            class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center"
        >
            <h2 class="text-lg font-bold flex items-center gap-2">
                <v-icon icon="mdi-image-multiple" color="primary" />
                Gallery Foto
            </h2>
        </div>
        <v-card-text class="p-6">
            <!-- Upload New -->
            <div class="mb-6">
                <div class="mb-2 text-sm font-medium">Upload Foto Baru</div>
                <v-file-upload
                    v-model="modelValue.galleries"
                    multiple
                    label="Pilih atau Drag Foto Baru"
                    accept="image/*"
                    variant="outlined"
                    density="compact"
                    :error-messages="modelValue.errors.galleries"
                    show-size
                    clearable
                />
            </div>

            <!-- Existing Gallery -->
            <div v-if="modelValue.existing_galleries.length > 0">
                <h3 class="font-bold mb-4 flex justify-between items-center">
                    Foto Saat Ini
                    <span class="text-xs font-normal text-gray-500">
                        {{ activeExistingGalleriesCount() }} aktif,
                        {{ modelValue.remove_galleries.length }} dihapus
                    </span>
                </h3>
                <v-row>
                    <v-col
                        v-for="item in modelValue.existing_galleries"
                        :key="item.id"
                        cols="6"
                        sm="4"
                        md="3"
                    >
                        <v-card
                            variant="flat"
                            class="rounded-xl overflow-hidden group relative border"
                            :class="{
                                'opacity-40 grayscale': isRemoved(item.id),
                            }"
                        >
                            <v-img
                                :src="'/storage/' + item.image_path"
                                cover
                                height="120"
                            />
                            <div
                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center"
                            >
                                <v-btn
                                    v-if="!isRemoved(item.id)"
                                    icon="mdi-delete"
                                    color="error"
                                    size="small"
                                    @click="removeExistingGallery(item.id)"
                                />
                                <v-btn
                                    v-else
                                    icon="mdi-undo"
                                    color="success"
                                    size="small"
                                    @click="undoRemoveExistingGallery(item.id)"
                                />
                            </div>
                            <div
                                v-if="isRemoved(item.id)"
                                class="absolute top-1 right-1"
                            >
                                <v-chip color="error" size="x-small"
                                    >Akan dihapus</v-chip
                                >
                            </div>
                        </v-card>
                    </v-col>
                </v-row>
            </div>

            <v-alert
                v-if="modelValue.galleries && modelValue.galleries.length > 0"
                type="info"
                variant="tonal"
                density="compact"
                class="mt-6"
            >
                {{ modelValue.galleries.length }} foto baru akan diunggah.
            </v-alert>
        </v-card-text>
    </v-card>
</template>
