<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowLeft,
    Book,
    Heart,
    MessageCircle,
    Pause,
    Play,
    Share2,
    Volume2,
    VolumeX,
} from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
import BottomNavBar from '@/components/BottomNavBar.vue';
import BottomNavBarMember from '@/components/BottomNavBarMember.vue';

type VideoItem = {
    id: number;
    category: string;
    title: string;
    speaker: string;
    description: string;
    bible_reference: string;
    video_url: string;
    likes: number;
    shares: number;
    comments_count: number;
    date: string;
};

const props = defineProps<{
    videos: VideoItem[];
    isMember: boolean;
}>();

const activeTab = ref<'pour_vous' | 'populaire'>('pour_vous');
const activeIndex = ref(0);
const isMuted = ref(true);
const likedVideos = ref<number[]>([]);
const activeVerse = ref<{ title: string; ref: string } | null>(null);

// Filter videos by tab
const filteredVideos = computed(() => {
    if (activeTab.value === 'populaire') {
        return [...props.videos].sort((a, b) => b.likes - a.likes);
    }
    return props.videos;
});

// Load liked states from cache
onMounted(() => {
    const cached = localStorage.getItem('mpf_liked_videos');
    if (cached) {
        likedVideos.value = JSON.parse(cached);
    }
});

// Sync likes to cache
function toggleLike(id: number) {
    const index = likedVideos.value.indexOf(id);
    if (index >= 0) {
        likedVideos.value.splice(index, 1);
    } else {
        likedVideos.value.push(id);
    }
    localStorage.setItem('mpf_liked_videos', JSON.stringify(likedVideos.value));
}

function isLiked(id: number) {
    return likedVideos.value.includes(id);
}

// Share on WhatsApp
function shareVideo(video: VideoItem) {
    const text = `Regardez cette vidéo de ${video.speaker} sur Ministère Prophétique de la Foi : "${video.title}" (${video.bible_reference}). Retrouvez l'édification sur notre application !`;
    const url = `https://api.whatsapp.com/send?text=${encodeURIComponent(text)}`;
    window.open(url, '_blank');
}

// Show Bible verse modal/popover
function showVerse(video: VideoItem) {
    activeVerse.value = {
        title: video.title,
        ref: video.bible_reference,
    };
}

// Audio controls
function toggleMute() {
    isMuted.value = !isMuted.value;
    // Apply to all active video tags
    document.querySelectorAll('video').forEach((el) => {
        el.muted = isMuted.value;
    });
}

// Video player references and scrolling logic
const feedContainer = ref<HTMLElement | null>(null);
const videoRefs = ref<Record<number, HTMLVideoElement>>({});

function registerVideoRef(id: number, el: any) {
    if (el) {
        videoRefs.value[id] = el;
    } else {
        delete videoRefs.value[id];
    }
}

// Track scroll to update active video
function handleScroll() {
    if (!feedContainer.value) return;
    const container = feedContainer.value;
    const index = Math.round(container.scrollTop / container.clientHeight);
    if (index !== activeIndex.value && index >= 0 && index < filteredVideos.value.length) {
        activeIndex.value = index;
    }
}

// Watch activeIndex to play/pause videos
watch([activeIndex, filteredVideos], () => {
    // Wait for DOM updates
    setTimeout(() => {
        filteredVideos.value.forEach((v, index) => {
            const player = videoRefs.value[v.id];
            if (player) {
                if (index === activeIndex.value) {
                    player.muted = isMuted.value;
                    player.play().catch(() => {
                        // Handle browser autoplay policy block
                    });
                } else {
                    player.pause();
                    player.currentTime = 0;
                }
            }
        });
    }, 100);
}, { immediate: true });

// Toggle play/pause on tap
const isPaused = ref(false);
function togglePlayPause(id: number) {
    const player = videoRefs.value[id];
    if (player) {
        if (player.paused) {
            player.play().catch(() => {});
            isPaused.value = false;
        } else {
            player.pause();
            isPaused.value = true;
        }
    }
}

// Reset scroll on tab change
function selectTab(tab: 'pour_vous' | 'populaire') {
    activeTab.value = tab;
    activeIndex.value = 0;
    isPaused.value = false;
    if (feedContainer.value) {
        feedContainer.value.scrollTop = 0;
    }
}
</script>

