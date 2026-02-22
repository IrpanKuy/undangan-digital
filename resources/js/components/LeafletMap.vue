<script setup>
import { ref, onMounted, watch } from "vue";
import L from "leaflet";
import "leaflet/dist/leaflet.css";

// Fix for default marker icons in Leaflet with Vite/Webpack
import markerIcon from "leaflet/dist/images/marker-icon.png";
import markerIcon2x from "leaflet/dist/images/marker-icon-2x.png";
import markerShadow from "leaflet/dist/images/marker-shadow.png";

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow,
});

const props = defineProps({
    modelValue: {
        type: Object,
        default: () => ({ lat: -6.2088, lng: 106.8456 }), // Default Jakarta
    },
});

const emit = defineEmits(["update:modelValue"]);

const mapContainer = ref(null);
let map = null;
let marker = null;

onMounted(() => {
    map = L.map(mapContainer.value).setView(
        [props.modelValue.lat, props.modelValue.lng],
        13,
    );

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution:
            '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    marker = L.marker([props.modelValue.lat, props.modelValue.lng], {
        draggable: true,
    }).addTo(map);

    marker.on("dragend", (event) => {
        const position = event.target.getLatLng();
        emit("update:modelValue", { lat: position.lat, lng: position.lng });
    });

    map.on("click", (event) => {
        const position = event.latlng;
        marker.setLatLng(position);
        emit("update:modelValue", { lat: position.lat, lng: position.lng });
    });
});

watch(
    () => props.modelValue,
    (newVal) => {
        if (map && marker) {
            const currentPos = marker.getLatLng();
            if (
                currentPos.lat !== newVal.lat ||
                currentPos.lng !== newVal.lng
            ) {
                marker.setLatLng([newVal.lat, newVal.lng]);
                map.panTo([newVal.lat, newVal.lng]);
            }
        }
    },
    { deep: true },
);
</script>

<template>
    <div
        ref="mapContainer"
        style="height: 300px; width: 100%; border-radius: 8px"
    ></div>
</template>

<style>
/* Leaflet map styles can be added here if needed */
</style>
