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
                :href="route('admin.kategori-undangan.index')"
                :class="{
                    'bg-[#F3F4F6]! rounded-r-full text-[#004D31]!':
                        isLinkActive('admin.kategori-undangan.index'),
                    'hover:bg-gray-200/20! text-white!': !isLinkActive(
                        'admin.kategori-undangan.index',
                    ),
                }"
                class="font-medium! transition duration-150 flex items-center justify-between px-3 py-3 rounded-r-full cursor-pointer"
            >
                <div class="flex items-center gap-3">
                    <Icon icon="dashicons:category" width="24" />
                    <div class="font-bold text-lg">Kategori Undangan</div>
                </div>
            </Link>

            <Link
                :href="route('admin.template-undangan.index')"
                :class="{
                    'bg-[#F3F4F6]! rounded-r-full text-[#004D31]!':
                        isLinkActive('admin.template-undangan.index'),
                    'hover:bg-gray-200/20! text-white!': !isLinkActive(
                        'admin.template-undangan.index',
                    ),
                }"
                class="font-medium! transition duration-150 flex items-center justify-between px-3 py-3 rounded-r-full cursor-pointer"
            >
                <div class="flex items-center gap-3">
                    <Icon icon="ic:round-book" width="24" />
                    <div class="font-bold text-lg">Template Undangan</div>
                </div>
            </Link>

            <Link
                :href="route('admin.template-content-undangan.index')"
                :class="{
                    'bg-[#F3F4F6]! rounded-r-full text-[#004D31]!':
                        isLinkActive('admin.template-content-undangan.index'),
                    'hover:bg-gray-200/20! text-white!': !isLinkActive(
                        'admin.template-content-undangan.index',
                    ),
                }"
                class="font-medium! transition duration-150 flex items-center justify-between px-3 py-3 rounded-r-full cursor-pointer"
            >
                <div class="flex items-center gap-3">
                    <Icon
                        icon="mdi:book-open-page-variant-outline"
                        width="24"
                    />
                    <div class="font-bold text-lg">Template Content</div>
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
