<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Printer, Camera, CheckSquare, Square } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    members: Array<{
        id: number;
        first_name: string;
        last_name: string;
        member_code: string;
        has_photo?: boolean;
        photo_url?: string | null;
    }>;
    visitors: Array<{
        id: number;
        first_name: string;
        last_name: string;
        qr_code: string;
    }>;
}>();

const membersWithPhotoCount = computed(
    () => props.members.filter((m) => m.has_photo).length,
);

const selectedMembers = ref<number[]>([]);
const tab = ref<'members' | 'visitors' | 'reports'>('members');

const reportMonth = ref(new Date().toISOString().substring(0, 7));
const communionMonth = ref(new Date().toISOString().substring(0, 7));
const communionDate = ref('');
const absentMonth = ref(new Date().toISOString().substring(0, 7));
const absentDate = ref('');
const nonPreparedMonth = ref(new Date().toISOString().substring(0, 7));
const nonPreparedDate = ref('');

function downloadMembersList() {
    window.open('/impression/liste-membres', '_blank');
}

function downloadPresences() {
    window.open(`/impression/presences-mois?month=${reportMonth.value}`, '_blank');
}

function downloadCommunion() {
    let url = `/impression/communion-prepares?month=${communionMonth.value}`;
    if (communionDate.value) {
        url += `&date=${communionDate.value}`;
    }
    window.open(url, '_blank');
}

function downloadAbsents() {
    let url = `/impression/absents-culte?month=${absentMonth.value}`;
    if (absentDate.value) {
        url += `&date=${absentDate.value}`;
    }
    window.open(url, '_blank');
}

function downloadNonPrepared() {
    let url = `/impression/communion-non-prepares?month=${nonPreparedMonth.value}`;
    if (nonPreparedDate.value) {
        url += `&date=${nonPreparedDate.value}`;
    }
    window.open(url, '_blank');
}

function toggleMember(id: number) {
    const i = selectedMembers.value.indexOf(id);

    if (i >= 0) {
        selectedMembers.value.splice(i, 1);
    } else {
        selectedMembers.value.push(id);
    }
}

function selectAllMembers() {
    selectedMembers.value = props.members.map((m) => m.id);
}

function selectMembersWithPhoto() {
    selectedMembers.value = props.members
        .filter((m) => m.has_photo)
        .map((m) => m.id);
}

function deselectAllMembers() {
    selectedMembers.value = [];
}

function printMembers() {
    if (!selectedMembers.value.length) {
        return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/impression/membres';
    form.target = '_blank';
    const csrf = document.querySelector<HTMLMetaElement>(
        'meta[name="csrf-token"]',
    )?.content;

    if (csrf) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = '_token';
        input.value = csrf;
        form.appendChild(input);
    }

    selectedMembers.value.forEach((id) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = String(id);
        form.appendChild(input);
    });
    document.body.appendChild(form);
    form.submit();
    form.remove();
}
</script>

