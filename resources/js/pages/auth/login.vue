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
            // Scroll to first error
            setTimeout(() => {
                const firstError = document.querySelector(".error-input");
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
        <div
            class="max-w-6xl w-full bg-white shadow-2xl rounded-3xl overflow-hidden flex flex-col md:flex-row"
        >
            <div
                class="hidden md:flex md:w-1/2 relative bg-primary items-center justify-center p-12 overflow-hidden"
            >
                <div class="absolute inset-0 z-0">
                    <img
                        src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?q=80&w=2069&auto=format&fit=crop"
                        class="object-cover w-full h-full opacity-60 grayscale-[20%]"
                        alt="Wedding Background"
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

            <div
                class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center"
            >
                <div class="flex justify-center md:justify-start mb-10">
                    <img
                        src="/assets/logo_up.png"
                        alt="Logo"
                        class="w-40 object-contain"
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

                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Alamat Email</label
                        >
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
                            >
                                <Icon
                                    icon="mdi:email-outline"
                                    class="text-gray-400 text-xl"
                                />
                            </div>
                            <input
                                v-model="form.email"
                                type="email"
                                placeholder="nama@email.com"
                                :class="[
                                    'w-full pl-11 pr-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition duration-200',
                                    form.errors.email
                                        ? 'border-red-500 focus:ring-red-200 error-input'
                                        : 'border-gray-300 focus:ring-primary/30 focus:border-primary',
                                ]"
                            />
                        </div>
                        <p
                            v-if="form.errors.email"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Kata Sandi</label
                        >
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
                            >
                                <Icon
                                    icon="mdi:lock-outline"
                                    class="text-gray-400 text-xl"
                                />
                            </div>
                            <input
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                placeholder="••••••••"
                                :class="[
                                    'w-full pl-11 pr-12 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition duration-200',
                                    form.errors.password
                                        ? 'border-red-500 focus:ring-red-200 error-input'
                                        : 'border-gray-300 focus:ring-primary/30 focus:border-primary',
                                ]"
                            />
                            <div
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer text-gray-400 hover:text-gray-600"
                            >
                                <Icon
                                    :icon="
                                        showPassword ? 'mdi:eye-off' : 'mdi:eye'
                                    "
                                    class="text-xl"
                                />
                            </div>
                        </div>
                        <p
                            v-if="form.errors.password"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer">
                            <input
                                v-model="form.remember"
                                type="checkbox"
                                class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary focus:ring-2 cursor-pointer"
                            />
                            <span class="ml-2 text-sm text-gray-600"
                                >Ingat saya</span
                            >
                        </label>

                        <Link
                            href="/forgot-password"
                            class="text-sm font-medium text-primary hover:underline"
                        >
                            Lupa Password?
                        </Link>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full py-3.5 bg-primary text-white bg-[#004D31] rounded-xl text-lg font-bold shadow-lg hover:bg-primary/90 active:scale-[0.98] transition-all disabled:opacity-70 disabled:cursor-not-allowed mt-2"
                    >
                        <span v-if="form.processing">Memproses...</span>
                        <span v-else>Masuk Sekarang</span>
                    </button>

                    <div class="relative py-4">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-3 bg-white text-gray-500"
                                >Atau masuk dengan</span
                            >
                        </div>
                    </div>

                    <button
                        type="button"
                        @click="loginWithGoogle"
                        class="w-full flex items-center justify-center py-3.5 border border-gray-200 rounded-xl text-gray-700 font-medium hover:bg-gray-50 active:scale-[0.98] transition-all"
                    >
                        <Icon icon="logos:google-icon" class="text-xl mr-3" />
                        Lanjutkan dengan Google
                    </button>
                </form>

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
        </div>
    </div>
</template>
