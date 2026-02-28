<script setup>
import UserDashboardLayout from "../../layouts/userDashboardLayout.vue";
</script>

<template>
    <UserDashboardLayout>
        <template #headerTitle>
            <div class="flex items-center gap-2">
                <Icon
                    icon="mdi:email-multiple-outline"
                    width="22"
                    class="text-[#004D31]"
                />
                <span class="font-semibold text-gray-800">Undangan Saya</span>
            </div>
        </template>

        <template #content>
            <div class="w-full p-2 md:p-5 bg-white">
                <!-- Page Header -->
                <div
                    class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8"
                >
                    <div>
                        <h1 class="text-xl font-bold text-gray-800 mb-1">
                            Pilih Template
                        </h1>
                        <p class="text-sm text-gray-600">
                            Pilih template yang sesuai dengan kebutuhan Anda.
                        </p>
                    </div>
                </div>

                <!-- grid -->
                <div
                    class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-4 border rounded-lg border-gray-200 bg-gray-50"
                >
                    <div
                        class="group relative flex flex-col bg-white rounded-2xl overflow-hidden shadow-sm transition-all duration-300 border border-primary/70"
                    >
                        <div
                            class="relative aspect-3/4 p-3 flex justify-center bg-gray-200 items-center overflow-hidden"
                        >
                            <div
                                class="absolute top-2 left-2 z-20 flex items-center gap-1 bg-black/60 backdrop-blur-md px-2 py-1 rounded-full border border-white/20"
                            >
                                <Icon
                                    icon="material-symbols:crown"
                                    width="12"
                                    class="text-yellow-400"
                                />
                                <span
                                    class="text-[10px] font-medium text-white uppercase tracking-wider"
                                    >Premium</span
                                >
                            </div>

                            <div
                                class="relative w-full h-full max-w-[140px] mx-auto transition-transform duration-500 group-hover:scale-105"
                            >
                                <img
                                    src="/assets/handphone.png"
                                    class="w-full h-full absolute top-0 left-0 z-10 object-contain pointer-events-none"
                                    alt=""
                                />
                                <img
                                    src="https://placehold.co/400x800"
                                    class="w-[60%] md:w-[80%] h-[96%] mt-[2%] mx-auto object-cover"
                                    alt="Template Preview"
                                />
                            </div>
                        </div>

                        <div class="p-2 md:p-3 flex flex-col gros">
                            <h2
                                class="text-sm md:text-base font-semibold text-gray-800 line-clamp-2 mb-3 text-center"
                            >
                                Blue Soft Premium 1
                            </h2>

                            <div
                                class="flex flex-col md:flex-row items-center gap-1 mt-auto"
                            >
                                <button
                                    class="flex-1 w-full md:w-auto flex items-center justify-center gap-1 py-[6px] px-1 border border-primary/20 bg-primary/5 hover:bg-primary/10 rounded-lg transition-colors group/btn"
                                >
                                    <Icon
                                        icon="mdi:eye"
                                        width="16"
                                        class="text-primary"
                                    />
                                    <span
                                        class="text-[11px] font-bold text-primary"
                                        >Preview</span
                                    >
                                </button>

                                <button
                                    class="flex-1 w-full md:w-auto flex items-center justify-center gap-1 py-[6px] px-1 bg-primary hover:bg-primary-dark rounded-lg shadow-sm shadow-primary/30 transition-all active:scale-95"
                                >
                                    <Icon
                                        icon="gridicons:create"
                                        width="14"
                                        class="text-white"
                                    />
                                    <span
                                        class="text-[11px] font-bold text-white"
                                        >Gunakan</span
                                    >
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </UserDashboardLayout>
</template>
