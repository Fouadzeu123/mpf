<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Printer } from 'lucide-vue-next';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    members: Array<{ id: number; first_name: string; last_name: string; member_code: string }>;
    visitors: Array<{ id: number; first_name: string; last_name: string; qr_code: string }>;
}>();

const selectedMembers = ref<number[]>([]);
const tab = ref<'members' | 'visitors'>('members');

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
    const csrf = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content;

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
            <div class="rounded-3xl bg-gradient-to-br from-slate-950 via-slate-900 to-amber-950 p-6 text-white shadow-xl">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-amber-300">
                    Impression PDF
                </p>
                <h1 class="mt-2 text-3xl font-black">Cartes membres A4</h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-300">
                    Sélectionnez les membres puis téléchargez un seul PDF. Le document place les cartes normalement sur A4 en 2 colonnes et 5 lignes.
                </p>
            </div>

            <div class="mt-4 flex gap-2 border-b">
                <button
                    class="px-4 py-2 text-sm font-medium"
                    :class="tab === 'members' ? 'border-b-2 border-primary' : ''"
                    @click="tab = 'members'"
                >
                    Membres
                </button>
                <button
                    class="px-4 py-2 text-sm font-medium"
                    :class="tab === 'visitors' ? 'border-b-2 border-primary' : ''"
                    @click="tab = 'visitors'"
                >
                    Visiteurs
                </button>
            </div>

            <div v-if="tab === 'members'" class="rounded-3xl border bg-card p-4 shadow-sm">
                <div class="mb-4 flex flex-wrap gap-2">
                    <Button variant="outline" size="sm" @click="selectAllMembers"
                        >Tout sélectionner</Button
                    >
                    <Button :disabled="!selectedMembers.length" @click="printMembers">
                        <Printer class="mr-2 h-4 w-4" />
                        Télécharger PDF A4 ({{ selectedMembers.length }})
                    </Button>
                </div>
                <p class="mb-3 text-sm text-muted-foreground">
                    Exemple : sélectionnez 10 membres pour obtenir une page A4 complète avec 2 cartes par ligne et 5 cartes par colonne.
                </p>
                <div class="grid max-h-[32rem] gap-2 overflow-y-auto sm:grid-cols-2 xl:grid-cols-3">
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
                        <span class="text-sm">{{ m.first_name }} {{ m.last_name }}</span>
                        <span class="text-xs text-muted-foreground">{{ m.member_code }}</span>
                    </label>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
