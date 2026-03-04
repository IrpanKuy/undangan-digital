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
        // Tambahkan default zoom 13 agar tidak error
        default: () => ({ lat: -6.2088, lng: 106.8456, zoom: 13 }),
    },
});

const emit = defineEmits(["update:modelValue"]);

const mapContainer = ref(null);
let map = null;
let marker = null;

// Fungsi helper agar emit lebih rapi dan konsisten
const emitUpdate = (lat, lng, zoom) => {
    emit("update:modelValue", { lat, lng, zoom });
};

onMounted(() => {
    // 1. Inisialisasi map menggunakan zoom dari props (bukan hardcode 13)
    map = L.map(mapContainer.value).setView(
        [props.modelValue.lat, props.modelValue.lng],
        props.modelValue.zoom || 13,
    );

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution:
            '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    marker = L.marker([props.modelValue.lat, props.modelValue.lng], {
        draggable: true,
    }).addTo(map);

    // 2. Event saat marker selesai digeser
    marker.on("dragend", (event) => {
        const position = event.target.getLatLng();
        emitUpdate(position.lat, position.lng, map.getZoom());
    });

    // 3. Event saat peta diklik
    map.on("click", (event) => {
        const position = event.latlng;
        marker.setLatLng(position);
        emitUpdate(position.lat, position.lng, map.getZoom());
    });

    // 4. EVENT BARU: Saat user men-zoom peta lewat mouse / sentuhan
    map.on("zoomend", () => {
        const position = marker.getLatLng();
        emitUpdate(position.lat, position.lng, map.getZoom());
    });
});

watch(
    () => props.modelValue,
    (newVal) => {
        if (map && marker) {
            const currentPos = marker.getLatLng();
            const currentZoom = map.getZoom();

            // Cek apakah koordinat berubah
            if (
                currentPos.lat !== Number(newVal.lat) ||
                currentPos.lng !== Number(newVal.lng)
            ) {
                marker.setLatLng([newVal.lat, newVal.lng]);
                map.panTo([newVal.lat, newVal.lng]);
            }

            // Cek apakah zoom berubah dari parent (dari input number)
            if (
                newVal.zoom !== undefined &&
                currentZoom !== Number(newVal.zoom)
            ) {
                map.setZoom(Number(newVal.zoom));
            }
        }
    },
    { deep: true },
);
</script>

<template>
    <div class="relative z-0">
        <div
            ref="mapContainer"
            style="height: 300px; width: 100%; border-radius: 8px"
        ></div>
    </div>
</template>

<style>
/* Leaflet map styles can be added here if needed */
</style>
