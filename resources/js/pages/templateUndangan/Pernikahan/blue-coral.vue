<script setup>
import { ref, reactive, onMounted, onUnmounted, computed } from "vue";

const props = defineProps({
    undangan: Object,
    acara: Object,
    dataMempelai: Object,
    templateUndanganPernikahan: Object,
    kisahCinta: Object,
    galleryUndangan: Object,
});

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

const formatJam = (time) => {
    if (!time) return "00:00";
    // Memecah 12:30:00 menjadi ['12', '30', '00'] lalu ambil 2 pertama
    const [hours, minutes] = time.split(":");
    return `${hours}:${minutes}`;
};

const formatTanggal = (dateString) => {
    if (!dateString) return "Belum diatur";

    const date = new Date(dateString);

    return new Intl.DateTimeFormat("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    }).format(date);
};

const countdown = reactive({
    hari: 0,
    jam: 0,
    menit: 0,
    detik: 0,
});

let timer = null;

const updateCountdown = () => {
    const targetDate = props.templateUndanganPernikahan?.tanggal_mulai_akad;

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

onMounted(() => {
    updateCountdown(); // Jalankan sekali saat load
    timer = setInterval(updateCountdown, 1000); // Update setiap 1 detik
});

onUnmounted(() => {
    clearInterval(timer); // Bersihkan memory saat pindah halaman
});

const namaPengantinPria = "Anisa";
const namaPengantinWanita = "Jamal";
const akadNikah = "jum'at, 27 Februari 2026";
const isOpen = ref(false);
</script>
<template>
    <div
        class="bg-linear-to-b from-[#ffffff] to-[#E8F5FF] -z-50 w-screen h-screen overflow-y-auto overflow-x-hidden"
    >
        <!-- buka undangaan -->
        <div v-if="isOpen == true">
            <div
                class="mx-auto relative flex flex-col justify-center items-center text-center w-full h-screen max-w-120 bg-linear-to-b from-[#ffffff] to-[#E8F5FF]"
            >
                <div class="z-0">
                    <img
                        class="w-70 absolute top-0 right-0 object-cover inline"
                        src="/assets/bunga/bunga-1.png"
                        alt=""
                    />
                    <img
                        class="w-70 rotate-180 absolute bottom-0 left-0 object-cover inline"
                        src="/assets/bunga/bunga-1.png"
                        alt=""
                    />
                </div>
                <div class="z-10 flex flex-col items-center space-y-14">
                    <h2 class="font-bold font-lora text-4xl z-50 italic">
                        The Wedding Of
                    </h2>
                    <div
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
                    <div class="relative">
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
                        @click="isOpen = false"
                        class="px-4 py-3 cursor-pointer flex rounded-full justify-center items-center gap-3 bg-[#6D99BD] text-white"
                    >
                        <icon icon="ion:book-sharp" width="20" />
                        <p>Buka Undangan</p>
                    </button>
                </div>
            </div>
        </div>

        <div v-if="isOpen == false">
            <!-- section 1 -->
            <div
                class="relative mx-auto flex flex-col items-center text-center w-full max-w-120 bg-linear-to-b from-[#ffffff] to-[#E8F5FF]"
            >
                <div class="z-0">
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
                                class="font-bold font-lora text-3xl z-50 italic"
                            >
                                Undangan Pernikahan
                            </h2>

                            <div class="relative">
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
                            <div class="space-y-6">
                                <p class="font-montserrat text-xl">
                                    Akan menyelenggarakan acara <br />pernikahan
                                    pada
                                </p>
                                <h2 class="font-bold font-lora text-3xl">
                                    {{
                                        formatTanggal(
                                            templateUndanganPernikahan?.tanggal_mulai_akad,
                                        ) ?? "belum diatur"
                                    }}
                                </h2>
                                <p class="font-montserrat text-xl">
                                    Acara akan dimulai dalam
                                </p>
                                <div class="flex gap-4">
                                    <div
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
            <!-- section 2 -->
            <div
                class="relative mx-auto flex flex-col items-center text-center w-full max-w-120 bg-linear-to-b from-[#ffffff] to-[#E8F5FF]"
            >
                <div class="z-0">
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
                        <h2 class="font-bold font-lora text-3xl z-50 italic">
                            Assalamualaikum Wr.Br
                        </h2>
                        <p class="font-montserrat text-xl">
                            Puji sykur kehadirat Allah sehingga terlaksanakan
                            momen bahagia pada acara pernikahan ini
                        </p>

                        <!-- foto wanita -->
                        <div>
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
                        <div class="space-y-3 mb-25">
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
                                    v-if="dataMempelai?.instagram_url_wanita"
                                    class="object-cover w-12 rounded-md cursor-pointer"
                                    src="/assets/logo/ig-logo.png"
                                    alt=""
                                />
                                <img
                                    v-if="dataMempelai?.tiktok_url_wanita"
                                    class="object-cover w-12 rounded-md cursor-pointer"
                                    src="/assets/logo/tiktok-logo.png"
                                    alt=""
                                />
                                <img
                                    v-if="dataMempelai?.x_url_wanita"
                                    class="object-cover w-12 rounded-md cursor-pointer"
                                    src="/assets/logo/x-logo.png"
                                    alt=""
                                />
                            </div>
                        </div>

                        <!-- foto pria -->
                        <div>
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
                        <div class="space-y-3">
                            <h2 class="text-5xl font-alex-brush">
                                {{ dataMempelai?.nama_lengkap_pria }}
                            </h2>
                            <p class="font-montserrat text-xl">
                                {{ dataMempelai?.keterangan_keluarga_pria }}
                            </p>
                            <div class="flex justify-center gap-4">
                                <img
                                    v-if="dataMempelai?.instagram_url_pria"
                                    class="object-cover w-12 rounded-md cursor-pointer"
                                    src="/assets/logo/ig-logo.png"
                                    alt=""
                                />
                                <img
                                    v-if="dataMempelai?.tiktok_url_pria"
                                    class="object-cover w-12 rounded-md cursor-pointer"
                                    src="/assets/logo/tiktok-logo.png"
                                    alt=""
                                />
                                <img
                                    v-if="dataMempelai?.x_url_pria"
                                    class="object-cover w-12 rounded-md cursor-pointer"
                                    src="/assets/logo/x-logo.png"
                                    alt=""
                                />
                            </div>
                        </div>

                        <p class="font-montserrat text-xl">
                            Semoga acara ini berakhir lancar dan kedua pasangan
                            bahagia bersama
                        </p>
                    </div>
                </div>
            </div>
            <!-- section 2 -->

            <!-- section 3 -->
            <div
                class="relative mx-auto flex flex-col items-center text-center w-full max-w-120 bg-linear-to-b from-[#ffffff] to-[#E8F5FF]"
            >
                <div class="z-0">
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
                                    class="font-medium font-alex-brush text-5xl z-50 italic"
                                >
                                    Save The Date
                                </h2>
                                <p class="font-montserrat text-xl">
                                    Puji sykur kehadirat Allah sehingga
                                    terlaksanakan momen bahagia pada acara
                                    pernikahan ini
                                </p>
                                <button
                                    class="px-4 py-3 cursor-pointer mx-auto flex rounded-md justify-center items-center gap-3 bg-[#6D99BD] hover:bg-[#7babd3] active:bg-[#6f9dc2] active:scale-95 transition duration-200 text-white"
                                >
                                    <icon icon="mdi:bell" width="20" />
                                    <p>Atur Pengingat</p>
                                </button>
                            </div>

                            <!-- acara & akad nikah -->
                            <div class="w-full space-y-6">
                                <div
                                    class="bg-white w-full space-y-2 p-6 border-2 rounded-lg border-[#6D99BD]"
                                >
                                    <h2
                                        class="font-semibold font-lora text-3xl z-50 italic"
                                    >
                                        Akad Nikah
                                    </h2>
                                    <div
                                        class="flex justify-center items-center gap-2"
                                    >
                                        <icon width="20" icon="fe:calendar" />
                                        <h2
                                            class="font-medium font-lora text-xl z-50 italic"
                                        >
                                            {{
                                                formatTanggal(
                                                    templateUndanganPernikahan?.tanggal_mulai_akad,
                                                ) ?? "belum diatur"
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
                                                formatJam(
                                                    templateUndanganPernikahan?.waktu_mulai_akad,
                                                )
                                            }}
                                        </h2>
                                    </div>
                                    <p class="font-montserrat text-xl">
                                        Di jalan raya bagus Masjid Al-Falaq
                                    </p>
                                </div>
                                <div v-for="item in acara" :key="item.id">
                                    <div
                                        class="bg-white w-full space-y-2 p-6 border-2 rounded-lg border-[#6D99BD]"
                                    >
                                        <h2
                                            class="font-semibold font-lora text-3xl z-50 italic text-center"
                                        >
                                            {{
                                                item.nama_acara ?? "Nama Acara"
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
                                                    formatTanggal(
                                                        item.tanggal_acara,
                                                    )
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
                                                    formatJam(item.waktu_acara)
                                                }}
                                                - Selesai
                                            </h2>
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
                            <p class="font-montserrat text-xl">
                                Catatan: Dilarang Merokok
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- section 3 -->

            <!-- section 4 -->
            <div
                class="relative mx-auto flex flex-col items-center text-center w-full max-w-120 bg-linear-to-b from-[#ffffff] to-[#E8F5FF]"
            >
                <div class="z-0">
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
                                class="font-medium font-alex-brush text-5xl z-50 italic"
                            >
                                Our Story
                            </h2>
                            <p class="font-montserrat text-xl">
                                Perjalanan kisah cinta kami
                            </p>
                        </div>

                        <!-- our story image -->
                        <div>
                            <div class="mx-auto p-8">
                                <div
                                    v-for="(story, index) in kisahCinta"
                                    :key="index"
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
                                                index !== kisahCinta.length - 1
                                            "
                                            class="w-0.5 bg-black grow"
                                        ></div>
                                    </div>

                                    <div
                                        class="pb-12 pt-1 space-y-6 w-full text-left"
                                    >
                                        <h2 class="font-bold text-lg">
                                            {{ formatTanggal(story.tanggal) }}
                                        </h2>

                                        <div
                                            v-if="story.foto_kisah_cinta_path"
                                            class="relative flex justify-center items-center w-full max-w-[320px] sm:max-w-[380px] mx-auto my-10"
                                        >
                                            <img
                                                :src="`/storage/${story.foto_kisah_cinta_path}`"
                                                alt="Story Image"
                                                class="absolute z-0 object-cover w-[85%] h-[85%] rounded-md"
                                            />

                                            <img
                                                src="/assets/bingkai/bingkai2.png"
                                                alt="bingkai"
                                                class="relative z-10 w-full h-auto pointer-events-none"
                                            />
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-lg">
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

                        <div class="space-y-12">
                            <div class="space-y-3">
                                <h2
                                    class="font-bold font-lora text-2xl z-50 italic"
                                >
                                    Harapan Pengantin Pria
                                </h2>
                                <p class="font-montserrat text-xl">
                                    {{
                                        templateUndanganPernikahan?.doa_pengantinn_pria ??
                                        "Belum Diatur"
                                    }}
                                </p>
                            </div>
                            <div class="space-y-3">
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
                            class="font-medium font-alex-brush text-5xl italic text-gray-800"
                        >
                            Our Gallery
                        </h2>

                        <div
                            v-if="galleries.length > 0"
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
                                    :src="getImageUrl(galleries[currentIndex])"
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
                <div class="z-0">
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
                            class="font-medium font-alex-brush text-5xl z-50 italic"
                        >
                            Kirim Ucapan
                        </h2>

                        <div
                            class="p-6 bg-[#FDFFFF] border-2 space-y-12 border-[#6D99BD] rounded-lg"
                        >
                            <div
                                class="flex text-left justify-start flex-col gap-1"
                            >
                                <label for="name">Nama</label>
                                <input
                                    class="w-full p-3 text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#6D99BD] focus:border-transparent transition duration-200 resize-y"
                                    type="text"
                                    id="name"
                                    name="nama"
                                />
                                <label for="pesan">Pesan</label>
                                <textarea
                                    class="w-full p-3 text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#6D99BD] focus:border-transparent transition duration-200 resize-y"
                                    rows="4"
                                    id="pesan"
                                    name="pesan"
                                />
                                <button
                                    class="px-3 py-2 mt-3 cursor-pointer w-fit flex justify-center rounded-md items-center gap-3 bg-[#6D99BD] hover:bg-[#7babd3] active:bg-[#6f9dc2] active:scale-95 transition duration-200 text-white"
                                >
                                    <icon icon="fa:send" width="20" />
                                    <p>Kirim Ucapan</p>
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
                                        <icon icon="material-symbols:comment" />
                                        <p>Riwayat Pesan</p>
                                    </div>
                                    <div
                                        class="h-0.5 flex-1 bg-gray-200 rounded-full"
                                    ></div>
                                </div>
                                <div class="flex mt-12 gap-2">
                                    <img
                                        src="https://placehold.co/400x400"
                                        alt="foto-profil"
                                        class="w-9 h-9 rounded-full"
                                    />
                                    <div
                                        class="flex w-full text-left flex-col gap-2"
                                    >
                                        <label for="pesan">Nama Pengguna</label>
                                        <textarea
                                            class="w-full p-3 text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#6D99BD] focus:border-transparent transition duration-200 resize-none"
                                            rows="4"
                                            id="pesan"
                                            name="pesan"
                                        />
                                    </div>
                                </div>
                                <button
                                    class="px-3 mt-8 py-2 cursor-pointer w-full flex justify-center rounded-md items-center gap-3 bg-[#6D99BD] hover:bg-[#7babd3] active:bg-[#6f9dc2] active:scale-95 transition duration-200 text-white"
                                >
                                    <icon icon="uim:redo" width="20" />
                                    <p>Lihat Lainnya</p>
                                </button>
                            </div>
                        </div>

                        <div
                            class="p-6 w-full flex flex-col gap-4 bg-[#FDFFFF] border-2 space-y-4 border-[#6D99BD] rounded-lg"
                        >
                            <h2
                                class="font-bold font-lora text-3xl z-50 italic"
                            >
                                RSVP Form
                            </h2>
                            <div class="w-full">
                                <button
                                    class="relative px-3 py-2 w-full cursor-pointer h-24 flex justify-center rounded-md items-center gap-3 bg-[#6D99BD] hover:bg-[#7babd3] active:bg-[#6f9dc2] active:scale-95 transition duration-200 text-white"
                                >
                                    <icon
                                        class="absolute scale-200 right-10 bottom-2"
                                        icon="game-icons:click"
                                        width="20"
                                    />
                                    <p class="font-bold font-lora text-3xl">
                                        Reservasi
                                    </p>
                                </button>
                            </div>
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
                <div class="z-0">
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
                    <div class="w-full space-y-12">
                        <h2
                            class="font-medium font-alex-brush text-5xl z-50 italic"
                        >
                            Kirim Hadiah
                        </h2>
                        <div
                            class="flex flex-col w-full justify-content-center gap-4 p-6 bg-[#FDFFFF] border-2 border-[#6D99BD] rounded-lg"
                        >
                            <img
                                src="/assets/amplop-digital.png"
                                class="w-30 h-auto mx-auto"
                                alt="amplop-digital"
                            />
                            <h2 class="font-bold font-lora text-xl z-50 italic">
                                Amplop Digital
                            </h2>
                            <p class="font-medium font-lora">
                                Bagi yang ingin mengirim Amplop bisa ke no
                                rekening berikut [08976xxxxx] atas nama jamal
                                dan udin
                            </p>
                        </div>
                        <div
                            class="flex flex-col w-full justify-content-center gap-4 p-6 bg-[#FDFFFF] border-2 border-[#6D99BD] rounded-lg"
                        >
                            <img
                                src="/assets/kado.png"
                                class="w-30 h-auto mx-auto"
                                alt="kado"
                            />
                            <h2 class="font-bold font-lora text-xl z-50 italic">
                                Kado
                            </h2>
                            <p class="font-medium font-lora">
                                Bagi yang ingin mengirim Amplop bisa ke no
                                rekening berikut, Jalan jawa no 5 sebelah masjid
                                jamak
                            </p>
                        </div>
                        <div class="space-y-8">
                            <p class="font-medium font-lora">
                                Atas kehadirannya kamai ucapkan
                            </p>
                            <h2
                                class="font-bold font-lora text-4xl z-50 italic"
                            >
                                Terima Kasih
                            </h2>
                            <div
                                class="flex flex-col text-5xl font-alex-brush space-y-2"
                            >
                                <h4>Anisa</h4>
                                <p>&</p>
                                <h4>Jamal</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- section 7 -->
        </div>
    </div>
</template>
