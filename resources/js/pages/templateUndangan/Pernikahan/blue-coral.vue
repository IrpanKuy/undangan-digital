<script setup>
import {
    ref,
    reactive,
    onMounted,
    onUnmounted,
    computed,
    watch,
    nextTick,
} from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import LeafletUndanganMap from "@/components/LeafletUndanganMap.vue";
import AOS from "aos";

const props = defineProps({
    undangan: Object,
    acara: Object,
    dataMempelai: Object,
    templateUndanganPernikahan: Object,
    kisahCinta: Object,
    galleryUndangan: Object,
    komentarUndangan: Object,
    isPreview: {
        type: Boolean,
        default: false,
    },
});

const isPreview = ref(props.isPreview);
console.log("undangan:", props.undangan);
console.log("acara:", props.acara);
console.log("dataMempelai:", props.dataMempelai);
console.log("templateUndanganPernikahan:", props.templateUndanganPernikahan);
console.log("kisahCinta:", props.kisahCinta);
console.log("galleryUndangan:", props.galleryUndangan);

const galleries = computed(() => {
    if (!props.galleryUndangan) return [];
    return Array.isArray(props.galleryUndangan)
        ? props.galleryUndangan
        : Object.values(props.galleryUndangan);
});

// State Index
const currentIndex = ref(0);

// Navigasi
const nextImage = () => {
    if (galleries.value.length === 0) return;
    currentIndex.value = (currentIndex.value + 1) % galleries.value.length;
};

const prevImage = () => {
    if (galleries.value.length === 0) return;
    currentIndex.value =
        (currentIndex.value - 1 + galleries.value.length) %
        galleries.value.length;
};

const setImage = (index) => {
    currentIndex.value = index;
};

// Helper Image URL
const getImageUrl = (item) => {
    if (!item || !item.image_path) return "/assets/placeholder.jpg";
    return `/storage/${item.image_path}`;
};

const countdown = reactive({
    hari: 0,
    jam: 0,
    menit: 0,
    detik: 0,
});

let timer = null;

const updateCountdown = () => {
    const targetDate = props.templateUndanganPernikahan?.tanggal_mulai_indo;

    if (!targetDate) return;

    const target = new Date(targetDate).getTime();
    const now = new Date().getTime();
    const gap = target - now;

    if (gap > 0) {
        // Logika matematika untuk memecah milidetik
        countdown.hari = Math.floor(gap / (1000 * 60 * 60 * 24));
        countdown.jam = Math.floor(
            (gap % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60),
        );
        countdown.menit = Math.floor((gap % (1000 * 60 * 60)) / (1000 * 60));
        countdown.detik = Math.floor((gap % (1000 * 60)) / 1000);
    } else {
        // Jika waktu sudah lewat
        countdown.hari = 0;
        countdown.jam = 0;
        countdown.menit = 0;
        countdown.detik = 0;
        clearInterval(timer);
    }
};

const commentForm = useForm({
    nama: "",
    pesan: "",
});

const submitComment = () => {
    if (isPreview.value) {
        Swal.fire({
            icon: "warning",
            title: "Mode Preview",
            text: "Anda tidak dapat mengirim ucapan dalam mode preview.",
        });
        return;
    }

    commentForm.post(route("undangan.komentar.store", props.undangan.id), {
        onSuccess: () => {
            commentForm.reset();
            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: "Ucapan Anda telah terkirim!",
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
            });
        },
    });
};

const showRsvpForm = ref(false);
const rsvpForm = useForm({
    nama: "",
    jumlah_hadir: 1,
    status_kehadiran: "Hadir",
});

const submitRsvp = () => {
    if (isPreview.value) {
        Swal.fire({
            icon: "warning",
            title: "Mode Preview",
            text: "Anda tidak dapat melakukan reservasi dalam mode preview.",
        });
        return;
    }

    rsvpForm.post(route("undangan.reservasi.store", props.undangan.id), {
        onSuccess: () => {
            showRsvpForm.value = false;
            rsvpForm.reset();
            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: "Reservasi Anda telah terkirim!",
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
            });
        },
    });
};

const isOpen = ref(true);

// Watcher untuk memastikan AOS me-refresh elemen yang baru muncul setelah cover hilang
watch(isOpen, async (newVal) => {
    if (!newVal) {
        await nextTick();
        // Beri jeda agar animasi transisi Vue selesai, lalu jalankan refresh AOS
        setTimeout(() => {
            AOS.refresh();
        }, 800);
    }
});

onMounted(() => {
    AOS.init({
        duration: 1200, // Durasi sedikit lebih lambat agar elegan
        once: true, // Animasi cukup diputar 1x saat pertama kali discroll
        easing: "ease-out-cubic",
    });

    // Detect if we are in preview mode based on URL or logic if not passed via props
    if (window.location.pathname.includes("/preview/")) {
        isPreview.value = true;
    }
    updateCountdown();
    timer = setInterval(updateCountdown, 1000); // Update setiap 1 detik
});

onUnmounted(() => {
    clearInterval(timer); // Bersihkan memory saat pindah halaman
});
</script>

