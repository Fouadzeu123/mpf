<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Html5Qrcode } from 'html5-qrcode';
import {
    Maximize2,
    Phone,
    MapPin,
    Building2,
    User,
    Calendar,
    Link as LinkIcon,
} from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    mode: string;
    serviceType: string;
    modes: Array<{ value: string; label: string }>;
    serviceTypes: Array<{ value: string; label: string }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Scanner QR', href: '/scanner' },
];

const currentMode = ref(props.mode);
const currentService = ref(props.serviceType);
const scanning = ref(false);
const result = ref<Record<string, unknown> | null>(null);
const error = ref('');
const fullscreen = ref(false);
let scanner: Html5Qrcode | null = null;
const readerId = 'qr-reader';

type ScanMember = {
    full_name?: string;
    photo_url?: string | null;
    department?: string | null;
    member_code?: string;
    phone?: string | null;
    age?: number | null;
    gender?: string | null;
    address_description?: string | null;
    status?: string | null;
    id?: number | null;
};

const scannedMember = computed<ScanMember | null>(() => {
    if (!result.value?.member || typeof result.value.member !== 'object') {
        return null;
    }

    return result.value.member as ScanMember;
});

async function startScanner() {
    if (scanner) {
        return;
    }

    scanner = new Html5Qrcode(readerId);

    try {
        // First, get the list of available cameras
        const cameras = await Html5Qrcode.getCameras();
        
        if (!cameras || cameras.length === 0) {
            throw new Error("Aucune caméra trouvée.");
        }

        // Try to find a back camera
        let cameraId = cameras[0].id;
        for (const camera of cameras) {
            const label = camera.label.toLowerCase();
            if (label.indexOf('back') !== -1 || label.indexOf('rear') !== -1 || label.indexOf('arrière') !== -1 || label.indexOf('environnement') !== -1) {
                cameraId = camera.id;
                break;
            }
        }

        // Start scanning with the selected camera ID
        await scanner.start(
            cameraId,
            { 
                fps: 10, 
                qrbox: (width, height) => {
                    const min = Math.min(width, height);
                    return {
                        width: Math.floor(min * 0.7),
                        height: Math.floor(min * 0.7)
                    };
                }
            },
            onScan,
            () => {},
        );
        
        scanning.value = true;
        error.value = '';
    } catch (err: any) {
        console.warn("Html5Qrcode getCameras failed or start failed, trying fallback to facingMode...", err);
        // Fallback to basic facingMode if getCameras fails or is blocked
        try {
            await scanner.start(
                { facingMode: 'environment' },
                { 
                    fps: 10, 
                    qrbox: { width: 220, height: 220 } 
                },
                onScan,
                () => {},
            ).catch(async (fallbackErr) => {
                console.warn("Could not start environment camera, trying fallback to user camera...", fallbackErr);
                return await scanner!.start(
                    { facingMode: 'user' },
                    { fps: 10, qrbox: { width: 220, height: 220 } },
                    onScan,
                    () => {},
                );
            });
            scanning.value = true;
            error.value = '';
        } catch (fallbackErr: any) {
            console.error("Html5Qrcode fallback also failed:", fallbackErr);
            error.value = "Caméra inaccessible. Veuillez autoriser l'accès et vérifier que vous utilisez une connexion sécurisée (HTTPS).";
        }
    }
}

async function stopScanner() {
    if (scanner) {
        await scanner.stop();
        scanner.clear();
        scanner = null;
    }

    scanning.value = false;
}

function playBeep() {
    try {
        const ctx = new AudioContext();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.frequency.value = 880;
        gain.gain.value = 0.1;
        osc.start();
        osc.stop(ctx.currentTime + 0.15);
    } catch {
        //
    }
}

function getAppUrl(path: string): string {
    const meta = document.querySelector<HTMLMetaElement>('meta[name="app-url"]');
    const baseUrl = meta?.content ? meta.content.replace(/\/$/, '') : '';
    return `${baseUrl}/${path.replace(/^\//, '')}`;
}

async function onScan(code: string) {
    const csrf = document.querySelector<HTMLMetaElement>(
        'meta[name="csrf-token"]',
    )?.content;

    const res = await fetch(getAppUrl('/scanner/scan'), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrf ?? '',
        },
        body: JSON.stringify({
            code,
            mode: currentMode.value,
            service_type: currentService.value,
        }),
        credentials: 'same-origin',
    });

    const data = await res.json();
    result.value = data;

    if (data.success) {
        playBeep();
        await stopScanner();
    }
}

function updateUrl() {
    const params = new URLSearchParams({
        mode: currentMode.value,
        service: currentService.value,
    });
    window.history.replaceState({}, '', `/scanner?${params}`);
}

onMounted(() => startScanner());
onUnmounted(() => stopScanner());
</script>

