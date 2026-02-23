<script setup>
import { ref } from "vue";
import { useForm, Link } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
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
    // Logic for Google OAuth
    window.location.href = route("google.login");
};
</script>

<template>
    <div
        class="min-h-screen flex items-center justify-center bg-gray-50 font-sans"
    >
        <v-card
            class="max-w-6xl w-full mx-4 shadow-2xl rounded-3xl overflow-hidden flex! flex-col! md:flex-row!"
        >
            <!-- Left Side: Promotional Image (Desktop Only) -->
            <div
                class="hidden md:flex! md:w-1/2! relative bg-primary items-center justify-center p-12! overflow-hidden"
            >
                <div class="absolute inset-0 z-0">
                    <v-img
                        src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?q=80&w=2069&auto=format&fit=crop"
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
                        Buat Undangan Digital<br />Impian Anda Secara Instan.
                    </h1>
                    <p class="text-lg opacity-90 mb-8 max-w-sm font-light">
                        Ribuan pasangan telah mempercayakan momen bahagia mereka
                        kepada kami. Mudah, Cepat, dan Elegan.
                    </p>
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <span class="text-2xl font-bold italic">5K+</span>
                            <span
                                class="text-xs uppercase tracking-widest opacity-70"
                                >Users</span
                            >
                        </div>
                        <div class="w-px h-8 bg-white/30 my-auto"></div>
                        <div class="flex flex-col items-center">
                            <span class="text-2xl font-bold italic">100+</span>
                            <span
                                class="text-xs uppercase tracking-widest opacity-70"
                                >Templates</span
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Login Form -->
            <div
                class="w-full md:w-1/2 p-8! md:p-16 bg-white flex flex-col justify-center"
            >
                <!-- Logo -->
                <div class="flex justify-center md:justify-start mb-10">
                    <v-img
                        src="/assets/logo_up.png"
                        width="160"
                        max-width="180"
                        contain
                    />
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-1">
                        Selamat Datang Kembali!
                    </h2>
                    <p class="text-gray-500">
                        Silakan login ke akun Anda untuk melanjutkan.
                    </p>
                </div>

                <v-form @submit.prevent="submit" class="space-y-4!">
                    <v-text-field
                        v-model="form.email"
                        label="Alamat Email"
                        placeholder="nama@email.com"
                        type="email"
                        variant="outlined"
                        density="comfortable"
                        prepend-inner-icon="mdi-email-outline"
                        :error-messages="form.errors.email"
                        rounded="lg"
                        hide-details="auto"
                    />

                    <v-text-field
                        v-model="form.password"
                        label="Kata Sandi"
                        placeholder="••••••••"
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

                    <div class="flex items-center justify-between mt-2">
                        <v-checkbox
                            v-model="form.remember"
                            label="Ingat saya"
                            color="primary"
                            density="compact"
                            hide-details
                        />
                        <Link
                            href="/forgot-password"
                            class="text-sm font-medium text-primary hover:underline"
                        >
                            Lupa Password?
                        </Link>
                    </div>

                    <v-btn
                        type="submit"
                        color="primary"
                        size="large"
                        block
                        class="rounded-xl normal-case text-lg font-bold shadow-lg mt-6"
                        :loading="form.processing"
                    >
                        Masuk Sekarang
                    </v-btn>

                    <div class="relative py-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-3 bg-white text-gray-500"
                                >Atau masuk dengan</span
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
                                width="20"
                                class="mr-2"
                            />
                        </template>
                        Lanjutkan dengan Google
                    </v-btn>
                </v-form>

                <p class="text-center mt-10 text-gray-600">
                    Belum punya akun?
                    <Link
                        :href="route('register')"
                        class="text-primary font-bold hover:underline"
                    >
                        Daftar Gratis
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
