<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

defineProps<{
    transactions: {
        data: Array<{
            id: number;
            reference: string;
            amount: number;
            status: string;
            provider: string | null;
            created_at: string;
            member: { first_name: string; last_name: string };
        }>;
    };
}>();
</script>

<template>
    <Head title="Paiements" />
    <AppLayout :breadcrumbs="[{ title: 'Paiements', href: '/paiements' }]">
        <div class="p-4">
            <h1 class="mb-4 text-2xl font-bold">Historique paiements (Notch Pay)</h1>
            <div class="overflow-hidden rounded-xl border">
                <table class="w-full text-sm">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-4 py-3 text-left">Référence</th>
                            <th class="px-4 py-3 text-left">Membre</th>
                            <th class="px-4 py-3 text-left">Montant</th>
                            <th class="px-4 py-3 text-left">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="t in transactions.data" :key="t.id" class="border-t">
                            <td class="px-4 py-3 font-mono text-xs">{{ t.reference }}</td>
                            <td class="px-4 py-3">{{ t.member.first_name }} {{ t.member.last_name }}</td>
                            <td class="px-4 py-3">{{ t.amount }} FCFA</td>
                            <td class="px-4 py-3">{{ t.status }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
