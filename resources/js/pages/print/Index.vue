<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Printer } from 'lucide-vue-next';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    members: Array<{
        id: number;
        first_name: string;
        last_name: string;
        member_code: string;
    }>;
    visitors: Array<{
        id: number;
        first_name: string;
        last_name: string;
        qr_code: string;
    }>;
}>();

const selectedMembers = ref<number[]>([]);
const tab = ref<'members' | 'visitors' | 'reports'>('members');

const reportMonth = ref(new Date().toISOString().substring(0, 7));
const communionMonth = ref(new Date().toISOString().substring(0, 7));
const communionDate = ref('');

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
                    Sélectionnez les membres puis téléchargez un seul PDF. Le
                    document place les cartes normalement sur A4 en 2 colonnes
                    et 5 lignes.
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
                <div class="mb-4 flex flex-wrap gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        @click="selectAllMembers"
                        >Tout sélectionner</Button
                    >
                    <Button
                        :disabled="!selectedMembers.length"
                        @click="printMembers"
                    >
                        <Printer class="mr-2 h-4 w-4" />
                        Télécharger PDF A4 ({{ selectedMembers.length }})
                    </Button>
                </div>
                <p class="mb-3 text-sm text-muted-foreground">
                    Exemple : sélectionnez 10 membres pour obtenir une page A4
                    complète avec 2 cartes par ligne et 5 cartes par colonne.
                </p>
                <div
                    class="grid max-h-[32rem] gap-2 overflow-y-auto sm:grid-cols-2 xl:grid-cols-3"
                >
                    <label
                        v-for="m in members"
                        :key="m.id"
                        class="flex cursor-pointer items-center gap-3 rounded-2xl border bg-background p-3 hover:bg-muted/50"
                    >
                        <input
                            type="checkbox"
                            :checked="selectedMembers.includes(m.id)"
                            @change="toggleMember(m.id)"
                        />
                        <span class="text-sm"
                            >{{ m.first_name }} {{ m.last_name }}</span
                        >
                        <span class="text-xs text-muted-foreground">{{
                            m.member_code
                        }}</span>
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
                </div>
            </div>
        </div>
    </AppLayout>
</template>
