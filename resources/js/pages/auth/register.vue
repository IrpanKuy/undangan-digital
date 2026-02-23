<script setup>
import { ref } from "vue";
import { useForm, Link } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

const form = useForm({
    name: "",
    email: "",
    no_hp: "",
    password: "",
    password_confirmation: "",
});

const showPassword = ref(false);

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
        onError: () => {
            // Scroll to first error as per READMERULES.md
            setTimeout(() => {
                const firstError = document.querySelector(".v-input--error");
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: "smooth",
                        block: "center",
                    });
                }
            }, 100);
        },
    });
};

const loginWithGoogle = () => {
    window.location.href = route("google.login");
};
</script>

<template>
    <div
        class="min-h-screen flex items-center justify-center bg-gray-50 font-sans p-4"
    >
        <v-card
            class="max-w-6xl w-full shadow-2xl rounded-3xl overflow-hidden flex flex-col md:flex-row"
        >
            <!-- Left Side: Promotional Image (Desktop Only) -->
            <div
                class="hidden md:flex md:w-1/2 relative bg-primary items-center justify-center p-12 overflow-hidden"
            >
                <div class="absolute inset-0 z-0">
                    <v-img
                        src="https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=2070&auto=format&fit=crop"
                        cover
                        height="100%"
                        class="opacity-60 grayscale-20"
                    />
                    <div
                        class="absolute inset-0 bg-linear-to-tr from-primary/80 to-transparent"
                    ></div>
                </div>

                <div class="relative z-10 text-white">
                    <h1 class="text-4xl font-extrabold mb-4 leading-tight">
                        Mulai Perjalanan Kabar<br />Bahagia Anda di Sini.
                    </h1>
                    <p class="text-lg opacity-90 mb-8 max-w-sm font-light">
                        Gabung bersama komunitas kami dan buat desain undangan
                        digital yang merepresentasikan keunikan cinta Anda.
                    </p>
                    <v-list bg-color="transparent" class="text-white">
                        <v-list-item density="compact">
                            <template #prepend>
                                <Icon
                                    icon="mdi:check-circle"
                                    class="mr-3 text-cyan-300"
                                />
                            </template>
                            <v-list-item-title class="font-light"
                                >Akses Ratusan Template
                                Eksklusif</v-list-item-title
                            >
                        </v-list-item>
                        <v-list-item density="compact">
                            <template #prepend>
                                <Icon
                                    icon="mdi:check-circle"
                                    class="mr-3 text-cyan-300"
                                />
                            </template>
                            <v-list-item-title class="font-light"
                                >Custom Domain & Musik
                                Pilihan</v-list-item-title
                            >
                        </v-list-item>
                        <v-list-item density="compact">
                            <template #prepend>
                                <Icon
                                    icon="mdi:check-circle"
                                    class="mr-3 text-cyan-300"
                                />
                            </template>
                            <v-list-item-title class="font-light"
                                >Fitur Buku Tamu & RSVP
                                Digital</v-list-item-title
                            >
                        </v-list-item>
                    </v-list>
                </div>
            </div>

            <!-- Right Side: Register Form -->
            <div
                class="w-full md:w-1/2 p-8 md:p-12 bg-white flex flex-col justify-center"
            >
                <!-- Logo -->
                <div class="flex justify-center md:justify-start mb-8">
                    <v-img
                        src="/assets/logo_up.png"
                        width="140"
                        max-width="160"
                        contain
                    />
                </div>

                <div class="mb-6 text-center md:text-left">
                    <h2 class="text-2xl font-bold text-gray-800 mb-1">
                        Buat Akun Baru
                    </h2>
                    <p class="text-gray-500">
                        Daftar sekarang untuk mulai membuat undangan.
                    </p>
                </div>

                <v-form @submit.prevent="submit" class="space-y-3">
                    <v-text-field
                        v-model="form.name"
                        label="Nama Lengkap"
                        placeholder="Masukkan nama lengkap"
                        variant="outlined"
                        density="comfortable"
                        prepend-inner-icon="mdi-account-outline"
                        :error-messages="form.errors.name"
                        rounded="lg"
                        hide-details="auto"
                    />

                    <v-row dense>
                        <v-col cols="12" sm="6">
                            <v-text-field
                                v-model="form.email"
                                label="Email"
                                placeholder="email@contoh.com"
                                type="email"
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-email-outline"
                                :error-messages="form.errors.email"
                                rounded="lg"
                                hide-details="auto"
                            />
                        </v-col>
                        <v-col cols="12" sm="6">
                            <v-text-field
                                v-model="form.no_hp"
                                label="No. WhatsApp"
                                placeholder="0812xxxxxxxx"
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-whatsapp"
                                :error-messages="form.errors.no_hp"
                                rounded="lg"
                                hide-details="auto"
                            />
                        </v-col>
                    </v-row>

                    <v-text-field
                        v-model="form.password"
                        label="Kata Sandi"
                        placeholder="Min. 8 karakter"
                        :type="showPassword ? 'text' : 'password'"
                        variant="outlined"
                        density="comfortable"
                        prepend-inner-icon="mdi-lock-outline"
                        :append-inner-icon="
                            showPassword ? 'mdi-eye-off' : 'mdi-eye'
                        "
                        @click:append-inner="showPassword = !showPassword"
                        :error-messages="form.errors.password"
                        rounded="lg"
                        hide-details="auto"
                    />

                    <v-text-field
                        v-model="form.password_confirmation"
                        label="Konfirmasi Kata Sandi"
                        placeholder="Ulangi kata sandi"
                        :type="showPassword ? 'text' : 'password'"
                        variant="outlined"
                        density="comfortable"
                        prepend-inner-icon="mdi-lock-check-outline"
                        :error-messages="form.errors.password_confirmation"
                        rounded="lg"
                        hide-details="auto"
                    />

                    <p class="text-[10px] text-gray-400 mt-2 px-1 italic">
                        *Dengan mendaftar, Anda menyetujui Ketentuan Layanan dan
                        Kebijakan Privasi kami.
                    </p>

                    <v-btn
                        type="submit"
                        color="primary"
                        size="large"
                        block
                        class="rounded-xl normal-case text-lg font-bold shadow-lg mt-4"
                        :loading="form.processing"
                    >
                        Daftar Akun
                    </v-btn>

                    <div class="relative py-4">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="px-3 bg-white text-gray-400"
                                >Atau daftar dengan</span
                            >
                        </div>
                    </div>

                    <v-btn
                        variant="outlined"
                        size="large"
                        block
                        class="rounded-xl normal-case text-gray-700 border-gray-200 hover:bg-gray-50 transition-colors"
                        @click="loginWithGoogle"
                    >
                        <template #prepend>
                            <Icon
                                icon="logos:google-icon"
                                width="18"
                                class="mr-2"
                            />
                        </template>
                        Daftar dengan Google
                    </v-btn>
                </v-form>

                <p class="text-center mt-8 text-gray-600">
                    Sudah punya akun?
                    <Link
                        :href="route('login')"
                        class="text-primary font-bold hover:underline"
                    >
                        Masuk di Sini
                    </Link>
                </p>
            </div>
        </v-card>
    </div>
</template>

<style scoped>
:deep(.v-field__outline) {
    --v-field-border-opacity: 0.1;
}
:deep(.v-field--focused .v-field__outline) {
    --v-field-border-opacity: 1;
}
</style>
