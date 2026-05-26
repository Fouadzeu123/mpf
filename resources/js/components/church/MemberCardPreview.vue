<script setup lang="ts">
import { QrCode } from 'lucide-vue-next';

defineProps<{
    member: {
        member_code: string;
        first_name: string;
        last_name: string;
        photo_url?: string | null;
        age?: number | null;
        gender?: string | null;
        department?: string | null;
        address_description?: string | null;
    };
    qrDataUri?: string;
    qrSvg?: string;
    programs?: string[];
    compact?: boolean;
}>();
</script>

<template>
    <div
        class="mx-auto w-full max-w-md overflow-hidden rounded-2xl border border-amber-300 bg-amber-50 p-3 shadow-xl dark:border-amber-500/40 dark:bg-slate-950"
        :class="compact ? 'max-w-xs' : ''"
    >
        <div
            class="flex items-center gap-3 rounded-xl bg-slate-950 px-4 py-3 text-white"
        >
            <img
                src="/favicon.svg"
                alt="Logo"
                class="h-14 w-14 object-contain"
            />
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-bold">
                    Ministère Prophétique de la Foi
                </p>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-amber-300">
                    Carte d'identification
                </p>
            </div>
            <span class="rounded-full bg-amber-400 px-3 py-1 text-[10px] font-bold text-slate-950">
                MEMBRE
            </span>
        </div>

        <div class="flex gap-3 py-3">
            <div class="shrink-0">
                <img
                    v-if="member.photo_url"
                    :src="member.photo_url"
                    class="h-28 w-20 rounded-xl object-cover ring-2 ring-amber-400"
                    alt=""
                />
                <div
                    v-else
                    class="flex h-28 w-20 items-center justify-center rounded-xl bg-amber-100 text-lg font-bold text-amber-900 ring-2 ring-amber-400"
                >
                    {{ member.first_name[0] }}{{ member.last_name[0] }}
                </div>
            </div>
            <div class="min-w-0 flex-1">
                <h3 class="truncate text-base font-black uppercase text-slate-950 dark:text-white">
                    {{ member.last_name }}
                </h3>
                <p class="text-sm font-bold text-amber-800 dark:text-amber-300">
                    {{ member.first_name }}
                </p>
                <p class="mt-2 border-b border-amber-200 pb-1 text-[11px] text-slate-700 dark:text-slate-300">
                    <span class="font-bold uppercase text-amber-800 dark:text-amber-300">Sexe</span> :
                    {{ member.gender || '-' }}
                    <span class="font-bold uppercase text-amber-800 dark:text-amber-300">Age</span> :
                    {{ member.age ? `${member.age} ans` : '-' }}
                </p>
                <p class="border-b border-amber-200 py-1 text-[11px] text-slate-700 dark:text-slate-300">
                    <span class="font-bold uppercase text-amber-800 dark:text-amber-300">Adresse</span> :
                    {{ member.address_description || '-' }}
                </p>
                <p class="border-b border-amber-200 py-1 text-[11px] text-slate-700 dark:text-slate-300">
                    <span class="font-bold uppercase text-amber-800 dark:text-amber-300">Département</span> :
                    {{ member.department || '-' }}
                </p>
            </div>
            <div class="shrink-0 text-center">
                <div
                    v-if="qrSvg"
                    class="rounded-lg bg-white p-1 ring-1 ring-amber-200 [&_svg]:h-20 [&_svg]:w-20"
                    v-html="qrSvg"
                />
                <img
                    v-else-if="qrDataUri"
                    :src="qrDataUri"
                    class="h-20 w-20 rounded-lg bg-white p-1 ring-1 ring-amber-200"
                    alt="QR"
                />
                <QrCode
                    v-else
                    class="mx-auto h-20 w-20 text-slate-300"
                />
                <p class="mt-1 text-[10px] font-bold uppercase text-amber-800 dark:text-amber-300">
                    Scanner
                </p>
                <p class="mt-1 rounded-full bg-amber-700 px-2 py-1 font-mono text-[10px] font-bold text-white">
                    {{ member.member_code }}
                </p>
            </div>
        </div>

        <div
            v-if="programs?.length"
            class="rounded-xl border border-amber-200 bg-white px-3 py-2 text-[10px] leading-relaxed text-slate-600 dark:bg-slate-900 dark:text-slate-300"
        >
            {{ programs.join(' • ') }}
        </div>
    </div>
</template>