<template>
    <Head title="Scanner QR" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex flex-col gap-4 p-4"
            :class="{ 'fixed inset-0 z-50 bg-background p-2': fullscreen }"
        >
            <div class="flex flex-wrap items-center justify-between gap-2">
                <h1 class="text-2xl font-bold">Scanner QR</h1>
                <Button
                    variant="outline"
                    size="sm"
                    @click="fullscreen = !fullscreen"
                >
                    <Maximize2 class="h-4 w-4" />
                </Button>
            </div>

            <div class="flex flex-wrap gap-2">
                <select
                    v-model="currentMode"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium shadow-sm transition focus:border-primary focus:ring-2 focus:ring-primary/30 focus:outline-none dark:border-slate-700 dark:bg-slate-900"
                    @change="updateUrl"
                >
                    <option v-for="m in modes" :key="m.value" :value="m.value">
                        {{ m.label }}
                    </option>
                </select>
                <select
                    v-if="currentMode === 'attendance'"
                    v-model="currentService"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium shadow-sm transition focus:border-primary focus:ring-2 focus:ring-primary/30 focus:outline-none dark:border-slate-700 dark:bg-slate-900"
                    @change="updateUrl"
                >
                    <option
                        v-for="s in serviceTypes"
                        :key="s.value"
                        :value="s.value"
                    >
                        {{ s.label }}
                    </option>
                </select>
                <Button v-if="!scanning" size="sm" @click="startScanner"
                    >Relancer caméra</Button
                >
            </div>

            <div
                id="qr-reader"
                class="mx-auto w-full max-w-md border border-slate-200 dark:border-slate-800 bg-black min-h-[280px]"
            />

            <p v-if="error" class="text-center text-sm text-destructive">
                {{ error }}
            </p>

            <div
                v-if="result"
                class="mx-auto max-w-md rounded-xl border p-4 text-center transition"
                :class="
                    result.success
                        ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-950'
                        : 'border-blue-500 bg-blue-50/50 dark:bg-blue-950/20'
                "
            >
                <p class="text-lg font-semibold">{{ result.message }}</p>
                <template v-if="result.member">
                    <!-- Profile mode detailed card -->
                    <template v-if="currentMode === 'profile'">
                        <div class="mt-4 flex flex-col items-center gap-4">
                            <img
                                v-if="scannedMember?.photo_url"
                                :src="scannedMember.photo_url"
                                class="h-32 w-32 rounded-full object-cover ring-4 ring-primary"
                                alt=""
                            />
                            <p class="text-2xl font-bold">
                                {{ scannedMember?.full_name }}
                            </p>
                            <div
                                class="flex flex-wrap justify-center gap-2 text-sm"
                            >
                                <span
                                    class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 dark:bg-slate-800"
                                >
                                    <LinkIcon class="h-4 w-4" />
                                    {{ scannedMember?.id }}
                                </span>
                                <span
                                    v-if="scannedMember?.status"
                                    class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold"
                                    :class="
                                        scannedMember?.status === 'active'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : 'bg-slate-100 text-slate-600'
                                    "
                                >
                                    {{
                                        scannedMember?.status === 'active'
                                            ? 'Actif'
                                            : 'Inactif'
                                    }}
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <Phone class="h-4 w-4" />
                                    {{ scannedMember?.phone }}
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <Building2 class="h-4 w-4" />
                                    {{ scannedMember?.department }}
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <User class="h-4 w-4" />
                                    {{ scannedMember?.gender }}
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <Calendar class="h-4 w-4" />
                                    {{ scannedMember?.age }}
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <MapPin class="h-4 w-4" />
                                    {{ scannedMember?.address_description }}
                                </span>
                            </div>
                            <Link
                                :href="`/members/${scannedMember?.id}`"
                                class="mt-2 inline-flex items-center gap-1 text-primary hover:underline"
                            >
                                <LinkIcon class="h-4 w-4" /> Voir profil complet
                            </Link>
                        </div>
                    </template>
                    <!-- Non‑profile simple display -->
                    <template v-else>
                        <div class="mt-4 flex flex-col items-center gap-2">
                            <img
                                v-if="scannedMember?.photo_url"
                                :src="scannedMember.photo_url"
                                class="h-20 w-20 rounded-full object-cover"
                                alt=""
                            />
                            <p class="font-bold">
                                {{ scannedMember?.full_name }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ scannedMember?.department }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ scannedMember?.member_code }}
                            </p>
                        </div>
                    </template>
                </template>
                <Button
                    class="mt-4"
                    @click="
                        result = null;
                        startScanner();
                    "
                    >Scanner à nouveau</Button
                >
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
#qr-reader :deep(video) {
    width: 100% !important;
    height: auto !important;
    max-height: 400px !important;
    object-fit: cover !important;
    border-radius: 0px !important;
}
</style>
