<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { FileText, MapPin, Pencil, Printer } from 'lucide-vue-next';
import { ref } from 'vue';
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
</script>

<template>
    <Head :title="member.member_code" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="no-print grid gap-6 p-4 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
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

            <div>
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
        </div>

        <section class="print-profile hidden bg-white p-10 text-slate-900">
            <div
                class="mx-auto min-h-[277mm] w-[190mm] border border-slate-200 p-8"
            >
                <div
                    class="flex items-center justify-between border-b-4 border-blue-500 pb-5"
                >
                    <div class="flex items-center gap-4">
                        <img
                            src="/favicon.svg"
                            alt="Logo"
                            class="h-20 w-20 object-contain"
                        />
                        <div>
                            <h1 class="text-2xl font-black uppercase">
                                Fiche complète membre
                            </h1>
                            <p class="text-sm font-semibold text-slate-600">
                                Ministère Prophétique de la Foi
                            </p>
                        </div>
                    </div>
                    <div
                        class="rounded-full bg-slate-950 px-5 py-2 text-sm font-bold text-white"
                    >
                        {{ member.member_code }}
                    </div>
                </div>

                <div class="mt-8 space-y-6">
                    <div>
                        <img
                            v-if="member.photo_url"
                            :src="member.photo_url"
                            alt=""
                            class="h-48 w-36 rounded-xl object-cover ring-2 ring-blue-500/70"
                        />
                        <div
                            v-else
                            class="flex h-48 w-36 items-center justify-center rounded-xl bg-blue-100 text-3xl font-black text-blue-900 ring-2 ring-blue-500/70 dark:bg-blue-950 dark:text-blue-200"
                        >
                            {{ member.first_name[0] }}{{ member.last_name[0] }}
                        </div>
                    </div>

                    <div>
                        <h2 class="text-3xl font-black uppercase">
                            {{ member.last_name }}
                        </h2>
                        <p class="text-2xl font-bold text-primary dark:text-blue-400">
                            {{ member.first_name }}
                        </p>

                        <div class="mt-6 space-y-3 text-sm">
                            <div class="rounded-xl border p-4">
                                <p
                                    class="text-xs font-bold text-slate-500 uppercase"
                                >
                                    Numéro membre
                                </p>
                                <p class="mt-1 font-mono text-lg font-bold">
                                    {{ member.member_code }}
                                </p>
                            </div>
                            <div class="rounded-xl border p-4">
                                <p
                                    class="text-xs font-bold text-slate-500 uppercase"
                                >
                                    Téléphone
                                </p>
                                <p class="mt-1 text-lg font-bold">
                                    {{ member.phone || 'Non renseigné' }}
                                </p>
                            </div>
                            <div class="rounded-xl border p-4">
                                <p
                                    class="text-xs font-bold text-slate-500 uppercase"
                                >
                                    Sexe
                                </p>
                                <p class="mt-1 text-lg font-bold">
                                    {{ member.gender || 'Non renseigné' }}
                                </p>
                            </div>
                            <div class="rounded-xl border p-4">
                                <p
                                    class="text-xs font-bold text-slate-500 uppercase"
                                >
                                    Date de naissance
                                </p>
                                <p class="mt-1 text-lg font-bold">
                                    {{ formatDate(member.birth_date) }}
                                </p>
                            </div>
                            <div class="rounded-xl border p-4">
                                <p
                                    class="text-xs font-bold text-slate-500 uppercase"
                                >
                                    Département
                                </p>
                                <p class="mt-1 text-lg font-bold">
                                    {{ member.department || 'Non renseigné' }}
                                </p>
                            </div>
                            <div class="rounded-xl border p-4">
                                <p
                                    class="text-xs font-bold text-slate-500 uppercase"
                                >
                                    Statut GPS
                                </p>
                                <p class="mt-1 text-lg font-bold">
                                    {{
                                        member.latitude && member.longitude
                                            ? 'Localisé'
                                            : 'Non localisé'
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 rounded-xl border p-5">
                    <p class="text-xs font-bold text-slate-500 uppercase">
                        Adresse complète
                    </p>
                    <p class="mt-2 text-lg font-semibold">
                        {{
                            member.address_description ||
                            'Adresse non renseignée'
                        }}
                    </p>
                </div>

                <div class="mt-6 rounded-xl border p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p
                                class="text-xs font-bold text-slate-500 uppercase"
                            >
                                Localisation exacte
                            </p>
                            <p class="mt-2 font-mono text-base">
                                {{
                                    member.latitude && member.longitude
                                        ? `${member.latitude}, ${member.longitude}`
                                        : 'Coordonnées GPS non renseignées'
                                }}
                            </p>
                        </div>
                        <a
                            v-if="routeUrl"
                            :href="routeUrl"
                            class="rounded-full bg-blue-500 px-5 py-2 text-sm font-bold text-white shadow-sm shadow-blue-500/20"
                        >
                            Voir itinéraire
                        </a>
                    </div>
                    <iframe
                        v-if="
                            googleMapsKey && member.latitude && member.longitude
                        "
                        class="mt-5 h-72 w-full rounded-xl border"
                        :src="`https://www.google.com/maps/embed/v1/place?key=${googleMapsKey}&q=${member.latitude},${member.longitude}`"
                    />
                </div>

                <div class="mt-6 rounded-xl border p-5">
                    <p class="text-xs font-bold text-slate-500 uppercase">
                        Présences au culte du dimanche
                    </p>
                    <p class="mt-2 text-lg font-bold">
                        {{ currentMonthLabel }} :
                        {{ sundayAttendancesCount }} présence(s)
                    </p>
                    <div class="mt-4 space-y-2">
                        <div
                            v-for="attendance in sundayAttendances"
                            :key="'print-' + attendance.date + attendance.time"
                            class="rounded-lg border p-3 text-sm"
                        >
                            <p class="font-bold">{{ attendance.date }}</p>
                            <p>
                                {{ attendance.time }} -
                                {{ attendance.service_type }}
                            </p>
                        </div>
                        <p
                            v-if="!sundayAttendances.length"
                            class="text-sm text-slate-600"
                        >
                            Aucune présence au culte du dimanche pour ce mois.
                        </p>
                    </div>
                </div>

                <div class="mt-6 rounded-xl border p-5">
                    <p class="text-xs font-bold text-slate-500 uppercase">
                        Préparations de carte Sainte Cène
                    </p>
                    <div class="mt-4 space-y-2">
                        <div
                            v-for="preparation in communionPreparations"
                            :key="
                                'print-prep-' +
                                preparation.date +
                                preparation.time +
                                preparation.payment_reference
                            "
                            class="rounded-lg border p-3 text-sm"
                        >
                            <p class="font-bold">
                                {{ preparation.date }} à {{ preparation.time }}
                            </p>
                            <p>
                                Verset :
                                {{
                                    preparation.verse_reference ||
                                    'Non renseigné'
                                }}
                            </p>
                            <p>
                                Paiement :
                                {{
                                    preparation.payment_status ||
                                    'Non renseigné'
                                }}
                            </p>
                            <p>
                                Référence :
                                {{
                                    preparation.payment_reference ||
                                    'Non renseignée'
                                }}
                            </p>
                            <p>
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
                            class="text-sm text-slate-600"
                        >
                            Aucune préparation enregistrée.
                        </p>
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
        margin: 10mm;
    }
}
</style>
