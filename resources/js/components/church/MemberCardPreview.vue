<script setup lang="ts">
import { QrCode } from 'lucide-vue-next';

defineProps<{
    member: {
        member_code: string;
        first_name: string;
        last_name: string;
        photo_url?: string | null;
        birth_date?: string | null;
        gender?: string | null;
        phone?: string | null;
        department?: string | null;
        address_description?: string | null;
    };
    qrDataUri?: string;
    qrSvg?: string;
    programs?: string[];
    compact?: boolean;
}>();

function formatDate(dateStr?: string | null): string {
    if (!dateStr) return '-';
    const parts = dateStr.split('-');
    if (parts.length === 3) {
        return `${parts[2]}/${parts[1]}/${parts[0]}`;
    }
    return dateStr;
}
</script>

<template>
    <div
        class="mx-auto w-full max-w-md overflow-hidden rounded-2xl border-2 border-blue-900 bg-white p-3.5 shadow-lg dark:border-blue-700 dark:bg-slate-950"
        :class="compact ? 'max-w-xs' : ''"
    >
        <!-- Card Header: Solid deep blue, high printable contrast -->
        <div
            class="flex items-center gap-3 rounded-xl bg-blue-950 px-4 py-3 text-white dark:bg-blue-900"
        >
            <img
                src="/favicon.svg"
                alt="Logo"
                class="h-12 w-12 object-contain bg-white rounded-lg p-0.5"
            />
            <div class="min-w-0 flex-1">
                <p class="truncate text-xs font-black uppercase tracking-tight">
                    Ministère Prophétique de la Foi
                </p>
                <p
                    class="text-[9px] font-bold tracking-widest text-blue-300 uppercase"
                >
                    Carte d'identification
                </p>
            </div>
            <span
                class="rounded bg-blue-600 px-2 py-0.5 text-[9px] font-black text-white"
            >
                MEMBRE
            </span>
        </div>

        <!-- Card Body -->
        <div class="flex gap-4 py-3">
            <!-- Photo Slot: Clean solid borders for printing -->
            <div class="shrink-0">
                <img
                    v-if="member.photo_url"
                    :src="member.photo_url"
                    class="h-28 w-20 rounded-lg object-cover border-2 border-blue-900 dark:border-blue-700"
                    alt=""
                />
                <div
                    v-else
                    class="flex h-28 w-20 items-center justify-center rounded-lg bg-blue-50 border-2 border-dashed border-blue-300 text-lg font-bold text-blue-900 dark:bg-blue-950 dark:border-blue-700 dark:text-blue-200"
                >
                    {{ member.first_name[0] }}{{ member.last_name[0] }}
                </div>
            </div>

            <!-- Member Info: Solid colors, high visibility text for printing -->
            <div class="min-w-0 flex-1 space-y-1">
                <h3
                    class="truncate text-base font-black text-blue-950 uppercase dark:text-white"
                >
                    {{ member.last_name }}
                </h3>
                <p class="text-sm font-bold text-blue-700 dark:text-blue-400">
                    {{ member.first_name }}
                </p>
                
                <div class="mt-2 space-y-0.5 text-[10px] text-slate-800 dark:text-slate-200">
                    <p class="border-b border-slate-100 dark:border-slate-800 pb-0.5">
                        <span class="font-bold text-blue-900 dark:text-blue-300">SEXE :</span>
                        {{ member.gender || '-' }}
                        <span class="ml-2 font-bold text-blue-900 dark:text-blue-300">NAISSANCE :</span>
                        {{ formatDate(member.birth_date) }}
                    </p>
                    <p class="border-b border-slate-100 dark:border-slate-800 py-0.5 truncate">
                        <span class="font-bold text-blue-900 dark:text-blue-300">ADRESSE :</span>
                        {{ member.address_description || '-' }}
                    </p>
                    <p class="border-b border-slate-100 dark:border-slate-800 py-0.5 truncate">
                        <span class="font-bold text-blue-900 dark:text-blue-300">TEL :</span>
                        {{ member.phone || '-' }}
                    </p>
                    <p class="border-b border-slate-100 dark:border-slate-800 py-0.5 truncate">
                        <span class="font-bold text-blue-900 dark:text-blue-300">DEPT :</span>
                        {{ member.department || '-' }}
                    </p>
                </div>
            </div>

            <!-- QR Code: High contrast wrapper for perfect readability -->
            <div class="shrink-0 flex flex-col items-center justify-center">
                <div
                    v-if="qrSvg"
                    class="rounded-lg bg-white p-1 border-2 border-blue-900 dark:border-blue-700 [&_svg]:h-20 [&_svg]:w-20"
                    v-html="qrSvg"
                />
                <img
                    v-else-if="qrDataUri"
                    :src="qrDataUri"
                    class="h-20 w-20 rounded-lg bg-white p-1 border-2 border-blue-900 dark:border-blue-700"
                    alt="QR"
                />
                <QrCode v-else class="mx-auto h-20 w-20 text-slate-300" />
                
                <p
                    class="mt-1 rounded bg-blue-950 px-2 py-0.5 font-mono text-[8px] font-bold text-white tracking-wider uppercase"
                >
                    {{ member.member_code }}
                </p>
            </div>
        </div>

        <!-- Programs: Crisp layout at bottom -->
        <div
            v-if="programs?.length"
            class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-[9px] font-medium leading-normal text-slate-700 dark:border-slate-800 dark:bg-slate-900/50 dark:text-slate-300"
        >
            {{ programs.join(' • ') }}
        </div>
    </div>
</template>
