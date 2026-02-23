<script setup>
import { ref } from "vue";
import axios from "axios";
import { VFileUpload } from "vuetify/labs/VFileUpload";

const props = defineProps({
    modelValue: Object,
    template: Object,
});

const urlStatus = ref(null);

const checkUrl = async () => {
    if (!props.modelValue.url || props.modelValue.url === props.template.url) {
        urlStatus.value = null;
        return;
    }
    urlStatus.value = "checking";
    try {
        const response = await axios.post(
            route("admin.template-content-undangan.check-url"),
            {
                url: props.modelValue.url,
                exclude_id: props.template.id,
            },
        );
        urlStatus.value = response.data.available ? "available" : "taken";
    } catch (error) {
        urlStatus.value = null;
    }
};
</script>

<template>
    <v-card
        variant="outlined"
        class="mb-6 rounded-xl overflow-hidden border-gray-200"
    >
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h2 class="text-lg font-bold flex items-center gap-2">
                <v-icon icon="mdi-information-outline" color="primary" />
                Informasi Dasar Undangan
            </h2>
        </div>
        <v-card-text class="p-6">
            <v-row>
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="modelValue.judul"
                        label="Judul Undangan"
                        variant="outlined"
                        prepend-inner-icon="mdi-format-title"
                        :error-messages="modelValue.errors.judul"
                        required
                    />
                </v-col>
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="modelValue.url"
                        label="Custom URL (Opsional)"
                        placeholder="Contoh: budi-citra"
                        suffix=".undangankuy.com"
                        variant="outlined"
                        prepend-inner-icon="mdi-link-variant"
                        :error-messages="modelValue.errors.url"
                        @input="checkUrl"
                    >
                        <template #append-inner>
                            <v-progress-circular
                                v-if="urlStatus === 'checking'"
                                indeterminate
                                size="20"
                                width="2"
                            />
                            <v-icon
                                v-else-if="urlStatus === 'available'"
                                icon="mdi-check-circle"
                                color="success"
                            />
                            <v-icon
                                v-else-if="urlStatus === 'taken'"
                                icon="mdi-alert-circle"
                                color="error"
                            />
                        </template>
                    </v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                    <div class="mb-2 text-sm font-medium">
                        Thumbnail Undangan
                    </div>
                    <div
                        class="flex items-center gap-4 mb-2"
                        v-if="template.thumbnail_path"
                    >
                        <v-img
                            :src="'/storage/' + template.thumbnail_path"
                            width="80"
                            height="50"
                            cover
                            class="rounded border"
                        />
                        <span class="text-xs text-gray-500"
                            >Thumbnail Saat Ini</span
                        >
                    </div>
                    <v-file-upload
                        v-model="modelValue.thumbnail"
                        label="Ganti Thumbnail (Opsional)"
                        accept="image/*"
                        variant="outlined"
                        density="compact"
                        :error-messages="modelValue.errors.thumbnail"
                    />
                </v-col>
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="modelValue.video_youtube_url"
                        label="Link Video YouTube (Opsional)"
                        variant="outlined"
                        prepend-inner-icon="mdi-youtube"
                        :error-messages="modelValue.errors.video_youtube_url"
                    />
                </v-col>
                <v-col cols="12" md="6">
                    <v-textarea
                        v-model="modelValue.salam_pembuka"
                        label="Salam Pembuka"
                        variant="outlined"
                        rows="2"
                        :error-messages="modelValue.errors.salam_pembuka"
                        required
                    />
                </v-col>
                <v-col cols="12" md="6">
                    <v-textarea
                        v-model="modelValue.text_pembuka"
                        label="Text Pembuka"
                        variant="outlined"
                        rows="2"
                        :error-messages="modelValue.errors.text_pembuka"
                        required
                    />
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>
