<script setup>
import DashboardLayout from "./dashboardLayout.vue";
</script>

<template>
    <DashboardLayout>
        hai
        <template #content>
            <slot />
        </template>
    </DashboardLayout>
</template>
