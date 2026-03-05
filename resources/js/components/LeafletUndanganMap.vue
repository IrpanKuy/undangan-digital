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
    latitude: [String, Number],
    longitude: [String, Number],
    zoom: [String, Number],
});

const mapContainer = ref(null);
let map = null;
let marker = null;

onMounted(() => {
    if (!props.latitude || !props.longitude) return;

    const lat = parseFloat(props.latitude);
    const lng = parseFloat(props.longitude);
    const zoom = parseInt(props.zoom) || 13;

    map = L.map(mapContainer.value, {
        zoomControl: false,
        dragging: false,
        touchZoom: false,
        doubleClickZoom: false,
        scrollWheelZoom: false,
        boxZoom: false,
        keyboard: false,
    }).setView([lat, lng], zoom);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution:
            '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    marker = L.marker([lat, lng]).addTo(map);
});

watch(
    () => [props.latitude, props.longitude, props.zoom],
    ([newLat, newLng, newZoom]) => {
        if (map && marker && newLat && newLng) {
            const lat = parseFloat(newLat);
            const lng = parseFloat(newLng);
            const zoom = parseInt(newZoom) || 13;

            marker.setLatLng([lat, lng]);
            map.setView([lat, lng], zoom);
        }
    },
);
</script>

<template>
    <div class="relative z-0 w-full h-full">
        <div
            ref="mapContainer"
            class="w-full h-full rounded-lg overflow-hidden border border-gray-200 shadow-sm"
            style="min-height: 200px"
        ></div>
    </div>
</template>

<style>
/* Ensure the map container has height if not provided by parent */
.leaflet-container {
    height: 100%;
    width: 100%;
}
</style>
