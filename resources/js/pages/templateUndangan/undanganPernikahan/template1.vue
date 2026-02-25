<script setup>
import { ref } from "vue";

const namaPengantinPria = "Anisa";
const namaPengantinWanita = "Jamal";
const akadNikah = "jum'at, 27 Februari 2026";
const isOpen = ref(false);
</script>
<template>
    <div
        class="bg-red-300 -z-50 w-screen h-screen overflow-y-auto overflow-x-hidden"
    >
        <!-- buka undangaan -->
        <div v-if="isOpen == true">
            <div
                class="mx-auto relative flex flex-col justify-center items-center text-center w-full h-screen max-w-[480px] bg-linear-to-b from-[#ffffff] to-[#E8F5FF]"
            >
                <div class="z-0">
                    <img
                        class="w-70 absolute top-0 right-0 object-cover inline"
                        src="/assets/bunga/bunga-1.png"
                        alt=""
                    />
                    <img
                        class="w-70 rotate-180 absolute bottom-0 left-0 object-cover inline"
                        src="/assets/bunga/bunga-1.png"
                        alt=""
                    />
                </div>
                <div class="z-10 flex flex-col items-center space-y-14">
                    <h2 class="font-bold font-lora text-4xl z-50 italic">
                        The Wedding Of
                    </h2>
                    <div
                        class="flex flex-col text-5xl font-alex-brush space-y-2"
                    >
                        <h4>Anisa</h4>
                        <p>&</p>
                        <h4>Jamal</h4>
                    </div>
                    <div class="relative">
                        <img
                            src="https://placehold.co/400"
                            alt="Foto Profil Manusia"
                            class="w-40 h-40 z-10 rounded-full border-2 border-gray-200 object-cover shadow-sm"
                        />
                        <img
                            src="/assets/bingkai/bingkai1.png"
                            alt="Foto Profil Manusia"
                            class="w-full h-full scale-150 absolute inset-0 z-0 object-contain"
                        />
                    </div>
                    <button
                        @click="isOpen = false"
                        class="px-4 py-3 cursor-pointer flex rounded-full justify-center items-center gap-3 bg-[#6D99BD] text-white"
                    >
                        <icon icon="ion:book-sharp" width="20" />
                        <p>Buka Undangan</p>
                    </button>
                </div>
            </div>
        </div>

        <div v-if="isOpen == false">
            <!-- section 1 -->
            <div
                class="relative mx-auto flex flex-col items-center text-center w-full max-w-[480px] bg-linear-to-b from-[#ffffff] to-[#E8F5FF]"
            >
                <div class="z-0">
                    <img
                        class="w-70 absolute top-0 right-0 object-cover"
                        src="/assets/bunga/bunga-1.png"
                        alt=""
                    />
                    <img
                        class="w-70 rotate-180 absolute bottom-0 left-0 object-cover"
                        src="/assets/bunga/bunga-1.png"
                        alt=""
                    />
                </div>
                <div class="relative min-h-full px-10 py-20">
                    <div>
                        <div
                            class="relative z-10 flex flex-col items-center space-y-14"
                        >
                            <h2
                                class="font-bold font-lora text-3xl z-50 italic"
                            >
                                Undangan Pernikahan
                            </h2>
                            <div class="relative">
                                <img
                                    src="https://placehold.co/400"
                                    alt="Foto Profil Manusia"
                                    class="w-40 h-40 z-10 rounded-full border-2 border-gray-200 object-cover shadow-sm"
                                />
                                <img
                                    src="/assets/bingkai/bingkai1.png"
                                    alt="Foto Profil Manusia"
                                    class="w-full h-full scale-150 absolute inset-0 z-0 object-contain"
                                />
                            </div>
                            <div
                                class="flex flex-col text-5xl font-alex-brush space-y-2"
                            >
                                <h4>{{ namaPengantinPria }}</h4>
                                <p>&</p>
                                <h4>{{ namaPengantinWanita }}</h4>
                            </div>
                            <p class="font-montserrat text-xl">
                                Akan menyelenggarakan acara <br />pernikahan
                                pada
                            </p>
                            <h2 class="font-bold font-lora text-3xl">
                                {{ akadNikah }}
                            </h2>
                            <p class="font-montserrat text-xl">
                                Acara akan dimulai dalam
                            </p>
                            <div class="flex gap-4">
                                <div
                                    class="w-16 h-16 flex justify-center items-center rounded-lg flex-col p-1 bg-linear-to-br from-[#5A85A8] to-[#AAC8E0] text-white font-lora"
                                >
                                    <div class="text-lg font-medium">Hari</div>
                                    <p class="font-bold">5</p>
                                </div>
                                <div
                                    class="w-16 h-16 flex justify-center items-center rounded-lg flex-col p-1 bg-linear-to-br from-[#5A85A8] to-[#AAC8E0] text-white font-lora"
                                >
                                    <div class="text-lg font-medium">Jam</div>
                                    <p class="font-bold">5</p>
                                </div>
                                <div
                                    class="w-16 h-16 flex justify-center items-center rounded-lg flex-col p-1 bg-linear-to-br from-[#5A85A8] to-[#AAC8E0] text-white font-lora"
                                >
                                    <div class="text-lg font-medium">Menit</div>
                                    <p class="font-bold">5</p>
                                </div>
                                <div
                                    class="w-16 h-16 flex justify-center items-center rounded-lg flex-col p-1 bg-linear-to-br from-[#5A85A8] to-[#AAC8E0] text-white font-lora"
                                >
                                    <div class="text-lg font-medium">Detik</div>
                                    <p class="font-bold">5</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- section 2 -->
            <div
                class="relative mx-auto flex flex-col items-center text-center w-full max-w-[480px] bg-linear-to-b from-[#ffffff] to-[#E8F5FF]"
            >
                <div class="z-0">
                    <img
                        class="w-70 absolute top-0 right-0 object-cover"
                        src="/assets/bunga/bunga-1.png"
                        alt=""
                    />
                    <img
                        class="w-70 rotate-180 absolute bottom-0 left-0 object-cover"
                        src="/assets/bunga/bunga-1.png"
                        alt=""
                    />
                </div>
                <div class="relative min-h-full px-10 py-20">
                    <div>
                        <div
                            class="relative z-10 flex flex-col items-center space-y-14"
                        >
                            <h2
                                class="font-bold font-lora text-3xl z-50 italic"
                            >
                                Assalamualaikum Wr.Br
                            </h2>
                            <p class="font-montserrat text-xl">
                                Puji sykur kehadirat Allah sehingga
                                terlaksanakan momen bahagia pada acara
                                pernikahan ini
                            </p>
                            <div class="relative">
                                <img
                                    src="https://placehold.co/400"
                                    alt="Foto Profil Manusia"
                                    class="w-40 h-40 z-10 rounded-full border-2 border-gray-200 object-cover shadow-sm"
                                />
                                <img
                                    src="/assets/bingkai/bingkai1.png"
                                    alt="Foto Profil Manusia"
                                    class="w-full h-full scale-150 absolute inset-0 z-0 object-contain"
                                />
                            </div>
                            <div class="space-x-6">
                                <h2
                                    class="flex flex-col text-5xl font-alex-brush"
                                >
                                    Anisa Putri
                                </h2>
                                <p class="font-montserrat text-xl">
                                    Putri Bapak Haji Kusumo
                                </p>
                            </div>
                            <div class="flex gap-4">
                                <img
                                    class="object-cover w-16 rounded-md cursor-pointer"
                                    src="/assets/logo/ig-logo.png"
                                    alt=""
                                />
                                <img
                                    class="object-cover w-16 rounded-md cursor-pointer"
                                    src="/assets/logo/tiktok-logo.png"
                                    alt=""
                                />
                                <img
                                    class="object-cover w-16 rounded-md cursor-pointer"
                                    src="/assets/logo/x-logo.png"
                                    alt=""
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- section 2 -->
        </div>
    </div>
</template>
