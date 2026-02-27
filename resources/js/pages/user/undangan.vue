<script setup>
import UserDashboardLayout from "../layouts/userDashboardLayout.vue";
</script>

<template>
    <UserDashboardLayout>
        <template #headerTitle>
            <div class="font-bold text-lg">Dashboard</div>
        </template>
        <template #content>
            <div class="shadow-md!">
                <div class="p-6">
                    <div class="font-bold text-lg">Dashboard</div>
                </div>
            </div>
        </template>
    </UserDashboardLayout>
</template>
