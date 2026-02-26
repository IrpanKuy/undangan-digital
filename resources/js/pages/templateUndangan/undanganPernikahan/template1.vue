<script setup>
import { ref } from "vue";

const namaPengantinPria = "Anisa";
const namaPengantinWanita = "Jamal";
const akadNikah = "jum'at, 27 Februari 2026";
const isOpen = ref(false);

const ourStory = ref([
    {
        date: "31 Januari 2018",
        title: "Pertama Kali Kenal",
        desc: "Kita Saling Mengenal dari SMA dan selalu menjaga hubungan dari masih sekolah",
        image: "https://placehold.co/400x400", // Ganti dengan path fotomu
    },
    {
        date: "3 maret 2022",
        title: "Bersama bertahun-tahun",
        desc: "Kita Saling Mengenal dari SMA dan selalu menjaga hubungan dari masih sekolah",
        image: "https://placehold.co/400x400",
    },
    {
        date: "28 Februari 2026",
        title: "Lamaran",
        desc: "Akhirnya kita memutuskan untuk melangkah ke jenjang yang lebih serius.",
        image: "https://placehold.co/400x400",
    },
]);

const ourGallery = ref([
    "https://placehold.co/400x400",
    "https://placehold.co/400x400",
    "https://placehold.co/400x400",
    "https://placehold.co/400x400",
]);
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
                        <h4>Anisa</h4>
                        <p>&</p>
                        <h4>Jamal</h4>
                    </div>
                    <div class="relative">
                        <img
                            src="https://placehold.co/400"
                            alt="Foto Profil Manusia"
                            class="w-40 h-40 z-10 rounded-full border-2 border-gray-200 object-cover shadow-sm"
                        />
                        <img
                            src="/assets/bingkai/bingkai1.png"
                            alt="Foto Profil Manusia"
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
                                    src="https://placehold.co/400"
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
                                <h4>{{ namaPengantinPria }}</h4>
                                <p>&</p>
                                <h4>{{ namaPengantinWanita }}</h4>
                            </div>
                            <p class="font-montserrat text-xl">
                                Akan menyelenggarakan acara <br />pernikahan
                                pada
                            </p>
                            <h2 class="font-bold font-lora text-3xl">
                                {{ akadNikah }}
                            </h2>
                            <p class="font-montserrat text-xl">
                                Acara akan dimulai dalam
                            </p>
                            <div class="flex gap-4">
                                <div
                                    class="w-16 h-16 flex justify-center items-center rounded-lg flex-col p-1 bg-linear-to-br from-[#5A85A8] to-[#AAC8E0] text-white font-lora"
                                >
                                    <div class="text-lg font-medium">Hari</div>
                                    <p class="font-bold">5</p>
                                </div>
                                <div
                                    class="w-16 h-16 flex justify-center items-center rounded-lg flex-col p-1 bg-linear-to-br from-[#5A85A8] to-[#AAC8E0] text-white font-lora"
                                >
                                    <div class="text-lg font-medium">Jam</div>
                                    <p class="font-bold">5</p>
                                </div>
                                <div
                                    class="w-16 h-16 flex justify-center items-center rounded-lg flex-col p-1 bg-linear-to-br from-[#5A85A8] to-[#AAC8E0] text-white font-lora"
                                >
                                    <div class="text-lg font-medium">Menit</div>
                                    <p class="font-bold">5</p>
                                </div>
                                <div
                                    class="w-16 h-16 flex justify-center items-center rounded-lg flex-col p-1 bg-linear-to-br from-[#5A85A8] to-[#AAC8E0] text-white font-lora"
                                >
                                    <div class="text-lg font-medium">Detik</div>
                                    <p class="font-bold">5</p>
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
                                    src="https://placehold.co/400"
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
                                Anisa Putri
                            </h2>
                            <p class="font-montserrat text-xl">
                                Putri Bapak Haji Kusumo
                            </p>
                            <div class="flex justify-center gap-4">
                                <img
                                    class="object-cover w-12 rounded-md cursor-pointer"
                                    src="/assets/logo/ig-logo.png"
                                    alt=""
                                />
                                <img
                                    class="object-cover w-12 rounded-md cursor-pointer"
                                    src="/assets/logo/tiktok-logo.png"
                                    alt=""
                                />
                                <img
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
                                    src="https://placehold.co/400"
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
                        <div class="space-y-3">
                            <h2 class="text-5xl font-alex-brush">
                                Anisa Putri
                            </h2>
                            <p class="font-montserrat text-xl">
                                Putri Bapak Haji Kusumo
                            </p>
                            <div class="flex justify-center gap-4">
                                <img
                                    class="object-cover w-12 rounded-md cursor-pointer"
                                    src="/assets/logo/ig-logo.png"
                                    alt=""
                                />
                                <img
                                    class="object-cover w-12 rounded-md cursor-pointer"
                                    src="/assets/logo/tiktok-logo.png"
                                    alt=""
                                />
                                <img
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
                                            28 Desemmber 2026
                                        </h2>
                                    </div>
                                    <div
                                        class="flex justify-center items-center gap-2"
                                    >
                                        <icon width="20" icon="mdi:clock" />
                                        <h2
                                            class="font-medium font-lora text-xl z-50 italic"
                                        >
                                            Pukul 07:00 - 13:00
                                        </h2>
                                    </div>
                                    <p class="font-montserrat text-xl">
                                        Di jalan raya bagus Masjid Al-Falaq
                                    </p>
                                </div>
                                <div
                                    class="bg-white w-full space-y-2 p-6 border-2 rounded-lg border-[#6D99BD]"
                                >
                                    <h2
                                        class="font-semibold font-lora text-3xl z-50 italic"
                                    >
                                        Resepsi
                                    </h2>
                                    <div
                                        class="flex justify-center items-center gap-2"
                                    >
                                        <icon width="20" icon="fe:calendar" />
                                        <h2
                                            class="font-medium font-lora text-xl z-50 italic"
                                        >
                                            28 Desemmber 2026
                                        </h2>
                                    </div>
                                    <div
                                        class="flex justify-center items-center gap-2"
                                    >
                                        <icon width="20" icon="mdi:clock" />
                                        <h2
                                            class="font-medium font-lora text-xl z-50 italic"
                                        >
                                            Pukul 07:00 - 13:00
                                        </h2>
                                    </div>
                                    <p class="font-montserrat text-xl">
                                        Di jalan raya bagus Masjid Al-Falaq
                                    </p>
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
                                    v-for="(story, index) in ourStory"
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
                                            v-if="index !== ourStory.length - 1"
                                            class="w-0.5 bg-black grow"
                                        ></div>
                                    </div>

                                    <div
                                        class="pb-12 pt-1 space-y-12 w-full text-left"
                                    >
                                        <h2 class="font-bold text-lg">
                                            {{ story.date }}
                                        </h2>

                                        <div class="relative w-full">
                                            <img
                                                :src="story.image"
                                                alt="Story Image"
                                                class="w-full absolute h-auto object-cover border-2"
                                            />
                                            <img
                                                src="/assets/bingkai/bingkai2.png"
                                                alt="bingkai"
                                                class="z-50 scale-120 translate-y-1"
                                            />
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-lg">
                                                {{ story.title }}
                                            </h3>
                                            <p
                                                class="text-gray-600 text-sm leading-relaxed"
                                            >
                                                {{ story.desc }}
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
                                    Semoga istri saya sehat selamanya
                                </p>
                            </div>
                            <div class="space-y-3">
                                <h2
                                    class="font-bold font-lora text-2xl z-50 italic"
                                >
                                    Harapan Pengantin Wanita
                                </h2>
                                <p class="font-montserrat text-xl">
                                    Semoga suami saya sehat selamanya
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
                    <div class="space-y-24">
                        <h2
                            class="font-medium font-alex-brush text-5xl z-50 italic"
                        >
                            Our Gallery
                        </h2>
                        <div
                            class="relative flex flex-col justify-center items-center h-70"
                        >
                            <img
                                :src="ourGallery[0]"
                                alt="gambar"
                                class="object-cover absolute bg-white w-70 h-auto"
                            />
                            <img
                                :src="ourGallery[1]"
                                alt="gambar"
                                class="object-cover absolute w-30 rounded-md z-25 h-auto"
                            />

                            <img
                                src="/assets/bingkai/bingkai-gallery.png"
                                alt="gambar"
                                class="object-cover bg-black/25 absolute w-70 h-auto"
                            />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div
                                v-for="(gallery, index) in ourGallery"
                                :key="index"
                                class="w-full"
                            >
                                <img
                                    :src="gallery"
                                    alt=""
                                    class="w-full h-auto rounded-md object-fit-cover"
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
