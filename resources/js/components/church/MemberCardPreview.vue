<script setup lang="ts">
import { computed } from 'vue';
import { QrCode } from 'lucide-vue-next';

const props = defineProps<{
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

function getAppUrl(path: string): string {
    const meta = document.querySelector<HTMLMetaElement>('meta[name="app-url"]');
    const baseUrl = meta?.content ? meta.content.replace(/\/$/, '') : '';
    return `${baseUrl}/${path.replace(/^\//, '')}`;
}

function formatDate(dateStr?: string | null): string {
    if (!dateStr) return '-';
    const parts = dateStr.split('-');
    if (parts.length === 3) {
        return `${parts[2]}/${parts[1]}/${parts[0]}`;
    }
    return dateStr;
}

const normalize = (str?: string | null): string => {
    if (!str) return '';
    return str.toLowerCase().trim()
        .replace(/[éèêë]/g, 'e')
        .replace(/[àâä]/g, 'a')
        .replace(/[ôö]/g, 'o')
        .replace(/[ûü]/g, 'u')
        .replace(/ç/g, 'c');
};

const memberDepts = computed(() => {
    return props.member.department
        ? props.member.department.split(',').map(d => d.trim()).filter(d => d !== '')
        : [];
});

const theme = computed(() => {
    let isApotre = false;
    let isPasteur = false;
    let isDirigeant = false;
    let isEvangeliste = false;
    let isAncien = false;
    let isDiacre = false;
    let isChorale = false;

    memberDepts.value.forEach(md => {
        const norm = normalize(md);
        if (norm === 'apotre') isApotre = true;
        if (norm === 'pasteur') isPasteur = true;
        if (norm === 'dirigeant') isDirigeant = true;
        if (norm === 'evangeliste') isEvangeliste = true;
        if (norm === 'anciens' || norm === 'ancien') isAncien = true;
        if (norm === 'diacres' || norm === 'diacre') isDiacre = true;
        if (norm === 'chorale') isChorale = true;
    });

    if (isApotre) return 'apotre';
    if (isPasteur || isDirigeant) return isDirigeant ? 'dirigeant' : 'pasteur';
    if (isEvangeliste) return 'evangeliste';
    if (isAncien) return 'ancien';
    if (isDiacre) return 'diacre';
    if (isChorale) return 'chorale';
    return 'default';
});

const badgeLabel = computed(() => {
    switch (theme.value) {
        case 'apotre': return 'APÔTRE';
        case 'pasteur': return 'PASTEUR';
        case 'dirigeant': return 'DIRIGEANT';
        case 'evangeliste': return 'ÉVANGÉLISTE';
        case 'ancien': return 'ANCIEN';
        case 'diacre': return 'DIACRE';
        case 'chorale': return 'CHORALE';
        default: return 'MEMBRE';
    }
});

const displayedDepts = computed(() => {
    const hiddenDepts = ['apotre', 'pasteur', 'dirigeant', 'evangeliste', 'anciens', 'ancien', 'diacres', 'diacre', 'chorale', 'nettoyage', 'protocole', 'communication', "culte d'enfant", 'moniteurs'];
    const filtered = memberDepts.value.filter(md => !hiddenDepts.includes(normalize(md)));
    return filtered.join(', ');
});

const containerClasses = computed(() => {
    switch (theme.value) {
        case 'apotre': return 'border-purple-700 dark:border-purple-600';
        case 'pasteur':
        case 'dirigeant': return 'border-amber-600 dark:border-amber-500';
        case 'evangeliste': return 'border-blue-600 dark:border-blue-500';
        case 'ancien': return 'border-amber-800 dark:border-amber-700';
        case 'diacre': return 'border-indigo-600 dark:border-indigo-500';
        case 'chorale': return 'border-sky-600 dark:border-sky-500';
        default: return 'border-blue-900 dark:border-blue-700';
    }
});

const headerClasses = computed(() => {
    switch (theme.value) {
        case 'apotre': return 'bg-purple-950 border-b border-amber-600 text-white';
        case 'pasteur':
        case 'dirigeant': return 'bg-slate-950 border-b border-amber-500 text-white';
        case 'evangeliste': return 'bg-blue-800 text-white';
        case 'ancien': return 'bg-amber-950 text-white';
        case 'diacre': return 'bg-indigo-950 text-white';
        case 'chorale': return 'bg-sky-800 text-white';
        default: return 'bg-blue-950 text-white dark:bg-blue-900';
    }
});

const badgeClasses = computed(() => {
    switch (theme.value) {
        case 'apotre':
        case 'pasteur':
        case 'dirigeant': return 'bg-amber-600 text-white';
        case 'evangeliste': return 'bg-blue-600 text-white';
        case 'ancien': return 'bg-amber-700 text-white';
        case 'diacre': return 'bg-indigo-600 text-white';
        case 'chorale': return 'bg-sky-600 text-white';
        default: return 'bg-blue-600 text-white';
    }
});

const headerSubtitleClasses = computed(() => {
    if (['apotre', 'pasteur', 'dirigeant'].includes(theme.value)) {
        return 'text-[9px] font-bold tracking-widest text-amber-400 uppercase';
    }
    return 'text-[9px] font-bold tracking-widest text-blue-300 uppercase';
});

const borderClasses = computed(() => {
    switch (theme.value) {
        case 'apotre': return 'border-purple-700 dark:border-purple-600';
        case 'pasteur':
        case 'dirigeant': return 'border-amber-600 dark:border-amber-500';
        case 'evangeliste': return 'border-blue-600 dark:border-blue-500';
        case 'ancien': return 'border-amber-800 dark:border-amber-700';
        case 'diacre': return 'border-indigo-600 dark:border-indigo-500';
        case 'chorale': return 'border-sky-600 dark:border-sky-500';
        default: return 'border-blue-900 dark:border-blue-700';
    }
});

const firstNameClasses = computed(() => {
    switch (theme.value) {
        case 'apotre': return 'text-purple-600 dark:text-purple-400';
        case 'pasteur':
        case 'dirigeant': return 'text-amber-600 dark:text-amber-400';
        case 'evangeliste': return 'text-blue-600 dark:text-blue-400';
        case 'ancien': return 'text-amber-700 dark:text-amber-400';
        case 'diacre': return 'text-indigo-600 dark:text-indigo-400';
        case 'chorale': return 'text-sky-600 dark:text-sky-400';
        default: return 'text-blue-700 dark:text-blue-400';
    }
});

const labelHighlightClasses = computed(() => {
    switch (theme.value) {
        case 'apotre': return 'text-purple-950 dark:text-purple-300';
        case 'pasteur':
        case 'dirigeant': return 'text-amber-700 dark:text-amber-400';
        case 'evangeliste': return 'text-blue-950 dark:text-blue-300';
        case 'ancien': return 'text-amber-900 dark:text-amber-400';
        case 'diacre': return 'text-indigo-950 dark:text-indigo-300';
        case 'chorale': return 'text-sky-950 dark:text-sky-300';
        default: return 'text-blue-900 dark:text-blue-300';
    }
});

const codeClasses = computed(() => {
    switch (theme.value) {
        case 'apotre': return 'bg-purple-950 border border-amber-600 text-amber-400';
        case 'pasteur':
        case 'dirigeant': return 'bg-slate-950 border border-amber-600 text-amber-400';
        case 'evangeliste': return 'bg-blue-800 text-white';
        case 'ancien': return 'bg-amber-950 text-white';
        case 'diacre': return 'bg-indigo-950 text-white';
        case 'chorale': return 'bg-sky-800 text-white';
        default: return 'bg-blue-950 text-white';
    }
});
</script>

<template>
    <div
        class="mx-auto w-full max-w-md overflow-hidden rounded-2xl border-2 bg-white p-3.5 shadow-lg dark:bg-slate-950"
        :class="[compact ? 'max-w-xs' : '', containerClasses]"
    >
        <!-- Card Header -->
        <div
            class="flex items-center gap-3 rounded-xl px-4 py-3"
            :class="headerClasses"
        >
            <img
                :src="getAppUrl('/favicon.svg')"
                alt="Logo"
                class="h-12 w-12 object-contain bg-white rounded-lg p-0.5"
            />
            <div class="min-w-0 flex-1">
                <p class="truncate text-xs font-black uppercase tracking-tight">
                    Ministère Prophétique de la Foi
                </p>
                <p :class="headerSubtitleClasses">
                    Carte d'identification
                </p>
            </div>
            <span
                class="rounded px-2 py-0.5 text-[9px] font-black"
                :class="badgeClasses"
            >
                {{ badgeLabel }}
            </span>
        </div>

        <!-- Card Body -->
        <div class="flex gap-4 py-3">
            <!-- Photo Slot -->
            <div class="shrink-0">
                <img
                    v-if="member.photo_url"
                    :src="member.photo_url"
                    class="h-28 w-20 rounded-lg object-cover border-2"
                    :class="borderClasses"
                    alt=""
                />
                <div
                    v-else
                    class="flex h-28 w-20 items-center justify-center rounded-lg bg-blue-50 border-2 border-dashed text-lg font-bold dark:bg-blue-950"
                    :class="borderClasses"
                >
                    {{ member.first_name[0] }}{{ member.last_name[0] }}
                </div>
            </div>

            <!-- Member Info -->
            <div class="min-w-0 flex-1 space-y-1">
                <h3
                    class="truncate text-base font-black text-slate-900 uppercase dark:text-white"
                >
                    {{ member.last_name }}
                </h3>
                <p class="text-sm font-bold" :class="firstNameClasses">
                    {{ member.first_name }}
                </p>
                
                <div class="mt-2 space-y-0.5 text-[10px] text-slate-800 dark:text-slate-200">
                    <p class="border-b border-slate-100 dark:border-slate-800 pb-0.5">
                        <span class="font-bold" :class="labelHighlightClasses">SEXE :</span>
                        {{ member.gender || '-' }}
                        <span class="ml-2 font-bold" :class="labelHighlightClasses">NAISSANCE :</span>
                        {{ formatDate(member.birth_date) }}
                    </p>
                    <p class="border-b border-slate-100 dark:border-slate-800 py-0.5 truncate">
                        <span class="font-bold" :class="labelHighlightClasses">ADRESSE :</span>
                        {{ member.address_description || '-' }}
                    </p>
                    <p class="border-b border-slate-100 dark:border-slate-800 py-0.5 truncate">
                        <span class="font-bold" :class="labelHighlightClasses">TEL :</span>
                        {{ member.phone || '-' }}
                    </p>
                    <p v-if="displayedDepts" class="border-b border-slate-100 dark:border-slate-800 py-0.5 truncate">
                        <span class="font-bold" :class="labelHighlightClasses">DEPT :</span>
                        {{ displayedDepts }}
                    </p>
                </div>
            </div>

            <!-- QR Code -->
            <div class="shrink-0 flex flex-col items-center justify-center">
                <div
                    v-if="qrSvg"
                    class="rounded-lg bg-white p-1 border-2 [&_svg]:h-20 [&_svg]:w-20"
                    :class="borderClasses"
                    v-html="qrSvg"
                />
                <img
                    v-else-if="qrDataUri"
                    :src="qrDataUri"
                    class="h-20 w-20 rounded-lg bg-white p-1 border-2"
                    :class="borderClasses"
                    alt="QR"
                />
                <QrCode v-else class="mx-auto h-20 w-20 text-slate-300" />
                
                <p
                    class="mt-1 rounded px-2 py-0.5 font-mono text-[8px] font-bold tracking-wider uppercase"
                    :class="codeClasses"
                >
                    {{ member.member_code }}
                </p>
            </div>
        </div>

        <!-- Programs -->
        <div
            v-if="programs?.length"
            class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-[9px] font-medium leading-normal text-slate-700 dark:border-slate-800 dark:bg-slate-900/50 dark:text-slate-300"
        >
            {{ programs.join(' • ') }}
        </div>
    </div>
</template>
