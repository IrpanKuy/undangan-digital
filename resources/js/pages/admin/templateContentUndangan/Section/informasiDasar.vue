<script setup>
import { ref } from "vue";
import axios from "axios";
import { VFileUpload } from "vuetify/labs/VFileUpload";

const props = defineProps({
    modelValue: Object,
});

const urlStatus = ref(null); // null, 'checking', 'available', 'taken'

const checkUrl = async () => {
    if (!props.modelValue.url) {
        urlStatus.value = null;
        return;
    }
    urlStatus.value = "checking";
    try {
        const response = await axios.post(
            route("admin.template-content-undangan.check-url"),
            {
                url: props.modelValue.url,
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
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <v-icon
                    icon="mdi-information-outline"
                    color="primary"
                    size="24"
                />
                Informasi Dasar Undangan
            </h2>
        </div>
        <v-card-text class="p-6">
            <v-row>
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="modelValue.judul"
                        label="Judul Undangan"
                        placeholder="Contoh: Pernikahan Budi & Citra"
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
                    <!-- Thumbnail using v-file-upload -->
                    <v-input :error-messages="modelValue.errors.thumbnail">
                        <v-file-upload
                            v-model="modelValue.thumbnail"
                            title="Pilih Gambar Thumbnail"
                            accept="image/*"
                            density="compact"
                            variant="outlined"
                            class="mb-2"
                            hint="Rekomendasi 1200x630"
                            persistent-hint
                        />
                    </v-input>
                </v-col>
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="modelValue.video_youtube_url"
                        label="Link Video YouTube (Opsional)"
                        placeholder="https://www.youtube.com/watch?v=..."
                        variant="outlined"
                        prepend-inner-icon="mdi-youtube"
                        :error-messages="modelValue.errors.video_youtube_url"
                    />
                </v-col>
                <v-col cols="12" md="6">
                    <v-textarea
                        v-model="modelValue.salam_pembuka"
                        label="Salam Pembuka"
                        model-value="Assalamu'alaikum Wr. Wb."
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
                        model-value="Maha suci Allah yang telah menciptakan makhluk-Nya berpasang-pasangan. Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud menyelenggarakan syukuran pernikahan putra-putri kami:"
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
