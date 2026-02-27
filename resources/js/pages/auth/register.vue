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
                        src="https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=2070&auto=format&fit=crop"
                        class="object-cover w-full h-full opacity-60 grayscale-20"
                        alt="Wedding Background"
                    />
                    <div
                        class="absolute inset-0 bg-linear-to-tr from-primary/80 to-transparent"
                    ></div>
                </div>

                <div class="relative z-10 text-white w-full">
                    <h1 class="text-4xl font-extrabold mb-4 leading-tight">
                        Mulai Perjalanan Kabar<br />Bahagia Anda di Sini.
                    </h1>
                    <p class="text-lg opacity-90 mb-8 max-w-sm font-light">
                        Gabung bersama komunitas kami dan buat desain undangan
                        digital yang merepresentasikan keunikan cinta Anda.
                    </p>

                    <ul class="space-y-4">
                        <li class="flex items-center">
                            <Icon
                                icon="mdi:check-circle"
                                class="text-cyan-300 text-2xl mr-3 shrink-0"
                            />
                            <span class="font-light text-lg"
                                >Akses Ratusan Template Eksklusif</span
                            >
                        </li>
                        <li class="flex items-center">
                            <Icon
                                icon="mdi:check-circle"
                                class="text-cyan-300 text-2xl mr-3 shrink-0"
                            />
                            <span class="font-light text-lg"
                                >Custom Domain & Musik Pilihan</span
                            >
                        </li>
                        <li class="flex items-center">
                            <Icon
                                icon="mdi:check-circle"
                                class="text-cyan-300 text-2xl mr-3 shrink-0"
                            />
                            <span class="font-light text-lg"
                                >Fitur Buku Tamu & RSVP Digital</span
                            >
                        </li>
                    </ul>
                </div>
            </div>

            <div
                class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center"
            >
                <div class="flex justify-center md:justify-start mb-8">
                    <img
                        src="/assets/logo_up.png"
                        alt="Logo"
                        class="w-36 object-contain"
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

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
                            >
                                <Icon
                                    icon="mdi:account-outline"
                                    class="text-gray-400 text-xl"
                                />
                            </div>
                            <input
                                v-model="form.name"
                                type="text"
                                placeholder="Nama Lengkap"
                                :class="[
                                    'w-full pl-11 pr-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition duration-200',
                                    form.errors.name
                                        ? 'border-red-500 focus:ring-red-200 error-input'
                                        : 'border-gray-300 focus:ring-primary/30 focus:border-primary',
                                ]"
                            />
                        </div>
                        <p
                            v-if="form.errors.name"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
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
                                    placeholder="Email"
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
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
                                >
                                    <Icon
                                        icon="mdi:whatsapp"
                                        class="text-gray-400 text-xl"
                                    />
                                </div>
                                <input
                                    v-model="form.no_hp"
                                    type="text"
                                    placeholder="No. WhatsApp"
                                    :class="[
                                        'w-full pl-11 pr-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition duration-200',
                                        form.errors.no_hp
                                            ? 'border-red-500 focus:ring-red-200 error-input'
                                            : 'border-gray-300 focus:ring-primary/30 focus:border-primary',
                                    ]"
                                />
                            </div>
                            <p
                                v-if="form.errors.no_hp"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ form.errors.no_hp }}
                            </p>
                        </div>
                    </div>

                    <div>
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
                                placeholder="Kata Sandi (Min. 8 karakter)"
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

                    <div>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
                            >
                                <Icon
                                    icon="mdi:lock-check-outline"
                                    class="text-gray-400 text-xl"
                                />
                            </div>
                            <input
                                v-model="form.password_confirmation"
                                :type="showPassword ? 'text' : 'password'"
                                placeholder="Konfirmasi Kata Sandi"
                                :class="[
                                    'w-full pl-11 pr-12 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition duration-200',
                                    form.errors.password_confirmation
                                        ? 'border-red-500 focus:ring-red-200 error-input'
                                        : 'border-gray-300 focus:ring-primary/30 focus:border-primary',
                                ]"
                            />
                        </div>
                        <p
                            v-if="form.errors.password_confirmation"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.password_confirmation }}
                        </p>
                    </div>

                    <p class="text-[11px] text-gray-500 mt-1 px-1 italic">
                        *Dengan mendaftar, Anda menyetujui Ketentuan Layanan dan
                        Kebijakan Privasi kami.
                    </p>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full py-3.5 bg-primary text-white rounded-xl text-lg font-bold shadow-lg hover:bg-primary/90 active:scale-[0.98] transition-all disabled:opacity-70 disabled:cursor-not-allowed mt-2"
                    >
                        <span v-if="form.processing">Memproses...</span>
                        <span v-else>Daftar Akun</span>
                    </button>

                    <div class="relative py-3">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="px-3 bg-white text-gray-500"
                                >Atau daftar dengan</span
                            >
                        </div>
                    </div>

                    <button
                        type="button"
                        @click="loginWithGoogle"
                        class="w-full flex items-center justify-center py-3.5 border border-gray-200 rounded-xl text-gray-700 font-medium hover:bg-gray-50 active:scale-[0.98] transition-all"
                    >
                        <Icon icon="logos:google-icon" class="text-xl mr-3" />
                        Daftar dengan Google
                    </button>
                </form>

                <p class="text-center mt-6 text-gray-600">
                    Sudah punya akun?
                    <Link
                        :href="route('login')"
                        class="text-primary font-bold hover:underline"
                    >
                        Masuk di Sini
                    </Link>
                </p>
            </div>
        </div>
    </div>
</template>