<template>
    <Head title="Impression A4" />
    <AppLayout :breadcrumbs="[{ title: 'Impression A4', href: '/impression' }]">
        <div class="space-y-5 bg-slate-50 p-4 dark:bg-slate-950">
            <div
                class="rounded-3xl bg-gradient-to-br from-blue-950 via-indigo-950 to-slate-900 p-6 text-white shadow-xl"
            >
                <p
                    class="text-sm font-semibold tracking-[0.25em] text-blue-300 uppercase"
                >
                    Impression PDF
                </p>
                <h1 class="mt-2 text-3xl font-black">Cartes membres A4</h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-300">
                    Sélectionnez les membres puis téléchargez le fichier PDF. Le
                    document génère une planche A4 de 10 cartes (2 colonnes × 5 rangées,
                    format standard 85×55 mm avec marge de sécurité et découpe massicot).
                </p>
            </div>

            <div class="mt-4 flex gap-2 border-b">
                <button
                    class="px-4 py-2 text-sm font-medium"
                    :class="
                        tab === 'members' ? 'border-b-2 border-primary' : ''
                    "
                    @click="tab = 'members'"
                >
                    Membres
                </button>
                <button
                    class="px-4 py-2 text-sm font-medium"
                    :class="
                        tab === 'visitors' ? 'border-b-2 border-primary' : ''
                    "
                    @click="tab = 'visitors'"
                >
                    Visiteurs
                </button>
                <button
                    class="px-4 py-2 text-sm font-medium"
                    :class="
                        tab === 'reports' ? 'border-b-2 border-primary' : ''
                    "
                    @click="tab = 'reports'"
                >
                    Listes & Rapports
                </button>
            </div>

            <div
                v-if="tab === 'members'"
                class="rounded-3xl border bg-card p-4 shadow-sm"
            >
                <div class="mb-4 flex flex-wrap items-center gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        @click="selectAllMembers"
                    >
                        <CheckSquare class="mr-1.5 h-4 w-4" />
                        Tout sélectionner ({{ members.length }})
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        class="border-emerald-500/40 text-emerald-700 hover:bg-emerald-50 dark:text-emerald-300 dark:hover:bg-emerald-950/30"
                        @click="selectMembersWithPhoto"
                    >
                        <Camera class="mr-1.5 h-4 w-4 text-emerald-600" />
                        Sélectionner avec photo ({{ membersWithPhotoCount }})
                    </Button>
                    <Button
                        v-if="selectedMembers.length"
                        variant="ghost"
                        size="sm"
                        @click="deselectAllMembers"
                    >
                        Tout désélectionner
                    </Button>
                    <div class="ml-auto">
                        <Button
                            :disabled="!selectedMembers.length"
                            @click="printMembers"
                        >
                            <Printer class="mr-2 h-4 w-4" />
                            Télécharger PDF A4 ({{ selectedMembers.length }})
                        </Button>
                    </div>
                </div>
                <div class="mb-3 flex items-center justify-between text-xs text-muted-foreground">
                    <p>
                        Format fini des cartes : <strong>85 × 55 mm</strong> avec <strong>3 mm de fond perdu</strong> (91 × 61 mm). Disposées à raison de <strong>10 cartes par page A4</strong> (2 colonnes × 5 rangées).
                    </p>
                    <span class="font-medium text-slate-700 dark:text-slate-300">
                        {{ selectedMembers.length }} sélectionné(s) / {{ members.length }} membres
                    </span>
                </div>
                <div
                    class="grid max-h-[32rem] gap-2 overflow-y-auto sm:grid-cols-2 xl:grid-cols-3"
                >
                    <label
                        v-for="m in members"
                        :key="m.id"
                        class="flex cursor-pointer items-center gap-3 rounded-2xl border bg-background p-3 transition hover:bg-muted/50"
                        :class="selectedMembers.includes(m.id) ? 'border-primary/50 bg-primary/5' : ''"
                    >
                        <input
                            type="checkbox"
                            :checked="selectedMembers.includes(m.id)"
                            class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary"
                            @change="toggleMember(m.id)"
                        />
                        <div class="relative shrink-0">
                            <img
                                v-if="m.photo_url"
                                :src="m.photo_url"
                                class="h-10 w-10 rounded-full object-cover border border-slate-200 dark:border-slate-700"
                                alt=""
                            />
                            <div
                                v-else
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300"
                            >
                                {{ m.first_name[0] }}{{ m.last_name[0] }}
                            </div>
                            <span
                                v-if="m.has_photo"
                                class="absolute -bottom-0.5 -right-0.5 rounded-full bg-emerald-500 p-0.5 text-white ring-2 ring-white dark:ring-slate-900"
                                title="Photo disponible"
                            >
                                <Camera class="h-2.5 w-2.5" />
                            </span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">
                                {{ m.first_name }} {{ m.last_name }}
                            </p>
                            <p class="font-mono text-xs text-muted-foreground">
                                {{ m.member_code }}
                            </p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Reports Tab -->
            <div
                v-if="tab === 'reports'"
                class="rounded-3xl border bg-card p-6 shadow-sm space-y-6"
            >
                <div>
                    <h2 class="text-xl font-bold">Impression de Listes & Rapports</h2>
                    <p class="text-sm text-muted-foreground mt-1">
                        Générez et téléchargez des rapports au format PDF paysage, conçus de manière très compacte pour tenir sur une seule ligne par membre.
                    </p>
                </div>

                <div class="grid gap-6 md:grid-cols-3">
                    <!-- Report 1: Members List -->
                    <div class="rounded-2xl border bg-background p-5 flex flex-col justify-between min-h-[12rem]">
                        <div>
                            <h3 class="font-bold text-base text-slate-800 dark:text-slate-200">Liste des Membres</h3>
                            <p class="text-xs text-muted-foreground mt-2">
                                Exporte la liste complète de tous les membres de l'église avec leurs informations détaillées.
                            </p>
                        </div>
                        <Button class="w-full mt-4" @click="downloadMembersList">
                            <Printer class="mr-2 h-4 w-4" />
                            Imprimer la Liste
                        </Button>
                    </div>

                    <!-- Report 2: Monthly Attendances -->
                    <div class="rounded-2xl border bg-background p-5 flex flex-col justify-between min-h-[12rem]">
                        <div>
                            <h3 class="font-bold text-base text-slate-800 dark:text-slate-200">Présences du Mois</h3>
                            <p class="text-xs text-muted-foreground mt-2">
                                Exporte la liste des présences enregistrées pour le mois sélectionné.
                            </p>
                            <input
                                type="month"
                                v-model="reportMonth"
                                class="mt-3 w-full rounded-lg border px-3 py-1.5 text-sm bg-background"
                            />
                        </div>
                        <Button class="w-full mt-4" @click="downloadPresences">
                            <Printer class="mr-2 h-4 w-4" />
                            Imprimer les Présences
                        </Button>
                    </div>

                    <!-- Report 3: Communion Preparation -->
                    <div class="rounded-2xl border bg-background p-5 flex flex-col justify-between min-h-[12rem]">
                        <div>
                            <h3 class="font-bold text-base text-slate-800 dark:text-slate-200">Membres Sainte Cène</h3>
                            <p class="text-xs text-muted-foreground mt-2">
                                Exporte la liste des membres ayant préparé la Sainte Cène pour un mois ou une date spécifique.
                            </p>
                            <div class="space-y-2 mt-2">
                                <div class="flex flex-col gap-0.5">
                                    <label class="text-[10px] text-slate-500 font-semibold">Par Mois (requis)</label>
                                    <input
                                        type="month"
                                        v-model="communionMonth"
                                        class="w-full rounded-lg border px-3 py-1.5 text-sm bg-background"
                                    />
                                </div>
                                <div class="flex flex-col gap-0.5">
                                    <label class="text-[10px] text-slate-500 font-semibold">Par Date Spécifique (optionnel)</label>
                                    <input
                                        type="date"
                                        v-model="communionDate"
                                        class="w-full rounded-lg border px-3 py-1.5 text-sm bg-background"
                                    />
                                </div>
                            </div>
                        </div>
                        <Button class="w-full mt-4" @click="downloadCommunion">
                            <Printer class="mr-2 h-4 w-4" />
                            Imprimer les Préparations
                        </Button>
                    </div>

                    <!-- Report 4: Absents du Culte -->
                    <div class="rounded-2xl border bg-background p-5 flex flex-col justify-between min-h-[12rem]">
                        <div>
                            <h3 class="font-bold text-base text-slate-800 dark:text-slate-200">Absents aux Cultes</h3>
                            <p class="text-xs text-muted-foreground mt-2">
                                Exporte la liste des membres actifs n'ayant aucune présence enregistrée pour un mois ou un jour.
                            </p>
                            <div class="space-y-2 mt-2">
                                <div class="flex flex-col gap-0.5">
                                    <label class="text-[10px] text-slate-500 font-semibold">Par Mois (requis)</label>
                                    <input
                                        type="month"
                                        v-model="absentMonth"
                                        class="w-full rounded-lg border px-3 py-1.5 text-sm bg-background"
                                    />
                                </div>
                                <div class="flex flex-col gap-0.5">
                                    <label class="text-[10px] text-slate-500 font-semibold">Par Date Spécifique (optionnel)</label>
                                    <input
                                        type="date"
                                        v-model="absentDate"
                                        class="w-full rounded-lg border px-3 py-1.5 text-sm bg-background"
                                    />
                                </div>
                            </div>
                        </div>
                        <Button class="w-full mt-4" @click="downloadAbsents">
                            <Printer class="mr-2 h-4 w-4" />
                            Imprimer les Absents
                        </Button>
                    </div>

                    <!-- Report 5: Non-préparés Sainte Cène -->
                    <div class="rounded-2xl border bg-background p-5 flex flex-col justify-between min-h-[12rem]">
                        <div>
                            <h3 class="font-bold text-base text-slate-800 dark:text-slate-200">Membres sans Préparation</h3>
                            <p class="text-xs text-muted-foreground mt-2">
                                Exporte la liste des membres actifs n'ayant pas préparé la Sainte Cène pour un mois ou un jour.
                            </p>
                            <div class="space-y-2 mt-2">
                                <div class="flex flex-col gap-0.5">
                                    <label class="text-[10px] text-slate-500 font-semibold">Par Mois (requis)</label>
                                    <input
                                        type="month"
                                        v-model="nonPreparedMonth"
                                        class="w-full rounded-lg border px-3 py-1.5 text-sm bg-background"
                                    />
                                </div>
                                <div class="flex flex-col gap-0.5">
                                    <label class="text-[10px] text-slate-500 font-semibold">Par Date Spécifique (optionnel)</label>
                                    <input
                                        type="date"
                                        v-model="nonPreparedDate"
                                        class="w-full rounded-lg border px-3 py-1.5 text-sm bg-background"
                                    />
                                </div>
                            </div>
                        </div>
                        <Button class="w-full mt-4" @click="downloadNonPrepared">
                            <Printer class="mr-2 h-4 w-4" />
                            Imprimer les Non-préparés
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
