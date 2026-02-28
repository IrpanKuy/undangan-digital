<script setup>
import DashboardLayout from "./dashboardLayout.vue";
import { usePage, Link, router } from "@inertiajs/vue3";
import { computed, onMounted, ref, watch } from "vue";

const page = usePage();
const currentRouteName = computed(() => page.props.currentRoute.name);

// pengkondisian route
const isLinkActive = (routeName) => {
    return currentRouteName.value === routeName;
};
</script>

<template>
    <DashboardLayout>
        <template #sidebar-menu>
            <Link
                :href="route('user.undangan.index')"
                :class="{
                    'bg-[#F3F4F6]! rounded-r-full text-[#004D31]':
                        isLinkActive('user.undangan.index') ||
                        isLinkActive('user.undangan.pilih-template'),
                    'hover:bg-gray-200/20! text-white!':
                        !isLinkActive('user.undangan.index') &&
                        !isLinkActive('user.undangan.pilih-template'),
                }"
                class="font-medium! transition duration-150 flex items-center justify-between px-3 py-3 rounded-r-full cursor-pointer"
            >
                <div class="flex items-center gap-3">
                    <Icon icon="dashicons:category" width="24" />
                    <div class="font-bold text-lg">Undangan Saya</div>
                </div>
            </Link>
        </template>

        <template #headerTitle>
            <slot name="headerTitle" />
        </template>
        <template #content>
            <div class="shadow-md!">
                <slot name="content" />
            </div>
        </template>
    </DashboardLayout>
</template>