<template>
    <div
        class="bg-linear-to-b from-[#ffffff] to-[#E8F5FF] -z-50 w-full min-h-screen overflow-x-hidden"
    >
        <transition name="invite-transition" mode="out-in">
            <!-- Buka undangan -->
            <div v-if="isOpen == true" key="cover">
                <div
                    class="mx-auto relative flex flex-col justify-center items-center text-center w-full h-screen max-w-120 bg-linear-to-b from-[#ffffff] to-[#E8F5FF]"
                >
                    <div class="z-0">
                        <img
                            data-aos="fade-down"
                            data-aos-duration="1500"
                            class="w-70 absolute top-0 right-0 object-cover inline"
                            src="/assets/bunga/bunga-1.png"
                            alt=""
                        />
                        <img
                            data-aos="fade-up"
                            data-aos-duration="1500"
                            class="w-70 rotate-180 absolute bottom-0 left-0 object-cover inline"
                            src="/assets/bunga/bunga-1.png"
                            alt=""
                        />
                    </div>
                    <div class="z-10 flex flex-col items-center space-y-14">
                        <h2
                            data-aos="fade-down"
                            data-aos-delay="200"
                            class="font-bold font-lora text-4xl z-50 italic"
                        >
                            The Wedding Of
                        </h2>
                        <div
                            data-aos="zoom-in"
                            data-aos-delay="400"
                            class="flex flex-col text-5xl font-alex-brush space-y-2"
                        >
                            <h4>
                                {{
                                    dataMempelai?.nama_panggilan_pria ??
                                    "belum diatur"
                                }}
                            </h4>
                            <p>&</p>
                            <h4>
                                {{
                                    dataMempelai?.nama_panggilan_wanita ??
                                    "belum diatur"
                                }}
                            </h4>
                        </div>
                        <div
                            class="relative"
                            data-aos="flip-left"
                            data-aos-delay="600"
                        >
                            <img
                                :src="
                                    undangan?.thumbnail_path
                                        ? `/storage/${undangan.thumbnail_path}`
                                        : 'https://placehold.co/400x400'
                                "
                                alt="Foto Profil Manusia"
                                class="w-40 h-40 z-10 rounded-full border-2 border-gray-200 object-cover shadow-sm"
                            />
                            <img
                                src="/assets/bingkai/bingkai1.png"
                                alt="bingkai"
                                class="w-full h-full scale-150 absolute inset-0 z-0 object-contain"
                            />
                        </div>
                        <button
                            data-aos="fade-up"
                            data-aos-delay="800"
                            data-aos-offset="0"
                            @click="isOpen = false"
                            class="px-4 py-3 cursor-pointer flex rounded-full justify-center items-center gap-3 bg-[#6D99BD] hover:bg-[#5a80a1] transition-colors text-white"
                        >
                            <icon icon="ion:book-sharp" width="20" />
                            <p>Buka Undangan</p>
                        </button>
                    </div>
                </div>
            </div>

            <div v-else key="content">
                <!-- section 1 -->
                <div
                    class="relative mx-auto flex flex-col items-center text-center w-full max-w-120 bg-linear-to-b from-[#ffffff] to-[#E8F5FF]"
                >
                    <div class="z-0 pointer-events-none">
                        <img
                            class="w-70 absolute top-0 right-0 object-cover"
                            src="/assets/bunga/bunga-1.png"
                            alt=""
                        />
                        <img
                            class="w-70 rotate-180 absolute bottom-0 left-0 object-cover"
                            src="/assets/bunga/bunga-1.png"
                            alt=""
                        />
                    </div>
                    <div class="relative min-h-full px-10 py-20">
                        <div>
                            <div
                                class="relative z-10 flex flex-col items-center space-y-14"
                            >
                                <h2
                                    data-aos="fade-down"
                                    class="font-bold font-lora text-3xl z-50 italic"
                                >
                                    Undangan Pernikahan
                                </h2>

                                <div
                                    class="relative"
                                    data-aos="zoom-in"
                                    data-aos-delay="200"
                                >
                                    <img
                                        :src="
                                            undangan?.thumbnail_path
                                                ? `/storage/${undangan.thumbnail_path}`
                                                : 'https://placehold.co/400x400'
                                        "
                                        alt="Foto Profil Manusia"
                                        class="w-40 h-40 z-10 rounded-full border-2 border-gray-200 object-cover shadow-sm"
                                    />
                                    <img
                                        src="/assets/bingkai/bingkai1.png"
                                        alt="Foto Profil Manusia"
                                        class="w-full h-full scale-150 absolute inset-0 z-0 object-contain"
                                    />
                                </div>
                                <div
                                    class="flex flex-col text-5xl font-alex-brush space-y-2"
                                >
                                    <h4
                                        data-aos="fade-right"
                                        data-aos-delay="300"
                                    >
                                        {{
                                            dataMempelai?.nama_panggilan_pria ??
                                            "belum diatur"
                                        }}
                                    </h4>
                                    <p data-aos="fade-in" data-aos-delay="400">
                                        &
                                    </p>
                                    <h4
                                        data-aos="fade-left"
                                        data-aos-delay="500"
                                    >
                                        {{
                                            dataMempelai?.nama_panggilan_wanita ??
                                            "belum diatur"
                                        }}
                                    </h4>
                                </div>
                                <div class="space-y-6">
                                    <p
                                        data-aos="fade-up"
                                        data-aos-delay="600"
                                        class="font-montserrat text-xl"
                                    >
                                        Akan menyelenggarakan acara
                                        <br />pernikahan pada
                                    </p>
                                    <h2
                                        data-aos="zoom-in"
                                        data-aos-delay="700"
                                        class="font-bold font-lora text-3xl"
                                    >
                                        {{
                                            templateUndanganPernikahan?.tanggal_mulai_indo ??
                                            "belum diatur"
                                        }}
                                    </h2>
                                    <p
                                        data-aos="fade-up"
                                        data-aos-delay="800"
                                        class="font-montserrat text-xl"
                                    >
                                        Acara akan dimulai dalam
                                    </p>
                                    <div class="flex gap-4">
                                        <div
                                            data-aos="flip-left"
                                            data-aos-delay="800"
                                            class="w-16 h-16 flex justify-center items-center rounded-lg flex-col p-1 bg-linear-to-br from-[#5A85A8] to-[#AAC8E0] text-white font-lora"
                                        >
                                            <div
                                                class="text-[10px] uppercase font-medium"
                                            >
                                                Hari
                                            </div>
                                            <p class="font-bold text-xl">
                                                {{ countdown.hari }}
                                            </p>
                                        </div>

                                        <div
                                            data-aos="flip-left"
                                            data-aos-delay="900"
                                            class="w-16 h-16 flex justify-center items-center rounded-lg flex-col p-1 bg-linear-to-br from-[#5A85A8] to-[#AAC8E0] text-white font-lora"
                                        >
                                            <div
                                                class="text-[10px] uppercase font-medium"
                                            >
                                                Jam
                                            </div>
                                            <p class="font-bold text-xl">
                                                {{ countdown.jam }}
                                            </p>
                                        </div>

                                        <div
                                            data-aos="flip-left"
                                            data-aos-delay="1000"
                                            class="w-16 h-16 flex justify-center items-center rounded-lg flex-col p-1 bg-linear-to-br from-[#5A85A8] to-[#AAC8E0] text-white font-lora"
                                        >
                                            <div
                                                class="text-[10px] uppercase font-medium"
                                            >
                                                Menit
                                            </div>
                                            <p class="font-bold text-xl">
                                                {{ countdown.menit }}
                                            </p>
                                        </div>

                                        <div
                                            data-aos="flip-left"
                                            data-aos-delay="1100"
                                            class="w-16 h-16 flex justify-center items-center rounded-lg flex-col p-1 bg-linear-to-br from-[#5A85A8] to-[#AAC8E0] text-white font-lora"
                                        >
                                            <div
                                                class="text-[10px] uppercase font-medium"
                                            >
                                                Detik
                                            </div>
                                            <p class="font-bold text-xl">
                                                {{ countdown.detik }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- section 1 -->

                <!-- section 2 -->
                <div
                    class="relative mx-auto flex flex-col items-center text-center w-full max-w-120 bg-linear-to-b from-[#ffffff] to-[#E8F5FF]"
                >
                    <div class="z-0 pointer-events-none">
                        <img
                            class="w-70 absolute top-0 right-0 object-cover"
                            src="/assets/bunga/bunga-1.png"
                            alt=""
                        />
                        <img
                            class="w-70 rotate-180 absolute bottom-0 left-0 object-cover"
                            src="/assets/bunga/bunga-1.png"
                            alt=""
                        />
                    </div>
                    <div class="relative min-h-full px-10 py-20">
                        <div
                            class="relative z-10 flex flex-col items-center space-y-14"
                        >
                            <h2
                                data-aos="fade-up"
                                class="font-bold font-lora text-3xl z-50 italic"
                            >
                                Assalamualaikum Wr.Br
                            </h2>
                            <p
                                data-aos="fade-up"
                                data-aos-delay="150"
                                class="font-montserrat text-xl"
                            >
                                Puji sykur kehadirat Allah sehingga
                                terlaksanakan momen bahagia pada acara
                                pernikahan ini
                            </p>

                            <!-- foto wanita -->
                            <div data-aos="fade-right" data-aos-delay="200">
                                <div class="relative">
                                    <img
                                        :src="
                                            dataMempelai?.foto_wanita_path
                                                ? `/storage/${dataMempelai.foto_wanita_path}`
                                                : 'https://placehold.co/400x400'
                                        "
                                        alt="Foto Profil Manusia"
                                        class="w-40 h-40 z-10 rounded-full border-2 border-gray-200 object-cover shadow-sm"
                                    />
                                    <img
                                        src="/assets/bingkai/bingkai1.png"
                                        alt="Foto Profil Manusia"
                                        class="w-full h-full scale-150 absolute inset-0 z-0 object-contain"
                                    />
                                </div>
                            </div>
                            <div
                                data-aos="fade-left"
                                data-aos-delay="300"
                                class="space-y-3 mb-25"
                            >
                                <h2 class="text-5xl font-alex-brush">
                                    {{
                                        dataMempelai?.nama_lengkap_wanita ??
                                        "belum diatur"
                                    }}
                                </h2>
                                <p class="font-montserrat text-xl">
                                    {{
                                        dataMempelai?.keterangan_keluarga_wanita ??
                                        "belum diatur"
                                    }}
                                </p>
                                <div class="flex justify-center gap-4">
                                    <img
                                        v-if="
                                            dataMempelai?.instagram_url_wanita
                                        "
                                        class="object-cover w-12 rounded-md cursor-pointer hover:scale-110 transition-transform"
                                        src="/assets/logo/ig-logo.png"
                                        alt=""
                                    />
                                    <img
                                        v-if="dataMempelai?.tiktok_url_wanita"
                                        class="object-cover w-12 rounded-md cursor-pointer hover:scale-110 transition-transform"
                                        src="/assets/logo/tiktok-logo.png"
                                        alt=""
                                    />
                                    <img
                                        v-if="dataMempelai?.x_url_wanita"
                                        class="object-cover w-12 rounded-md cursor-pointer hover:scale-110 transition-transform"
                                        src="/assets/logo/x-logo.png"
                                        alt=""
                                    />
                                </div>
                            </div>

                            <!-- foto pria -->
                            <div data-aos="fade-left" data-aos-delay="400">
                                <div class="relative">
                                    <img
                                        :src="
                                            dataMempelai?.foto_pria_path
                                                ? `/storage/${dataMempelai.foto_pria_path}`
                                                : 'https://placehold.co/400x400'
                                        "
                                        alt="Foto Profil Pria"
                                        class="w-40 h-40 z-10 rounded-full border-2 border-gray-200 object-cover shadow-sm"
                                    />
                                    <img
                                        src="/assets/bingkai/bingkai1.png"
                                        alt="Foto Profil Pria"
                                        class="w-full h-full scale-150 absolute inset-0 z-0 object-contain"
                                    />
                                </div>
                            </div>
                            <div
                                data-aos="fade-right"
                                data-aos-delay="500"
                                class="space-y-3"
                            >
                                <h2 class="text-5xl font-alex-brush">
                                    {{
                                        dataMempelai?.nama_lengkap_pria ??
                                        "belum diatur"
                                    }}
                                </h2>
                                <p class="font-montserrat text-xl">
                                    {{
                                        dataMempelai?.keterangan_keluarga_pria ??
                                        "belum diatur"
                                    }}
                                </p>
                                <div class="flex justify-center gap-4">
                                    <img
                                        v-if="dataMempelai?.instagram_url_pria"
                                        class="object-cover w-12 rounded-md cursor-pointer hover:scale-110 transition-transform"
                                        src="/assets/logo/ig-logo.png"
                                        alt=""
                                    />
                                    <img
                                        v-if="dataMempelai?.tiktok_url_pria"
                                        class="object-cover w-12 rounded-md cursor-pointer hover:scale-110 transition-transform"
                                        src="/assets/logo/tiktok-logo.png"
                                        alt=""
                                    />
                                    <img
                                        v-if="dataMempelai?.x_url_pria"
                                        class="object-cover w-12 rounded-md cursor-pointer hover:scale-110 transition-transform"
                                        src="/assets/logo/x-logo.png"
                                        alt=""
                                    />
                                </div>
                            </div>

                            <p
                                data-aos="fade-up"
                                data-aos-delay="600"
                                class="font-montserrat text-xl"
                            >
                                Semoga acara ini berakhir lancar dan kedua
                                pasangan bahagia bersama
                            </p>
                        </div>
                    </div>
                </div>
                <!-- section 2 -->

                <!-- section 3 -->
                <div
                    class="relative mx-auto flex flex-col items-center text-center w-full max-w-120 bg-linear-to-b from-[#ffffff] to-[#E8F5FF]"
                >
                    <div class="z-0 pointer-events-none">
                        <img
                            class="w-70 absolute top-0 right-0 object-cover"
                            src="/assets/bunga/bunga-1.png"
                            alt=""
                        />
                        <img
                            class="w-70 rotate-180 absolute bottom-0 left-0 object-cover"
                            src="/assets/bunga/bunga-1.png"
                            alt=""
                        />
                    </div>
                    <div class="relative min-h-full px-10 py-20">
                        <div>
                            <div
                                class="relative z-10 flex flex-col items-center space-y-14"
                            >
                                <div class="space-y-6">
                                    <h2
                                        data-aos="fade-down"
                                        class="font-medium font-alex-brush text-5xl z-50 italic"
                                    >
                                        Save The Date
                                    </h2>
                                    <p
                                        data-aos="fade-up"
                                        data-aos-delay="100"
                                        class="font-montserrat text-xl"
                                    >
                                        Puji sykur kehadirat Allah sehingga
                                        terlaksanakan momen bahagia pada acara
                                        pernikahan ini
                                    </p>
                                    <button
                                        data-aos="zoom-in"
                                        data-aos-delay="200"
                                        class="px-4 py-3 cursor-pointer mx-auto flex rounded-md justify-center items-center gap-3 bg-[#6D99BD] hover:bg-[#7babd3] active:bg-[#6f9dc2] active:scale-95 transition duration-200 text-white"
                                    >
                                        <icon icon="mdi:bell" width="20" />
                                        <p>Atur Pengingat</p>
                                    </button>
                                </div>

                                <!-- acara & proses nikah -->
                                <div class="w-full space-y-6">
                                    <div
                                        data-aos="fade-up"
                                        data-aos-delay="300"
                                        class="bg-white w-full space-y-2 p-6 border-2 rounded-lg border-[#6D99BD] shadow-xs"
                                    >
                                        <h2
                                            class="font-semibold font-lora text-3xl z-50 italic"
                                        >
                                            {{
                                                templateUndanganPernikahan?.nama_prosesi ??
                                                "belum diatur"
                                            }}
                                        </h2>
                                        <div
                                            class="flex justify-center items-center gap-2"
                                        >
                                            <icon
                                                width="20"
                                                icon="fe:calendar"
                                            />
                                            <h2
                                                class="font-medium font-lora text-xl z-50 italic"
                                            >
                                                {{
                                                    templateUndanganPernikahan?.tanggal_mulai_indo ??
                                                    "belum diatur"
                                                }}
                                            </h2>
                                        </div>
                                        <div
                                            class="flex justify-center items-center gap-2"
                                        >
                                            <icon width="20" icon="mdi:clock" />
                                            <h2
                                                class="font-medium font-lora text-xl z-50 italic"
                                            >
                                                Pukul
                                                {{
                                                    templateUndanganPernikahan?.waktu_mulai ??
                                                    "belum diatur"
                                                }}
                                                -
                                                {{
                                                    templateUndanganPernikahan?.waktu_selesai ??
                                                    "Selesai"
                                                }}
                                            </h2>
                                        </div>
                                        <div
                                            v-if="
                                                templateUndanganPernikahan.show_map
                                            "
                                            class="h-48 w-full my-4 rounded-md overflow-hidden"
                                        >
                                            <LeafletUndanganMap
                                                :latitude="
                                                    templateUndanganPernikahan.latitude
                                                "
                                                :longitude="
                                                    templateUndanganPernikahan.longitude
                                                "
                                                :zoom="
                                                    templateUndanganPernikahan.zoom ||
                                                    15
                                                "
                                            />
                                        </div>
                                        <p class="font-montserrat text-xl">
                                            Di jalan raya bagus Masjid Al-Falaq
                                        </p>
                                    </div>
                                    <div
                                        v-for="(item, index) in acara"
                                        :key="item.id"
                                    >
                                        <div
                                            data-aos="fade-up"
                                            :data-aos-delay="400 + index * 150"
                                            class="bg-white w-full space-y-2 p-6 border-2 rounded-lg border-[#6D99BD] shadow-xs"
                                        >
                                            <h2
                                                class="font-semibold font-lora text-3xl z-50 italic text-center"
                                            >
                                                {{
                                                    item.nama_acara ??
                                                    "Nama Acara"
                                                }}
                                            </h2>

                                            <div
                                                class="flex justify-center items-center gap-2"
                                            >
                                                <icon
                                                    width="20"
                                                    icon="fe:calendar"
                                                />
                                                <h2
                                                    class="font-medium font-lora text-xl z-50 italic"
                                                >
                                                    {{
                                                        item.tanggal_acara_indo ??
                                                        "belum diatur"
                                                    }}
                                                </h2>
                                            </div>

                                            <div
                                                class="flex justify-center items-center gap-2"
                                            >
                                                <icon
                                                    width="20"
                                                    icon="mdi:clock"
                                                />
                                                <h2
                                                    class="font-medium font-lora text-xl z-50 italic"
                                                >
                                                    Pukul
                                                    {{
                                                        item.waktu_mulai_acara ??
                                                        "belum diatur"
                                                    }}
                                                    -
                                                    {{
                                                        item.waktu_selesai_acara ??
                                                        "Selesai"
                                                    }}
                                                </h2>
                                            </div>

                                            <div
                                                v-if="item.show_map"
                                                class="h-48 w-full my-4 rounded-md overflow-hidden"
                                            >
                                                <LeafletUndanganMap
                                                    :latitude="
                                                        item.latitude_acara
                                                    "
                                                    :longitude="
                                                        item.longitude_acara
                                                    "
                                                    :zoom="item.zoom || 15"
                                                />
                                            </div>

                                            <p
                                                class="font-montserrat text-xl text-center"
                                            >
                                                {{
                                                    item.detail_lokasi_acara ??
                                                    "Lokasi belum ditentukan"
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- section 3 -->

                <!-- section 4 -->
                <div
                    class="relative mx-auto flex flex-col items-center text-center w-full max-w-120 bg-linear-to-b from-[#ffffff] to-[#E8F5FF]"
                >
                    <div class="z-0 pointer-events-none">
                        <img
                            class="w-70 absolute top-0 right-0 object-cover"
                            src="/assets/bunga/bunga-1.png"
                            alt=""
                        />
                        <img
                            class="w-70 rotate-180 absolute bottom-0 left-0 object-cover"
                            src="/assets/bunga/bunga-1.png"
                            alt=""
                        />
                    </div>
                    <div class="relative min-h-full px-10 py-20">
                        <div class="space-y-8">
                            <div class="space-y-3">
                                <h2
                                    data-aos="fade-down"
                                    class="font-medium font-alex-brush text-5xl z-50 italic"
                                >
                                    Our Story
                                </h2>
                                <p
                                    data-aos="fade-up"
                                    data-aos-delay="100"
                                    class="font-montserrat text-xl"
                                >
                                    Perjalanan kisah cinta kami
                                </p>
                            </div>

                            <!-- our story image -->
                            <div>
                                <div class="mx-auto p-8">
                                    <div
                                        v-for="(story, index) in kisahCinta"
                                        :key="index"
                                        data-aos="fade-up"
                                        :data-aos-delay="200 + index * 150"
                                        class="flex gap-6"
                                    >
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="w-8 h-8 rounded-full bg-[#6D99BD] flex items-center justify-center z-10 shrink-0 border-[3px] border-[#F8FCFF]"
                                            >
                                                <icon
                                                    icon="ion:heart"
                                                    class="text-white text-lg"
                                                />
                                            </div>

                                            <div
                                                v-if="
                                                    index !==
                                                    kisahCinta.length - 1
                                                "
                                                class="w-0.5 bg-black grow opacity-20"
                                            ></div>
                                        </div>

                                        <div
                                            class="pb-12 pt-1 space-y-6 w-full text-left"
                                        >
                                            <h2 class="font-bold text-lg">
                                                {{ story.tanggal }}
                                            </h2>

                                            <div
                                                v-if="
                                                    story.foto_kisah_cinta_path
                                                "
                                                class="relative flex justify-center items-center w-full max-w-[320px] sm:max-w-[380px] mx-auto my-6"
                                            >
                                                <img
                                                    :src="`/storage/${story.foto_kisah_cinta_path}`"
                                                    alt="Story Image"
                                                    class="absolute z-0 object-cover w-[85%] h-[85%] rounded-md shadow-sm"
                                                />

                                                <img
                                                    src="/assets/bingkai/bingkai2.png"
                                                    alt="bingkai"
                                                    class="relative z-10 w-full h-auto pointer-events-none"
                                                />
                                            </div>
                                            <div>
                                                <h3
                                                    class="font-semibold text-lg"
                                                >
                                                    {{ story.judul }}
                                                </h3>
                                                <p
                                                    class="text-gray-600 text-sm leading-relaxed"
                                                >
                                                    {{ story.peristiwa }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-12 overflow-hidden">
                                <div
                                    data-aos="fade-right"
                                    data-aos-delay="200"
                                    class="space-y-3"
                                >
                                    <h2
                                        class="font-bold font-lora text-2xl z-50 italic"
                                    >
                                        Harapan Pengantin Pria
                                    </h2>
                                    <p class="font-montserrat text-xl">
                                        {{
                                            templateUndanganPernikahan?.doa_pengantin_pria ??
                                            "Belum Diatur"
                                        }}
                                    </p>
                                </div>
                                <div
                                    data-aos="fade-left"
                                    data-aos-delay="400"
                                    class="space-y-3"
                                >
                                    <h2
                                        class="font-bold font-lora text-2xl z-50 italic"
                                    >
                                        Harapan Pengantin Wanita
                                    </h2>
                                    <p class="font-montserrat text-xl">
                                        {{
                                            templateUndanganPernikahan?.doa_pengantin_wanita ??
                                            "Belum Diatur"
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- section 4 -->

                <!-- section 5 -->
                <div
                    class="relative mx-auto flex flex-col items-center text-center w-full max-w-120 bg-linear-to-b from-[#ffffff] to-[#E8F5FF]"
                >
                    <div class="z-0 pointer-events-none">
                        <img
                            class="w-70 absolute top-0 right-0 object-cover"
                            src="/assets/bunga/bunga-1.png"
                            alt=""
                        />
                        <img
                            class="w-70 rotate-180 absolute bottom-0 left-0 object-cover"
                            src="/assets/bunga/bunga-1.png"
                            alt=""
                        />
                    </div>

                    <div
                        class="relative min-h-full px-6 sm:px-10 py-20 w-full z-10"
                    >
                        <div class="space-y-12">
                            <h2
                                data-aos="fade-down"
                                class="font-medium font-alex-brush text-5xl italic text-gray-800"
                            >
                                Our Gallery
                            </h2>

                            <div
                                v-if="galleries.length > 0"
                                data-aos="zoom-in"
                                data-aos-delay="200"
                                class="relative flex flex-col justify-center items-center h-[400px] w-full group"
                            >
                                <button
                                    @click="prevImage"
                                    class="absolute left-2 z-30 p-2 bg-white/60 rounded-full hover:bg-white/90 backdrop-blur-md shadow-md transition-all active:scale-90"
                                >
                                    <icon
                                        width="24"
                                        icon="mdi:chevron-left"
                                        class="text-gray-700"
                                    />
                                </button>

                                <transition name="fade" mode="out-in">
                                    <img
                                        :key="currentIndex"
                                        :src="
                                            getImageUrl(galleries[currentIndex])
                                        "
                                        alt="Main Gallery"
                                        class="object-cover absolute w-[240px] h-[320px] rounded-sm z-10 shadow-lg transition-opacity duration-300"
                                    />
                                </transition>

                                <img
                                    src="/assets/bingkai/bingkai-gallery.png"
                                    alt="bingkai"
                                    class="object-cover absolute w-full max-w-[320px] h-auto pointer-events-none z-20 drop-shadow-xl"
                                />

                                <button
                                    @click="nextImage"
                                    class="absolute right-2 z-30 p-2 bg-white/60 rounded-full hover:bg-white/90 backdrop-blur-md shadow-md transition-all active:scale-90"
                                >
                                    <icon
                                        width="24"
                                        icon="mdi:chevron-right"
                                        class="text-gray-700"
                                    />
                                </button>
                            </div>

                            <div
                                v-else
                                class="h-60 flex items-center justify-center text-gray-400 font-montserrat italic"
                            >
                                Belum ada foto yang diunggah.
                            </div>

                            <div
                                v-if="galleries.length > 0"
                                class="grid grid-cols-2 gap-3 sm:gap-4 mt-8"
                            >
                                <div
                                    v-for="(gallery, index) in galleries"
                                    :key="index"
                                    data-aos="flip-up"
                                    :data-aos-delay="100 * index"
                                    @click="setImage(index)"
                                    class="w-full cursor-pointer overflow-hidden rounded-lg border-2 transition-all duration-300 transform"
                                    :class="
                                        currentIndex === index
                                            ? 'border-[#6D99BD] scale-105 shadow-md z-10'
                                            : 'border-transparent hover:scale-105 hover:shadow-sm'
                                    "
                                >
                                    <img
                                        :src="getImageUrl(gallery)"
                                        alt="Thumbnail"
                                        class="w-full h-32 sm:h-40 object-cover"
                                        :class="
                                            currentIndex === index
                                                ? 'opacity-100'
                                                : 'opacity-70 hover:opacity-100'
                                        "
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- section 5 -->

                <!-- section 6 -->
                <div
                    class="relative mx-auto flex flex-col items-center text-center w-full max-w-120 bg-linear-to-b from-[#ffffff] to-[#E8F5FF]"
                >
                    <div class="z-0 pointer-events-none">
                        <img
                            class="w-70 absolute top-0 right-0 object-cover"
                            src="/assets/bunga/bunga-1.png"
                            alt=""
                        />
                        <img
                            class="w-70 rotate-180 absolute bottom-0 left-0 object-cover"
                            src="/assets/bunga/bunga-1.png"
                            alt=""
                        />
                    </div>
                    <div class="relative w-full min-h-full px-10 py-20">
                        <div class="space-y-12">
                            <h2
                                data-aos="fade-down"
                                class="font-medium font-alex-brush text-5xl z-50 italic"
                            >
                                Kirim Ucapan
                            </h2>

                            <div
                                data-aos="fade-up"
                                data-aos-delay="200"
                                class="p-6 bg-[#FDFFFF] border-2 space-y-12 border-[#6D99BD] rounded-lg shadow-xs"
                            >
                                <div
                                    class="flex text-left justify-start flex-col gap-1"
                                >
                                    <label for="name">Nama</label>
                                    <input
                                        v-model="commentForm.nama"
                                        class="w-full p-3 text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#6D99BD] focus:border-transparent transition duration-200 resize-y"
                                        type="text"
                                        id="name"
                                        name="nama"
                                        required
                                    />
                                    <label for="pesan" class="mt-3"
                                        >Pesan</label
                                    >
                                    <textarea
                                        v-model="commentForm.pesan"
                                        class="w-full p-3 text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#6D99BD] focus:border-transparent transition duration-200 resize-y"
                                        rows="4"
                                        id="pesan"
                                        name="pesan"
                                        required
                                    />
                                    <button
                                        @click="submitComment"
                                        :disabled="commentForm.processing"
                                        class="px-3 py-2 mt-3 cursor-pointer w-fit flex justify-center rounded-md items-center gap-3 bg-[#6D99BD] hover:bg-[#7babd3] active:bg-[#6f9dc2] active:scale-95 transition duration-200 text-white disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <icon icon="fa:send" width="20" />
                                        <p>
                                            {{
                                                commentForm.processing
                                                    ? "Mengirim..."
                                                    : "Kirim Ucapan"
                                            }}
                                        </p>
                                    </button>
                                </div>
                                <div class="">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="h-0.5 flex-1 bg-gray-200 rounded-full"
                                        ></div>
                                        <div
                                            class="flex items-center text-gray-600 font-semibold shrink-0 gap-2"
                                        >
                                            <icon
                                                icon="material-symbols:comment"
                                            />
                                            <p>Riwayat Pesan</p>
                                        </div>
                                        <div
                                            class="h-0.5 flex-1 bg-gray-200 rounded-full"
                                        ></div>
                                    </div>
                                    <div
                                        class="max-h-96 overflow-y-auto mt-6 space-y-6 scrollbar-hide"
                                    >
                                        <div
                                            v-for="msg in komentarUndangan"
                                            :key="msg.id"
                                            class="flex gap-2"
                                        >
                                            <div
                                                class="w-9 h-9 rounded-full bg-[#6D99BD] flex items-center justify-center text-white font-bold shrink-0"
                                            >
                                                {{
                                                    msg.nama
                                                        .charAt(0)
                                                        .toUpperCase()
                                                }}
                                            </div>
                                            <div
                                                class="flex w-full text-left flex-col gap-1 p-3 bg-gray-50 rounded-lg border border-gray-100"
                                            >
                                                <div
                                                    class="flex justify-between items-center"
                                                >
                                                    <span
                                                        class="font-bold text-sm text-gray-800"
                                                        >{{ msg.nama }}</span
                                                    >
                                                    <span
                                                        class="text-[10px] text-gray-400 italic"
                                                        >Baru saja</span
                                                    >
                                                </div>
                                                <p
                                                    class="text-gray-600 text-sm italic"
                                                >
                                                    "{{ msg.pesan }}"
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <button
                                        v-if="komentarUndangan?.length > 5"
                                        class="px-3 mt-8 py-2 cursor-pointer w-full flex justify-center rounded-md items-center gap-3 bg-[#6D99BD] hover:bg-[#7babd3] active:bg-[#6f9dc2] active:scale-95 transition duration-200 text-white"
                                    >
                                        <icon icon="uim:redo" width="20" />
                                        <p>Lihat Lainnya</p>
                                    </button>
                                    <div
                                        v-if="
                                            !komentarUndangan ||
                                            komentarUndangan.length === 0
                                        "
                                        class="py-10 text-gray-400 italic"
                                    >
                                        Belum ada ucapan. Jadilah yang pertama!
                                    </div>
                                </div>
                            </div>

                            <div
                                data-aos="fade-up"
                                data-aos-delay="400"
                                class="p-6 w-full flex flex-col gap-4 bg-[#FDFFFF] border-2 space-y-4 border-[#6D99BD] rounded-lg shadow-xs"
                            >
                                <h2
                                    class="font-bold font-lora text-3xl z-50 italic"
                                >
                                    RSVP Form
                                </h2>
                                <div class="w-full">
                                    <button
                                        @click="showRsvpForm = !showRsvpForm"
                                        class="relative px-3 py-2 w-full cursor-pointer h-24 flex justify-center rounded-md items-center gap-3 bg-[#6D99BD] hover:bg-[#7babd3] active:bg-[#6f9dc2] active:scale-95 transition duration-200 text-white"
                                    >
                                        <icon
                                            class="absolute scale-200 right-10 bottom-2"
                                            icon="game-icons:click"
                                            width="20"
                                        />
                                        <p
                                            class="font-bold font-lora text-3xl tracking-wide"
                                        >
                                            {{
                                                showRsvpForm
                                                    ? "Batal"
                                                    : "Reservasi"
                                            }}
                                        </p>
                                    </button>
                                </div>

                                <transition
                                    enter-active-class="transition ease-out duration-300"
                                    enter-from-class="transform opacity-0 -translate-y-4"
                                    enter-to-class="transform opacity-100 translate-y-0"
                                    leave-active-class="transition ease-in duration-200"
                                    leave-from-class="transform opacity-100 translate-y-0"
                                    leave-to-class="transform opacity-0 -translate-y-4"
                                >
                                    <div
                                        v-if="showRsvpForm"
                                        class="bg-gray-50 p-6 rounded-lg border border-gray-200 text-left space-y-4 shadow-inner"
                                    >
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                                >Nama</label
                                            >
                                            <input
                                                v-model="rsvpForm.nama"
                                                type="text"
                                                class="w-full p-2 border border-gray-300 rounded-md focus:ring-[#6D99BD] focus:border-[#6D99BD]"
                                                placeholder="Nama Anda"
                                            />
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                                >Jumlah Hadir</label
                                            >
                                            <input
                                                v-model="rsvpForm.jumlah_hadir"
                                                type="number"
                                                min="1"
                                                class="w-full p-2 border border-gray-300 rounded-md focus:ring-[#6D99BD] focus:border-[#6D99BD]"
                                            />
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                                >Kehadiran</label
                                            >
                                            <select
                                                v-model="
                                                    rsvpForm.status_kehadiran
                                                "
                                                class="w-full p-2 border border-gray-300 rounded-md focus:ring-[#6D99BD] focus:border-[#6D99BD]"
                                            >
                                                <option value="Hadir">
                                                    Hadir
                                                </option>
                                                <option value="Tidak">
                                                    Tidak Hadir
                                                </option>
                                            </select>
                                        </div>
                                        <button
                                            @click="submitRsvp"
                                            :disabled="rsvpForm.processing"
                                            class="w-full py-2 mt-2 bg-[#6D99BD] text-white rounded-md font-bold hover:bg-[#7babd3] transition-colors disabled:opacity-50 cursor-pointer"
                                        >
                                            {{
                                                rsvpForm.processing
                                                    ? "Mengirim..."
                                                    : "Kirim Reservasi"
                                            }}
                                        </button>
                                    </div>
                                </transition>
                                <p class="font-montserrat text-xl">
                                    Isi RSVP Form jika kamu berencana hadir
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- section 6 -->

                <!-- section 7 -->
                <div
                    class="relative mx-auto flex flex-col items-center text-center w-full max-w-120 bg-linear-to-b from-[#ffffff] to-[#E8F5FF]"
                >
                    <div class="z-0 pointer-events-none">
                        <img
                            class="w-70 absolute top-0 right-0 object-cover"
                            src="/assets/bunga/bunga-1.png"
                            alt=""
                        />
                        <img
                            class="w-70 rotate-180 absolute bottom-0 left-0 object-cover"
                            src="/assets/bunga/bunga-1.png"
                            alt=""
                        />
                    </div>
                    <div class="relative w-full min-h-full px-10 py-20 pb-32">
                        <div class="w-full space-y-12">
                            <h2
                                data-aos="fade-down"
                                class="font-medium font-alex-brush text-5xl z-50 italic"
                            >
                                Kirim Hadiah
                            </h2>
                            <div
                                data-aos="zoom-in"
                                data-aos-delay="200"
                                class="flex flex-col w-full justify-content-center gap-4 p-6 bg-[#FDFFFF] border-2 border-[#6D99BD] rounded-lg shadow-sm"
                            >
                                <img
                                    src="/assets/amplop-digital.png"
                                    class="w-30 h-auto mx-auto drop-shadow-sm"
                                    alt="amplop-digital"
                                />
                                <h2
                                    class="font-bold font-lora text-xl z-50 italic"
                                >
                                    Amplop Digital
                                </h2>
                                <p class="font-medium font-lora">
                                    Bagi yang ingin mengirim Amplop bisa ke no
                                    rekening berikut [08976xxxxx] atas nama
                                    jamal dan udin
                                </p>
                            </div>
                            <div
                                data-aos="zoom-in"
                                data-aos-delay="400"
                                class="flex flex-col w-full justify-content-center gap-4 p-6 bg-[#FDFFFF] border-2 border-[#6D99BD] rounded-lg shadow-sm"
                            >
                                <img
                                    src="/assets/kado.png"
                                    class="w-30 h-auto mx-auto drop-shadow-sm"
                                    alt="kado"
                                />
                                <h2
                                    class="font-bold font-lora text-xl z-50 italic"
                                >
                                    Kado
                                </h2>
                                <p class="font-medium font-lora">
                                    Bagi yang ingin mengirim Amplop bisa ke no
                                    rekening berikut, Jalan jawa no 5 sebelah
                                    masjid jamak
                                </p>
                            </div>
                            <div
                                data-aos="fade-up"
                                data-aos-delay="600"
                                class="space-y-8 mt-20"
                            >
                                <p class="font-medium font-lora">
                                    Atas kehadirannya kami ucapkan
                                </p>
                                <h2
                                    class="font-bold font-lora text-4xl z-50 italic"
                                >
                                    Terima Kasih
                                </h2>
                                <div
                                    class="flex flex-col text-5xl font-alex-brush space-y-2"
                                >
                                    <h4>
                                        {{
                                            dataMempelai.nama_panggilan_pria ??
                                            "belum diatur"
                                        }}
                                    </h4>

                                    <p>&</p>
                                    <h4>
                                        {{
                                            dataMempelai.nama_panggilan_wanita ??
                                            "belum diatur"
                                        }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- section 7 -->
            </div>
        </transition>
    </div>
</template>

<style scoped>
/* CSS Untuk Transisi Cover dan Isi Undangan */
.invite-transition-enter-active,
.invite-transition-leave-active {
    transition:
        opacity 0.8s ease-in-out,
        transform 0.8s ease-in-out;
}

.invite-transition-enter-from {
    opacity: 0;
    transform: translateY(30px);
}

.invite-transition-leave-to {
    opacity: 0;
    transform: translateY(-30px);
}
</style>
