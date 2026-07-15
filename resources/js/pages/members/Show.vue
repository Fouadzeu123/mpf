<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { FileText, MapPin, Pencil, Printer, Trash2 } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import MemberCardPreview from '@/components/church/MemberCardPreview.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    member: {
        id: number;
        member_code: string;
        first_name: string;
        last_name: string;
        photo_url: string | null;
        birth_date: string | null;
        gender: string | null;
        profession: string | null;
        phone: string | null;
        department: string | null;
        address_description: string | null;
        latitude: number | null;
        longitude: number | null;
    };
    contributions: Array<{
        id: number;
        event_title: string;
        amount: number;
        payment_method: string;
        date: string;
    }>;
    totalContributions: number;
    sundayAttendancesCount: number;
    sundayAttendances: Array<{
        date: string;
        time: string;
        service_type: string;
    }>;
    communionPreparations: Array<{
        date: string;
        time: string;
        verse_reference: string | null;
        payment_status: string | null;
        payment_reference: string | null;
        remote: boolean;
    }>;
    currentMonthLabel: string;
    qrSvg: string;
    programs: string[];
    googleMapsKey: string | null;
    routeUrl: string | null;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Membres', href: '/members' },
    { title: props.member.member_code, href: `/members/${props.member.id}` },
];

const gpsLoading = ref(false);

