<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';

defineProps<{
    visitors: {
        data: Array<{
            id: number;
            first_name: string;
            last_name: string;
            qr_code: string;
            visit_date: string;
        }>;
    };
}>();
</script>

<template>
    <Head title="Visiteurs" />
    <AppLayout :breadcrumbs="[{ title: 'Visiteurs', href: '/visitors' }]">
        <div class="p-4">
            <div class="mb-4 flex justify-between">
                <h1 class="text-2xl font-bold">Visiteurs</h1>
                <Link href="/visitors/create">
                    <Button><Plus class="mr-2 h-4 w-4" /> Ajouter</Button>
                </Link>
            </div>
            <div class="overflow-hidden rounded-xl border">
                <table class="w-full text-sm">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-4 py-3 text-left">Nom</th>
                            <th class="px-4 py-3 text-left">QR</th>
                            <th class="px-4 py-3 text-left">Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="v in visitors.data"
                            :key="v.id"
                            class="border-t"
                        >
                            <td class="px-4 py-3">
                                {{ v.first_name }} {{ v.last_name }}
                            </td>
                            <td class="px-4 py-3 font-mono text-xs">
                                {{ v.qr_code }}
                            </td>
                            <td class="px-4 py-3">{{ v.visit_date }}</td>
                            <td class="px-4 py-3">
                                <Link
                                    :href="`/visitors/${v.id}`"
                                    class="text-primary underline"
                                    >Voir</Link
                                >
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
