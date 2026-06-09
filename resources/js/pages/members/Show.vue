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
    const parts = dateStr.split('-');
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
            <div class="mx-auto w-[180mm] p-4 font-sans text-xs">
                <!-- Header -->
                <div class="flex items-center justify-between pb-6">
                    <div class="flex items-center gap-4">
                        <img
                            :src="getAppUrl('/favicon.svg')"
                            alt="Logo"
                            class="h-16 w-16 object-contain"
                        />
                        <div>
                            <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                                Fiche d'identification
                            </p>
                            <h1 class="text-xl font-black uppercase text-slate-900">
                                Ministère Prophétique de la Foi
                            </h1>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">
                            Code Membre
                        </p>
                        <p class="font-mono text-base font-black text-slate-900">
                            {{ member.member_code }}
                        </p>
                    </div>
                </div>

                <!-- Profile Info & Photo block -->
                <div class="grid grid-cols-3 gap-6 pt-4">
                    <!-- Photo column -->
                    <div class="col-span-1 flex flex-col items-center">
                        <img
                            v-if="member.photo_url"
                            :src="member.photo_url"
                            class="h-44 w-32 rounded-xl object-cover"
                            alt=""
                        />
                        <div
                            v-else
                            class="flex h-44 w-32 items-center justify-center rounded-xl bg-slate-100 text-3xl font-black text-slate-400"
                        >
                            {{ member.first_name[0] }}{{ member.last_name[0] }}
                        </div>
                    </div>

                    <!-- Details Column -->
                    <div class="col-span-2 space-y-4">
                        <div>
                            <h2 class="text-2xl font-black uppercase text-slate-900">
                                {{ member.last_name }}
                            </h2>
                            <p class="text-lg font-bold text-blue-600">
                                {{ member.first_name }}
                            </p>
                        </div>

                        <!-- Information Table (No borders, clean alignment) -->
                        <div class="grid grid-cols-2 gap-y-3 gap-x-6 text-xs">
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase">Sexe</p>
                                <p class="font-semibold text-slate-800">{{ member.gender || 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase">Date de naissance</p>
                                <p class="font-semibold text-slate-800">{{ formatDate(member.birth_date) }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase">Téléphone</p>
                                <p class="font-semibold text-slate-800">{{ member.phone || 'Non renseigné' }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase">Profession</p>
                                <p class="font-semibold text-slate-800">{{ member.profession || 'Non renseignée' }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-[9px] font-bold text-slate-400 uppercase">Département(s)</p>
                                <p class="font-semibold text-slate-800">{{ member.department || 'Aucun département' }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-[9px] font-bold text-slate-400 uppercase">Adresse complète</p>
                                <p class="font-semibold text-slate-800">{{ member.address_description || 'Adresse non renseignée' }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-[9px] font-bold text-slate-400 uppercase">Coordonnées GPS</p>
                                <p class="font-semibold text-slate-800">
                                    {{ member.latitude && member.longitude ? `${member.latitude}, ${member.longitude}` : 'Non renseigné' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sunday Attendances and Sainte Cene (side by side, very clean) -->
                <div class="grid grid-cols-2 gap-8 pt-8 mt-8">
                    <!-- Column 1: Attendances -->
                    <div>
                        <h3 class="text-[10px] font-bold tracking-wider text-slate-400 uppercase mb-3">
                            Présences au culte (Dimanche)
                        </h3>
                        <p class="text-xs font-semibold text-slate-700 mb-3">
                            {{ currentMonthLabel }} : {{ sundayAttendancesCount }} présence(s)
                        </p>
                        
                        <div class="space-y-3">
                            <div
                                v-for="attendance in sundayAttendances.slice(0, 5)"
                                :key="'print-' + attendance.date + attendance.time"
                                class="py-0.5"
                            >
                                <p class="font-bold text-slate-800 text-[11px]">{{ attendance.date }} · {{ attendance.time }}</p>
                                <p class="text-slate-500 text-[10px]">{{ attendance.service_type }}</p>
                            </div>
                            <p v-if="!sundayAttendances.length" class="text-xs text-slate-400 italic">
                                Aucune présence au culte ce mois.
                            </p>
                        </div>
                    </div>

                    <!-- Column 2: Sainte Cène preparations -->
                    <div>
                        <h3 class="text-[10px] font-bold tracking-wider text-slate-400 uppercase mb-3">
                            Préparations Sainte Cène
                        </h3>
                        <p class="text-xs font-semibold text-slate-700 mb-3">
                            Dernières préparations
                        </p>
                        
                        <div class="space-y-4">
                            <div
                                v-for="preparation in communionPreparations.slice(0, 4)"
                                :key="'print-prep-' + preparation.date + preparation.time"
                                class="text-[10px]"
                            >
                                <div class="flex justify-between font-bold text-slate-800">
                                    <span>{{ preparation.date }} à {{ preparation.time }}</span>
                                    <span class="text-slate-500">({{ preparation.remote ? 'À distance' : 'Sur place' }})</span>
                                </div>
                                <div class="grid grid-cols-2 gap-1 text-slate-500 mt-0.5">
                                    <span>Verset: {{ preparation.verse_reference || '-' }}</span>
                                    <span class="text-right">Réf: {{ preparation.payment_reference || '-' }}</span>
                                </div>
                            </div>
                            <p v-if="!communionPreparations.length" class="text-xs text-slate-400 italic">
                                Aucune préparation enregistrée.
                            </p>
                        </div>
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
    }

    .no-print,
    aside,
    header,
    nav {
        display: none !important;
    }

    .print-profile {
        display: block !important;
    }

    @page {
        size: A4;
        margin: 15mm;
    }
}
</style>
