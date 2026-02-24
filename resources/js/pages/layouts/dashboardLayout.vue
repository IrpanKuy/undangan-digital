<script setup>
import { usePage, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { computed, onMounted, onUnmounted, ref, watch } from "vue";
import { Icon } from "@iconify/vue";

// ─── State Management ────────────────────────────────────────────────────────
const screenWidth = ref(0);
const showSidebar = ref(true);

// ─── Resize Handler (Bug fix dari kode sebelumnya) ───────────────────────────
const handleResize = () => {
    screenWidth.value = window.innerWidth;
    if (window.innerWidth >= 768) {
        showSidebar.value = true;
    } else {
        showSidebar.value = false;
    }
};

onMounted(() => {
    // Set inisial ukuran layar
    handleResize();
    // Dengarkan perubahan ukuran layar
    window.addEventListener("resize", handleResize);
});

onUnmounted(() => {
    window.removeEventListener("resize", handleResize);
});

const closeSidebar = () => {
    if (screenWidth.value < 768) {
        showSidebar.value = false;
    }
};

// ─── Logout Logic ────────────────────────────────────────────────────────────
const logout = () => {
    Swal.fire({
        title: "Yakin ingin logout?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Ya, Logout",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(route("logout"));
        }
    });
};

// ─── Flash Message Watcher ───────────────────────────────────────────────────
const page = usePage();
watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) {
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: flash.success,
                timer: 2500,
                showConfirmButton: false,
                toast: true,
                position: "top-end",
            });
        }
        if (flash?.error) {
            Swal.fire("Gagal!", flash.error, "error");
        }
    },
    { immediate: true },
);

// ─── Computed Classes ────────────────────────────────────────────────────────
const isDesktopAndSidebarOpen = computed(() => {
    return showSidebar.value && screenWidth.value >= 768;
});
</script>

<template>
    <div class="font-poppins min-h-screen bg-gray-50 text-gray-800">
        <!-- ═══════════════ OVERLAY (MOBILE ONLY) ═══════════════ -->
        <transition
            enter-active-class="transition-opacity duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-300"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-show="showSidebar && screenWidth < 768"
                class="fixed inset-0 bg-black/50 z-40 md:hidden backdrop-blur-sm"
                @click="closeSidebar"
            ></div>
        </transition>

        <!-- ═══════════════ SIDEBAR ═══════════════ -->
        <transition
            enter-active-class="transition-transform duration-300 ease-in-out"
            enter-from-class="-translate-x-full"
            enter-to-class="translate-x-0"
            leave-active-class="transition-transform duration-300 ease-in-out"
            leave-from-class="translate-x-0"
            leave-to-class="-translate-x-full"
        >
            <aside
                v-show="showSidebar"
                class="fixed inset-y-0 left-0 z-50 w-72 flex flex-col shadow-2xl md:shadow-none"
            >
                <!-- Logo Area -->
                <div
                    class="h-20 bg-white flex justify-center items-center shadow-md shrink-0 px-4"
                >
                    <img
                        src="/assets/logo_up.png"
                        class="w-48 object-contain"
                        alt="logo-undangan-praktis"
                    />
                </div>

                <!-- Scrollable Menu Area -->
                <div
                    class="flex-1 overflow-y-auto bg-linear-to-b from-[#004D31] to-[#005033] custom-scrollbar"
                >
                    <nav class="flex flex-col gap-2 px-3 py-5">
                        <!-- Profile Area -->
                        <div class="bg-[#004D31] border-b border-white/20">
                            <div
                                class="bg-linear-to-br from-emerald-600 to-emerald-800 rounded-md p-3 shadow-inner"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-12 h-12 rounded-full overflow-hidden shrink-0 border-2 border-white/30"
                                    >
                                        <img
                                            :src="
                                                $page.props.auth.user
                                                    .profile_path
                                                    ? '/storage/' +
                                                      $page.props.auth.user
                                                          .profile_path
                                                    : 'https://ui-avatars.com/api/?name=' +
                                                      $page.props.auth.user
                                                          .name +
                                                      '&background=004D31&color=fff'
                                            "
                                            alt="Profile"
                                            class="w-full h-full object-cover"
                                        />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="text-white/80 text-xs font-medium"
                                        >
                                            Selamat Datang,
                                        </p>
                                        <p
                                            class="text-white font-semibold truncate text-sm"
                                        >
                                            {{
                                                $page.props.auth.user.name ??
                                                "User"
                                            }}
                                        </p>
                                        <!-- Role Badge (Murni Tailwind CSS) -->
                                        <!-- <span
                                            class="inline-block mt-1 px-2 py-[2px] border border-white/50 rounded-full text-[10px] text-white font-medium uppercase tracking-wider bg-white/10"
                                        >
                                            {{
                                                $page.props.auth.user.role ===
                                                "admin"
                                                    ? "Admin"
                                                    : ($page.props.auth.user
                                                          .langganan
                                                          ?.nama_paket ??
                                                      "Member")
                                            }}
                                        </span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Menggunakan slot untuk menu dari luar -->
                        <slot name="sidebar-menu" />
                    </nav>
                </div>

                <!-- Logout Button Area -->
                <div class="bg-[#004D31] p-4 shrink-0 border-t border-white/20">
                    <button
                        @click="logout"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-white font-medium hover:bg-[#4A60AA] transition-colors duration-200 group"
                    >
                        <Icon
                            icon="mdi:logout"
                            width="22"
                            class="text-white/80 group-hover:text-white transition-colors"
                        />
                        <span>Logout</span>
                    </button>
                </div>
            </aside>
        </transition>

        <!-- ═══════════════ HEADER ═══════════════ -->
        <header
            :class="[
                'fixed top-0 right-0 h-20 bg-white shadow-sm z-30 flex items-center px-4 md:px-6 transition-all duration-300 ease-in-out',
                isDesktopAndSidebarOpen ? 'left-72' : 'left-0',
            ]"
        >
            <div class="flex justify-between items-center w-full">
                <div class="flex items-center gap-4">
                    <!-- Hamburger Button (Murni Tailwind CSS) -->
                    <button
                        @click="showSidebar = !showSidebar"
                        class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#004D31]/30 transition-colors"
                    >
                        <Icon icon="ci:hamburger-lg" width="26" />
                    </button>

                    <!-- Header Title Slot (Jika dibutuhkan) -->
                    <div
                        class="hidden sm:flex items-center gap-2 font-semibold text-gray-700 text-lg"
                    >
                        <slot name="headerTitle" />
                    </div>
                </div>
            </div>
        </header>

        <!-- ═══════════════ MAIN CONTENT ═══════════════ -->
        <main
            :class="[
                'pt-20 min-h-screen transition-all duration-300 ease-in-out',
                isDesktopAndSidebarOpen ? 'ml-72' : 'ml-0',
            ]"
        >
            <div class="p-4 md:p-8 max-w-7xl bg-gray-50 mx-auto">
                <slot name="content" />
            </div>
        </main>
    </div>
</template>

<style scoped>
/* Kustomisasi scrollbar agar lebih elegan di sidebar hijau */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 20px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: rgba(255, 255, 255, 0.4);
}
</style>