function saveGps() {
    if (!navigator.geolocation) {
        alert('Géolocalisation non supportée');

        return;
    }

    gpsLoading.value = true;
    navigator.geolocation.getCurrentPosition(
        (pos) => {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/members/${props.member.id}/gps`;
            const csrf = document.querySelector<HTMLMetaElement>(
                'meta[name="csrf-token"]',
            )?.content;

            if (csrf) {
                const t = document.createElement('input');
                t.name = '_token';
                t.value = csrf;
                form.appendChild(t);
            }

            const coordinates: Array<[string, number]> = [
                ['latitude', pos.coords.latitude],
                ['longitude', pos.coords.longitude],
            ];

            coordinates.forEach(([n, v]) => {
                const i = document.createElement('input');
                i.name = n;
                i.value = String(v);
                form.appendChild(i);
            });
            document.body.appendChild(form);
            form.submit();
        },
        () => {
            gpsLoading.value = false;
            alert("Impossible d'obtenir la position");
        },
        { enableHighAccuracy: true },
    );
}

function printProfile() {
    window.print();
}

function formatDate(dateStr: string | null): string {
    if (!dateStr) return 'Non renseigné';
    const dateOnly = dateStr.substring(0, 10);
    const parts = dateOnly.split('-');
    if (parts.length === 3) {
        return `${parts[2]}/${parts[1]}/${parts[0]}`;
    }
    return dateStr;
}

function getAppUrl(path: string): string {
    const meta = document.querySelector<HTMLMetaElement>('meta[name="app-url"]');
    const baseUrl = meta?.content ? meta.content.replace(/\/$/, '') : '';
    return `${baseUrl}/${path.replace(/^\//, '')}`;
}

const page = usePage();
const userRole = computed(() => page.props.auth?.user?.role as string | undefined);

function deleteMember() {
    if (confirm("Êtes-vous sûr de vouloir supprimer ce membre définitivement ?\n\nCette action supprimera également toutes ses contributions et présences.")) {
        router.delete(`/members/${props.member.id}`);
    }
}
</script>

<template>
    <Head :title="member.member_code" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="no-print grid gap-6 p-4 lg:grid-cols-3">
            <!-- Card Preview Sidebar (Top on mobile, Right on desktop) -->
            <div class="order-1 lg:order-2 space-y-6">
                <h2
                    class="mb-3 text-center text-sm font-medium text-muted-foreground"
                >
                    Aperçu carte membre
                </h2>
                <MemberCardPreview
                    :member="member"
                    :qr-svg="qrSvg"
                    :programs="programs"
                />
            </div>

            <!-- Details Column (Bottom on mobile, Left on desktop) -->
            <div class="space-y-6 lg:col-span-2 order-2 lg:order-1">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold">
                            {{ member.first_name }} {{ member.last_name }}
                        </h1>
                        <p class="font-mono text-sm text-muted-foreground">
                            {{ member.member_code }}
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <Link :href="`/members/${member.id}/edit`">
                            <Button variant="outline" size="sm">
                                <Pencil class="mr-1 h-4 w-4" /> Modifier
                            </Button>
                        </Link>
                        <a
                            :href="`/members/${member.id}/carte`"
                            target="_blank"
                        >
                            <Button variant="outline" size="sm">
                                <Printer class="mr-1 h-4 w-4" /> PDF A4
                            </Button>
                        </a>
                        <Button
                            variant="outline"
                            size="sm"
                            @click="printProfile"
                        >
                            <FileText class="mr-1 h-4 w-4" /> Fiche A4
                        </Button>
                        <Button
                            v-if="['admin', 'secretaire'].includes(userRole)"
                            variant="destructive"
                            size="sm"
                            @click="deleteMember"
                        >
                            <Trash2 class="mr-1 h-4 w-4" /> Supprimer
                        </Button>
                    </div>
                </div>

                <div class="rounded-xl border bg-card p-4">
                    <h2 class="font-semibold">Coordonnées</h2>
                    <div class="mt-3 space-y-3 text-sm">
                        <p>
                            <span class="font-medium">Téléphone :</span>
                            {{ member.phone || 'Non renseigné' }}
                        </p>
                        <p>
                            <span class="font-medium">Département :</span>
                            {{ member.department || 'Non renseigné' }}
                        </p>
                        <p>
                            <span class="font-medium">Sexe :</span>
                            {{ member.gender || 'Non renseigné' }}
                        </p>
                        <p>
                            <span class="font-medium">Profession :</span>
                            {{ member.profession || 'Non renseignée' }}
                        </p>
                        <p>
                            <span class="font-medium">Date de naissance :</span>
                            {{ formatDate(member.birth_date) }}
                        </p>
                        <p>
                            <span class="font-medium">Adresse :</span>
                            {{
                                member.address_description ||
                                'Adresse non renseignée'
                            }}
                        </p>
                    </div>
                </div>

                <div class="rounded-xl border bg-card p-4">
                    <h2 class="font-semibold">
                        Présences au culte du dimanche
                    </h2>
                    <p class="mt-2 text-sm text-muted-foreground">
                        {{ currentMonthLabel }} :
                        {{ sundayAttendancesCount }} présence(s)
                    </p>
                    <div class="mt-3 space-y-2">
                        <div
                            v-for="attendance in sundayAttendances"
                            :key="attendance.date + attendance.time"
                            class="rounded-lg border px-3 py-2 text-sm"
                        >
                            <p class="font-medium">{{ attendance.date }}</p>
                            <p class="text-muted-foreground">
                                {{ attendance.time }} -
                                {{ attendance.service_type }}
                            </p>
                        </div>
                        <p
                            v-if="!sundayAttendances.length"
                            class="text-sm text-muted-foreground"
                        >
                            Aucune présence au culte du dimanche pour ce mois.
                        </p>
                    </div>
                </div>

                <div class="rounded-xl border bg-card p-4">
                    <h2 class="font-semibold">Préparations Sainte Cène</h2>
                    <div class="mt-3 space-y-2">
                        <div
                            v-for="preparation in communionPreparations"
                            :key="
                                preparation.date +
                                preparation.time +
                                preparation.payment_reference
                            "
                            class="rounded-lg border px-3 py-2 text-sm"
                        >
                            <p class="font-medium">
                                {{ preparation.date }} à {{ preparation.time }}
                            </p>
                            <p class="text-muted-foreground">
                                Verset :
                                {{
                                    preparation.verse_reference ||
                                    'Non renseigné'
                                }}
                            </p>
                            <p class="text-muted-foreground">
                                Paiement :
                                {{
                                    preparation.payment_status ||
                                    'Non renseigné'
                                }}
                            </p>
                            <p class="text-muted-foreground">
                                Référence :
                                {{
                                    preparation.payment_reference ||
                                    'Non renseignée'
                                }}
                            </p>
                            <p class="text-muted-foreground">
                                Mode :
                                {{
                                    preparation.remote
                                        ? 'À distance'
                                        : 'Sur place'
                                }}
                            </p>
                        </div>
                        <p
                            v-if="!communionPreparations.length"
                            class="text-sm text-muted-foreground"
                        >
                            Aucune préparation enregistrée.
                        </p>
                    </div>
                </div>

                <div class="rounded-xl border bg-card p-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold">Contributions pour événements</h2>
                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                            Total : {{ totalContributions }} FCFA
                        </span>
                    </div>
                    <div class="mt-3 space-y-2">
                        <div
                            v-for="contrib in contributions"
                            :key="contrib.id"
                            class="rounded-lg border px-3 py-2 text-sm flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/30"
                        >
                            <div>
                                <p class="font-medium">{{ contrib.event_title }}</p>
                                <p class="text-xs text-muted-foreground mt-0.5">
                                    {{ contrib.date }} · {{ contrib.payment_method }}
                                </p>
                            </div>
                            <span class="font-bold text-slate-800 dark:text-slate-100">
                                {{ contrib.amount }} FCFA
                            </span>
                        </div>
                        <p
                            v-if="!contributions.length"
                            class="text-sm text-muted-foreground"
                        >
                            Aucune contribution enregistrée.
                        </p>
                    </div>
                </div>

                <div class="rounded-xl border bg-card p-4">
                    <h2 class="mb-3 flex items-center gap-2 font-semibold">
                        <MapPin class="h-4 w-4 text-blue-500" />
                        Localisation GPS
                    </h2>
                    <div
                        v-if="member.latitude && member.longitude"
                        class="space-y-2"
                    >
                        <p class="font-mono text-sm text-muted-foreground">
                            {{ member.latitude }}, {{ member.longitude }}
                        </p>
                        <iframe
                            v-if="googleMapsKey"
                            class="h-48 w-full rounded-lg border"
                            :src="`https://www.google.com/maps/embed/v1/place?key=${googleMapsKey}&q=${member.latitude},${member.longitude}`"
                        />
                        <a
                            v-if="routeUrl"
                            :href="routeUrl"
                            target="_blank"
                            class="inline-flex text-sm font-medium text-primary underline dark:text-blue-400"
                        >
                            Voir itinéraire
                        </a>
                    </div>
                    <Button
                        class="mt-3"
                        :disabled="gpsLoading"
                        @click="saveGps"
                    >
                        Enregistrer localisation
                    </Button>
                </div>
            </div>
        </div>

        <section class="print-profile hidden bg-white text-slate-900">
            <!-- CV-style Container -->
            <div class="mx-auto w-[190mm] min-h-[270mm] font-sans text-xs flex border border-slate-200 shadow-sm rounded-xl overflow-hidden bg-white">
                
                <!-- Left Sidebar (Colored) -->
                <div class="w-[65mm] bg-[#1e293b] text-white p-6 flex flex-col justify-between">
                    <div>
                        <!-- Profile Photo Section -->
                        <div class="flex flex-col items-center mb-6">
                            <div class="relative">
                                <img
                                    v-if="member.photo_url"
                                    :src="member.photo_url"
                                    class="h-40 w-32 rounded-lg object-cover border-2 border-slate-700 shadow-md"
                                    alt=""
                                />
                                <div
                                    v-else
                                    class="flex h-40 w-32 items-center justify-center rounded-lg bg-slate-800 text-3xl font-black text-slate-500 border border-slate-700 shadow-md"
                                >
                                    {{ member.first_name[0] }}{{ member.last_name[0] }}
                                </div>
                            </div>
                            
                            <!-- Member Code Badge -->
                            <div class="mt-4 px-3 py-1 bg-slate-900/60 rounded-full border border-slate-700/50 text-[10px] font-mono tracking-wider font-semibold text-blue-300">
                                {{ member.member_code }}
                            </div>
                        </div>

                        <!-- Contact & Personal Details -->
                        <div class="space-y-5">
                            <div class="border-b border-slate-700/60 pb-2">
                                <h3 class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Contact</h3>
                            </div>
                            
                            <div class="space-y-3">
                                <div>
                                    <p class="text-[8px] font-medium text-slate-400 uppercase tracking-wider">Téléphone</p>
                                    <p class="font-semibold text-slate-200 mt-0.5">{{ member.phone || 'Non renseigné' }}</p>
                                </div>
                                <div>
                                    <p class="text-[8px] font-medium text-slate-400 uppercase tracking-wider">Adresse</p>
                                    <p class="font-semibold text-slate-200 mt-0.5 text-justify leading-relaxed">{{ member.address_description || 'Non renseignée' }}</p>
                                </div>
                                <div v-if="member.latitude && member.longitude">
                                    <p class="text-[8px] font-medium text-slate-400 uppercase tracking-wider">Position GPS</p>
                                    <p class="font-mono text-slate-300 mt-0.5 text-[9px]">{{ member.latitude }}, {{ member.longitude }}</p>
                                </div>
                            </div>

                            <div class="border-b border-slate-700/60 pb-2 pt-2">
                                <h3 class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Profil</h3>
                            </div>
                            
                            <div class="space-y-3">
                                <div>
                                    <p class="text-[8px] font-medium text-slate-400 uppercase tracking-wider">Genre</p>
                                    <p class="font-semibold text-slate-200 mt-0.5">{{ member.gender || 'Non renseigné' }}</p>
                                </div>
                                <div>
                                    <p class="text-[8px] font-medium text-slate-400 uppercase tracking-wider">Date de naissance</p>
                                    <p class="font-semibold text-slate-200 mt-0.5">{{ formatDate(member.birth_date) }}</p>
                                </div>
                                <div>
                                    <p class="text-[8px] font-medium text-slate-400 uppercase tracking-wider">Profession</p>
                                    <p class="font-semibold text-slate-200 mt-0.5">{{ member.profession || 'Non renseignée' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Footer -->
                    <div class="text-[8px] text-slate-400 border-t border-slate-700/40 pt-4 mt-6 text-center">
                        <p>EGLISE DU CHRIST</p>
                        <p class="mt-0.5 text-[7px] text-slate-500">Décret N° 71/DF/619</p>
                    </div>
                </div>

                <!-- Right Main Content -->
                <div class="flex-1 p-6 flex flex-col justify-between">
                    <div>
                        <!-- Header with Church Info & Member Title -->
                        <div class="flex justify-between items-start border-b border-slate-100 pb-4">
                            <div>
                                <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-none">
                                    {{ member.last_name }}
                                </h1>
                                <p class="text-lg font-semibold text-blue-600 mt-1">
                                    {{ member.first_name }}
                                </p>
                                
                                <!-- Role / Department badge(s) -->
                                <div class="flex flex-wrap gap-1 mt-2">
                                    <span v-for="dept in (member.department ? member.department.split(',') : ['Membre'])" :key="dept" 
                                          class="px-2 py-0.5 bg-blue-50 text-blue-700 dark:bg-blue-950/20 dark:text-blue-400 rounded text-[9px] font-bold uppercase tracking-wider">
                                        {{ dept.trim() }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Fiche d'identification</p>
                                <p class="text-xs font-bold text-slate-800 uppercase mt-0.5">EGLISE DU CHRIST</p>
                                <p class="text-[8px] text-slate-500 mt-0.5">Décret N° 71/DF/619 du 14 Déc 1971</p>
                            </div>
                        </div>

                        <!-- Section: Attendances -->
                        <div class="mt-6">
                            <div class="flex items-center gap-2 border-b border-slate-100 pb-1.5">
                                <div class="h-2 w-2 rounded-full bg-blue-600"></div>
                                <h2 class="text-[10px] font-bold tracking-widest text-slate-800 uppercase">Présences aux cultes (Dimanche)</h2>
                            </div>
                            <p class="text-[9px] text-slate-500 font-semibold mt-2 mb-3">
                                Pour le mois de {{ currentMonthLabel }} : {{ sundayAttendancesCount }} présence(s) enregistrée(s)
                            </p>
                            
                            <div class="grid grid-cols-2 gap-2.5">
                                <div
                                    v-for="attendance in sundayAttendances.slice(0, 6)"
                                    :key="'print-' + attendance.date + attendance.time"
                                    class="p-2 rounded-lg bg-slate-50 border border-slate-100/50 flex items-center justify-between"
                                >
                                    <div>
                                        <p class="font-bold text-slate-800 text-[10px]">{{ attendance.date }}</p>
                                        <p class="text-slate-500 text-[9px] mt-0.5">{{ attendance.service_type }}</p>
                                    </div>
                                    <div class="text-[9px] font-semibold text-slate-600 bg-white border px-1.5 py-0.5 rounded">
                                        {{ attendance.time }}
                                    </div>
                                </div>
                                <div v-if="!sundayAttendances.length" class="col-span-2 py-4 text-center rounded-lg border border-dashed text-slate-400 italic text-[10px]">
                                    Aucune présence enregistrée pour ce mois.
                                </div>
                            </div>
                        </div>

                        <!-- Section: Sainte Cène -->
                        <div class="mt-6">
                            <div class="flex items-center gap-2 border-b border-slate-100 pb-1.5">
                                <div class="h-2 w-2 rounded-full bg-blue-600"></div>
                                <h2 class="text-[10px] font-bold tracking-widest text-slate-800 uppercase">Préparations Sainte Cène récurrentes</h2>
                            </div>
                            
                            <div class="mt-3 space-y-2">
                                <div
                                    v-for="preparation in communionPreparations.slice(0, 4)"
                                    :key="'print-prep-' + preparation.date + preparation.time"
                                    class="p-2.5 rounded-lg border border-slate-100 bg-slate-50/50 flex flex-col justify-between"
                                >
                                    <div class="flex justify-between items-center">
                                        <span class="font-bold text-slate-800 text-[10px]">{{ preparation.date }} à {{ preparation.time }}</span>
                                        <span class="px-1.5 py-0.5 text-[8px] font-bold rounded uppercase tracking-wider"
                                              :class="preparation.remote ? 'bg-purple-100 text-purple-700' : 'bg-cyan-100 text-cyan-700'">
                                            {{ preparation.remote ? 'À distance' : 'Sur place' }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 text-[9px] text-slate-500 mt-1.5 border-t border-slate-100/60 pt-1.5">
                                        <span class="truncate"><strong>Verset :</strong> {{ preparation.verse_reference || '-' }}</span>
                                        <span class="text-right truncate"><strong>Réf. Paiement :</strong> {{ preparation.payment_reference || '-' }}</span>
                                    </div>
                                </div>
                                <div v-if="!communionPreparations.length" class="py-4 text-center rounded-lg border border-dashed text-slate-400 italic text-[10px]">
                                    Aucune préparation Sainte Cène enregistrée.
                                </div>
                            </div>
                        </div>

                        <!-- Section: Contributions Speciales -->
                        <div class="mt-6">
                            <div class="flex items-center gap-2 border-b border-slate-100 pb-1.5">
                                <div class="h-2 w-2 rounded-full bg-blue-600"></div>
                                <h2 class="text-[10px] font-bold tracking-widest text-slate-800 uppercase">Contributions événements & projets</h2>
                            </div>
                            
                            <div class="mt-3 space-y-2">
                                <div
                                    v-for="contribution in contributions.slice(0, 3)"
                                    :key="contribution.id"
                                    class="p-2 rounded-lg border border-slate-100/50 flex justify-between items-center text-[9px]"
                                >
                                    <div>
                                        <p class="font-bold text-slate-800">{{ contribution.event_title }}</p>
                                        <p class="text-slate-400 text-[8px] mt-0.5">{{ contribution.date }} · {{ contribution.payment_method }}</p>
                                    </div>
                                    <span class="font-mono font-bold text-blue-700 bg-blue-50/50 border border-blue-100/30 px-2 py-0.5 rounded text-[10px]">
                                        {{ contribution.amount }} FCFA
                                    </span>
                                </div>
                                <div v-if="!contributions.length" class="py-4 text-center rounded-lg border border-dashed text-slate-400 italic text-[10px]">
                                    Aucune contribution enregistrée.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Footer -->
                    <div class="text-[9px] text-slate-400 border-t border-slate-100 pt-4 mt-6 text-center">
                        <p>© {{ new Date().getFullYear() }} EGLISE DU CHRIST • Document officiel généré par la Plateforme PASDEBOC</p>
                    </div>
                </div>

            </div>
        </section>
    </AppLayout>
</template>

<style>
@media print {
    body {
        background: #ffffff;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .no-print,
    aside,
    header,
    nav {
        display: none !important;
    }

    .print-profile {
        display: block !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    @page {
        size: A4;
        margin: 10mm;
    }
}
</style>
