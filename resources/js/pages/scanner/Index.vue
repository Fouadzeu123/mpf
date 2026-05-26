<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Html5Qrcode } from 'html5-qrcode';
import { Maximize2, Phone, MapPin, Building2, User, Calendar, Link as LinkIcon } from 'lucide-vue-next';
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
        await scanner.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            onScan,
            () => {},
        );
        scanning.value = true;
    } catch {
         
        error.value = 'Caméra inaccessible. Autorisez l\'accès.';
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

async function onScan(code: string) {
    const csrf = document.querySelector<HTMLMetaElement>(
        'meta[name="csrf-token"]',
    )?.content;

    const res = await fetch('/scanner/scan', {
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
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium shadow-sm transition focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-slate-700 dark:bg-slate-900"
                    @change="updateUrl"
                >
                    <option
                        v-for="m in modes"
                        :key="m.value"
                        :value="m.value"
                    >
                        {{ m.label }}
                    </option>
                </select>
                <select
                    v-if="currentMode === 'attendance'"
                    v-model="currentService"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium shadow-sm transition focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-slate-700 dark:bg-slate-900"
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
                <Button
                    v-if="!scanning"
                    size="sm"
                    @click="startScanner"
                    >Relancer caméra</Button
                >
            </div>

            <div
                id="qr-reader"
                class="mx-auto max-w-md overflow-hidden rounded-xl border"
            />

            <p v-if="error" class="text-center text-sm text-destructive">{{
                error
            }}</p>

            <div
                v-if="result"
                class="mx-auto max-w-md rounded-xl border p-4 text-center transition"
                :class="
                    result.success
                        ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-950'
                        : 'border-amber-500 bg-amber-50'
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
                                class="h-32 w-32 rounded-full ring-4 ring-amber-500 object-cover"
                                alt=""
                            />
                            <p class="text-2xl font-bold">{{ scannedMember?.full_name }}</p>
                            <div class="flex flex-wrap justify-center gap-2 text-sm">
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 dark:bg-slate-800 px-2 py-0.5">
                                    <LinkIcon class="h-4 w-4" />
                                    {{ scannedMember?.id }}
                                </span>
                                <span v-if="scannedMember?.status"
                                    class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold"
                                    :class="scannedMember?.status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600'">
                                    {{ scannedMember?.status === 'active' ? 'Actif' : 'Inactif' }}
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
                            <Link :href="`/members/${scannedMember?.id}`" class="mt-2 inline-flex items-center gap-1 text-amber-600 hover:underline">
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
                            <p class="font-bold">{{ scannedMember?.full_name }}</p>
                            <p class="text-sm text-muted-foreground">{{ scannedMember?.department }}</p>
                            <p class="text-sm text-muted-foreground">{{ scannedMember?.member_code }}</p>
                        </div>
                    </template>
                </template>
                <Button class="mt-4" @click="result = null; startScanner()"
                    >Scanner à nouveau</Button
                >
            </div>
        </div>
    </AppLayout>
</template>
