<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';

const props = defineProps<{
    preparations: {
        data: Array<{
            id: number;
            verse_reference: string;
            verse_text: string;
            whatsapp_sent: boolean;
            remote: boolean;
            created_at: string;
            member: { first_name: string; last_name: string };
        }>;
    };
    preparationDate: string | null;
}>();

const form = useForm({
    preparation_date: props.preparationDate ?? '',
});

function saveDate() {
    form.post('/sainte-cene/configurer', {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Sainte Cène" />
    <AppLayout :breadcrumbs="[{ title: 'Sainte Cène', href: '/sainte-cene' }]">
        <div class="p-4">
            <h1 class="mb-4 text-2xl font-bold">Préparations Sainte Cène</h1>

            <!-- Settings Card -->
            <div class="mb-6 rounded-xl border bg-card p-4 shadow-sm">
                <h2 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-1">Configuration de la date</h2>
                <p class="text-sm text-slate-500 mb-4">
                    Définissez la date spécifique pour la préparation à la Sainte Cène (remplace la règle par défaut du deuxième samedi du mois).
                </p>
                <form @submit.prevent="saveDate" class="flex flex-wrap items-end gap-3">
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Date autorisée</label>
                        <input
                            type="date"
                            v-model="form.preparation_date"
                            class="rounded-lg border px-3 py-1.5 text-sm bg-background min-w-[200px]"
                        />
                    </div>
                    <Button type="submit" size="sm" :disabled="form.processing">
                        {{ form.processing ? 'Enregistrement...' : 'Définir la date' }}
                    </Button>
                    <Button 
                        v-if="form.preparation_date" 
                        type="button" 
                        variant="ghost" 
                        size="sm"
                        @click="form.preparation_date = ''; saveDate()"
                        :disabled="form.processing"
                    >
                        Réinitialiser
                    </Button>
                </form>
            </div>

            <!-- List -->
            <div class="space-y-3">
                <div
                    v-for="p in preparations.data"
                    :key="p.id"
                    class="rounded-xl border bg-card p-4"
                >
                    <div class="flex justify-between">
                        <strong
                            >{{ p.member.first_name }}
                            {{ p.member.last_name }}</strong
                        >
                        <span class="text-xs text-muted-foreground">{{
                            p.created_at
                        }}</span>
                    </div>
                    <p class="mt-2 text-sm font-medium">
                        {{ p.verse_reference }}
                    </p>
                    <p class="text-sm text-muted-foreground">
                        {{ p.verse_text }}
                    </p>
                    <div class="mt-2 flex gap-2 text-xs">
                        <span v-if="p.whatsapp_sent" class="text-emerald-600"
                            >WhatsApp ✓</span
                        >
                        <span v-if="p.remote" class="text-blue-600 dark:text-blue-400"
                            >À distance</span
                        >
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