<template>
    <Head title="Flux Spirituel" />

    <div class="relative h-svh w-full bg-slate-950 overflow-hidden text-white">
        <!-- Top Tabs Overlay -->
        <header
            class="absolute top-0 left-0 right-0 z-40 bg-gradient-to-b from-slate-950/80 to-transparent px-4 pt-4 pb-8 flex flex-col items-center gap-3"
        >
            <div class="w-full flex items-center justify-between">
                <Link
                    :href="isMember ? '/membre/espace' : '/dashboard'"
                    class="rounded-full bg-slate-900/50 p-2 text-white hover:bg-slate-900/80 transition backdrop-blur-md"
                >
                    <ArrowLeft class="h-5 w-5" />
                </Link>
                <span class="text-xs font-black uppercase tracking-[0.2em] text-amber-400">
                    MPF Médias
                </span>
                <button
                    @click="toggleMute"
                    class="rounded-full bg-slate-900/50 p-2 text-white hover:bg-slate-900/80 transition backdrop-blur-md"
                >
                    <Volume2 v-if="!isMuted" class="h-5 w-5 text-amber-400" />
                    <VolumeX v-else class="h-5 w-5 text-slate-400" />
                </button>
            </div>

            <!-- Tabs buttons -->
            <div class="flex items-center gap-6 text-sm font-semibold tracking-wide backdrop-blur-md bg-slate-900/30 px-4 py-1.5 rounded-full border border-white/5">
                <button
                    @click="selectTab('pour_vous')"
                    class="pb-1 transition duration-300"
                    :class="[
                        activeTab === 'pour_vous'
                            ? 'text-amber-400 border-b-2 border-amber-400 font-bold'
                            : 'text-slate-400 hover:text-slate-200',
                    ]"
                >
                    Pour vous
                </button>
                <button
                    @click="selectTab('populaire')"
                    class="pb-1 transition duration-300"
                    :class="[
                        activeTab === 'populaire'
                            ? 'text-amber-400 border-b-2 border-amber-400 font-bold'
                            : 'text-slate-400 hover:text-slate-200',
                    ]"
                >
                    Populaire
                </button>
            </div>
        </header>

        <!-- Vertical Swiping Feed Container -->
        <main
            ref="feedContainer"
            @scroll="handleScroll"
            class="h-full w-full snap-y snap-mandatory overflow-y-scroll scroll-smooth"
        >
            <div
                v-for="(v, index) in filteredVideos"
                :key="v.id"
                class="relative h-full w-full snap-start flex items-center justify-center bg-black overflow-hidden"
            >
                <!-- Lazy Loaded HTML5 Video Component -->
                <template v-if="Math.abs(index - activeIndex) <= 1">
                    <video
                        :ref="(el) => registerVideoRef(v.id, el)"
                        :src="v.video_url"
                        class="h-full w-full object-cover"
                        loop
                        playsinline
                        webkit-playsinline
                        :muted="isMuted"
                        @click="togglePlayPause(v.id)"
                    />
                </template>
                <div v-else class="h-full w-full bg-slate-900 flex items-center justify-center text-slate-500">
                    <div class="text-center space-y-2">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-amber-400 mx-auto" />
                        <span class="text-xs">Chargement spirituel...</span>
                    </div>
                </div>

                <!-- Play/Pause Overlay indicator -->
                <div
                    v-if="activeIndex === index && isPaused"
                    class="pointer-events-none absolute inset-0 flex items-center justify-center bg-black/20"
                >
                    <div class="rounded-full bg-black/55 p-5 text-amber-400 backdrop-blur-sm animate-ping">
                        <Play class="h-10 w-10 fill-current" />
                    </div>
                </div>

                <!-- Bottom gradient overlay for readability -->
                <div
                    class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent pointer-events-none z-10"
                />

                <!-- Left Content Overlay (Video descriptions) -->
                <div
                    class="absolute left-4 bottom-20 right-16 z-20 space-y-3 pointer-events-auto"
                >
                    <div class="space-y-1.5">
                        <h4 class="inline-flex items-center gap-1.5 rounded-full bg-amber-400/15 border border-amber-400/30 px-3 py-1 text-xs font-black text-amber-300">
                            {{ v.speaker }}
                        </h4>
                        <h3 class="text-lg font-black tracking-tight leading-snug text-white">
                            {{ v.title }}
                        </h3>
                    </div>
                    <p class="text-xs text-slate-200 line-clamp-2 leading-relaxed max-w-sm">
                        {{ v.description }}
                    </p>
                    <button
                        @click="showVerse(v)"
                        class="flex items-center gap-1.5 rounded-xl bg-blue-900/60 border border-blue-500/20 px-3 py-1.5 text-xs font-bold text-blue-200 hover:bg-blue-800/80 transition backdrop-blur-sm"
                    >
                        <Book class="h-3.5 w-3.5 text-amber-400" />
                        Verset : {{ v.bible_reference }}
                    </button>
                </div>

                <!-- Right Sidebar Interactions -->
                <div
                    class="absolute right-4 bottom-20 z-20 flex flex-col items-center gap-5 pointer-events-auto"
                >
                    <!-- Like Interaction -->
                    <button
                        @click="toggleLike(v.id)"
                        class="group flex flex-col items-center gap-1"
                    >
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-900/50 hover:bg-slate-900/80 transition backdrop-blur-md border border-white/5"
                        >
                            <Heart
                                class="h-6 w-6 transition duration-300"
                                :class="[
                                    isLiked(v.id)
                                        ? 'text-red-500 fill-current scale-110'
                                        : 'text-white group-hover:text-red-400',
                                ]"
                            />
                        </div>
                        <span class="text-[10px] font-bold text-slate-300">{{ v.likes + (isLiked(v.id) ? 1 : 0) }}</span>
                    </button>

                    <!-- Share Interaction (WhatsApp) -->
                    <button
                        @click="shareVideo(v)"
                        class="group flex flex-col items-center gap-1"
                    >
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-900/50 hover:bg-slate-900/80 transition backdrop-blur-md border border-white/5"
                        >
                            <Share2 class="h-5 w-5 text-white group-hover:text-amber-400 transition" />
                        </div>
                        <span class="text-[10px] font-bold text-slate-300">{{ v.shares }}</span>
                    </button>

                    <!-- Date info -->
                    <div class="text-[9px] font-bold text-slate-400 bg-slate-900/40 px-2 py-0.5 rounded border border-white/5 backdrop-blur-sm">
                        {{ v.date }}
                    </div>
                </div>
            </div>

            <!-- Feed Empty state -->
            <div
                v-if="!filteredVideos.length"
                class="h-full w-full flex items-center justify-center bg-slate-950 p-6 text-center"
            >
                <div class="max-w-xs space-y-3">
                    <Book class="h-12 w-12 text-slate-600 mx-auto" />
                    <h3 class="text-lg font-bold text-slate-300">Aucun contenu</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Il n'y a pas de vidéos de cette catégorie pour le moment. Veuillez revenir plus tard.
                    </p>
                </div>
            </div>
        </main>

        <!-- Floating Bible Verse Popover Modal -->
        <Transition name="fade">
            <div
                v-if="activeVerse"
                class="absolute inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
                @click.self="activeVerse = null"
            >
                <div
                    class="w-full max-w-sm rounded-[2rem] border border-blue-900/50 bg-gradient-to-b from-blue-950 to-slate-950 p-6 shadow-2xl text-center space-y-4"
                >
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-amber-400/10 text-amber-400">
                        <Book class="h-6 w-6" />
                    </div>
                    <div class="space-y-1">
                        <h4 class="text-xs font-black tracking-widest text-amber-400 uppercase">
                            Méditation Biblique
                        </h4>
                        <h3 class="text-sm font-semibold text-slate-300 leading-tight">
                            {{ activeVerse.title }}
                        </h3>
                    </div>
                    <div class="rounded-2xl border border-blue-900/20 bg-blue-950/40 p-4">
                        <p class="font-serif italic text-base text-amber-100 leading-relaxed">
                            " La foi est une ferme assurance des choses qu'on espère, une démonstration de celles qu'on ne voit pas. "
                        </p>
                        <p class="mt-2 text-xs font-bold text-amber-400 font-sans tracking-wide uppercase">
                            — {{ activeVerse.ref }}
                        </p>
                    </div>
                    <button
                        @click="activeVerse = null"
                        class="w-full rounded-2xl bg-amber-400 py-3 text-sm font-black text-slate-950 shadow-lg shadow-amber-400/10 hover:bg-amber-300 active:scale-[0.98] transition"
                    >
                        Fermer
                    </button>
                </div>
            </div>
        </Transition>

        <!-- Overlay Floating Menu -->
        <BottomNavBarMember v-if="isMember" />
        <BottomNavBar v-else />
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Hide scrollbar but keep functionality */
main {
    scrollbar-width: none; /* Firefox */
}
main::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera */
}
</style>
