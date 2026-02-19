<script setup>
import { usePage, Link, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { computed, onMounted, ref, watch } from "vue";

const showSidebar = ref(window.innerWidth >= 768);

const closeSidebar = () => {
    showSidebar.value = false;
};

const logout = () => {
    Swal.fire({
        title: "Yakin ingin logout?",
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(route("logout"));
        }
    });
};

const page = usePage();
watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) {
        }
        if (flash?.error) {
            Swal.fire("Gagal!", flash.error, "error");
        }
    },
    { immediate: true },
);

const SIDEBAR_WIDTH = "18rem";
const screenWidth = ref(0);

onMounted(() => {
    screenWidth.value = window.innerWidth;
});
const dynamicStyles = computed(() => ({
    // Tentukan lebar sidebar sebagai variabel CSS
    "--sidebar-width": showSidebar.value ? SIDEBAR_WIDTH : "0px",
    transition: "margin-left 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.86)",
}));

const handleResize = () => {
    if (window.innerWidth >= 768) {
        showSidebar.value = true;
    } else {
        showSidebar.value = false;
    }
};

const currentRouteName = computed(() => page.props.currentRoute.name);

// pengkondisian route
const isLinkActive = (routeName) => {
    // return currentRouteName.value === routeName;
    return true;
};
</script>
<template>
    <body class="font-poppins">
        <transition name="fade">
            <div
                v-show="showSidebar"
                class="fixed z-48 inset-0 bg-black/30 md:hidden"
                @click="closeSidebar"
            ></div>
        </transition>
        <transition name="fade-slide">
            <aside
                v-show="showSidebar"
                class="h-screen z-50 fixed w-72 flex flex-col"
            >
                <!-- logo -->
                <div
                    class="p-3! h-20 bg-white flex! justify-center! items-center! shadow-md shrink-0"
                >
                    <img
                        src="assets/logo_up.png"
                        class="bg-cover w-48"
                        alt="logo-undangan-praktis"
                    />
                </div>

                <div class="bg-[#004D31] p-4! border-b border-white/40!">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center"
                        >
                            <Icon
                                icon="mdi:account"
                                class="text-white"
                                width="28"
                            />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white/70 text-sm">Selamat Datang,</p>
                            <p class="text-white font-semibold truncate">
                                User
                            </p>
                            <v-chip
                                size="x-small"
                                color="white"
                                variant="outlined"
                                class="mt-1 text-capitalize"
                            >
                                Free
                            </v-chip>
                        </div>
                    </div>
                </div>

                <!-- Scrollable Menu Area -->
                <div
                    class="bg-linear-to-b flex-1 overflow-y-auto from-[#004D31] to-[#005033]"
                >
                    <div class="flex flex-col gap-4 p-5!">
                        <slot name="sidebar-menu" />

                        <Link
                            :class="{
                                'bg-[#017c4f]!': isLinkActive(
                                    'mentor-request.index',
                                ),
                                'hover:bg-[#005400]! ': !isLinkActive(
                                    'mentor-request.index',
                                ),
                            }"
                            class="text-white font-medium! transition duration-150 flex items-center justify-between px-3 py-2 rounded-md"
                        >
                            <div class="flex items-center gap-3">
                                <!-- <Icon icon="mdi:account-switch" width="24" /> -->
                                <div>Ganti Pembimbing</div>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Fixed Logout Button -->
                <!-- <div
                    class="bg-[#1a398f] p-5! shrink-0 border-t-2 border-white/30"
                >
                    <div
                        @click="logout"
                        class="text-white cursor-pointer hover:bg-[#4A60AA]! font-medium! transition duration-150 flex items-center gap-3 px-3 py-2 rounded-md"
                    >
                        <Icon icon="mdi:logout" width="24" />
                        <div>Logout</div>
                    </div>
                </div> -->
            </aside>
        </transition>
        <!-- header desktop -->
        <transition name="fade-slide">
            <header
                class="h-20 z-5! bg-white hidden md:inline shadow-sm fixed w-screen px-6!"
            >
                <div
                    :style="{
                        'margin-left': showSidebar ? SIDEBAR_WIDTH : '0px',
                    }"
                    class="flex justify-between items-center h-full transition-[margin-left] duration-200"
                >
                    <div class="flex items-center gap-4">
                        <!-- <v-btn
                            icon
                            variant="text"
                            @click="showSidebar = !showSidebar"
                        >
                            <Icon icon="ci:hamburger-lg" width="28" />
                        </v-btn> -->
                        <div
                            class="text-xl flex items-center gap-1 font-medium"
                        >
                            <slot name="headerTitle" />
                        </div>
                    </div>
                </div>
            </header>
        </transition>
        <!-- header mobile -->
        <div class="h-20 z-49 md:hidden bg-white shadow-sm fixed w-screen px-6">
            <div class="flex justify-between items-center h-full">
                <div class="flex items-center">
                    <!-- <v-btn
                        icon
                        variant="text"
                        @click="showSidebar = !showSidebar"
                    >
                        <Icon icon="ci:hamburger-lg" width="28" />
                    </v-btn> -->
                    <h2 class="font-bold text-xl">
                        <slot name="headerTitle" />
                    </h2>
                </div>
            </div>
        </div>

        <!-- slot -->
        <div
            :style="{
                marginLeft:
                    showSidebar && screenWidth >= 768 ? SIDEBAR_WIDTH : '0px',
            }"
            class="pt-20! transition-[margin-left] duration-200"
        >
            <div class="p-2! md:p-8!">
                <slot name="content" />
            </div>
        </div>
    </body>
</template>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.86);
}
/* Status awal saat muncul (enter-from) & status akhir saat menghilang (leave-to) */
.fade-slide-enter-from,
.fade-slide-leave-to {
    transform: translateX(-100%);
}

/* Status normal (di layar) */
.fade-slide-enter-to,
.fade-slide-leave-from {
    transform: translateX(0);
}
</style>
