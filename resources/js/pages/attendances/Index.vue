<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    attendances: {
        data: Array<{
            id: number;
            scanned_at: string;
            service_type: string;
            member: {
                first_name: string;
                last_name: string;
                member_code: string;
            };
            scanner?: { name: string };
        }>;
    };
    filters: { date?: string; service_type?: string };
}>();

const date = ref(props.filters.date ?? '');
const serviceType = ref(props.filters.service_type ?? '');

function filter() {
    router.get('/presences', {
        date: date.value || undefined,
        service_type: serviceType.value || undefined,
    });
}
</script>

<template>
    <Head title="Présences" />
    <AppLayout :breadcrumbs="[{ title: 'Présences', href: '/presences' }]">
        <div class="p-4">
            <h1 class="mb-4 text-2xl font-bold">Historique des présences</h1>
            <div class="mb-4 flex gap-2">
                <input
                    v-model="date"
                    type="date"
                    class="rounded-lg border px-3 py-2 text-sm"
                />
                <Button variant="secondary" @click="filter">Filtrer</Button>
            </div>
            <div class="overflow-x-auto rounded-xl border">
                <table class="w-full text-sm">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-4 py-3 text-left">Membre</th>
                            <th class="px-4 py-3 text-left">Service</th>
                            <th class="px-4 py-3 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="a in attendances.data"
                            :key="a.id"
                            class="border-t"
                        >
                            <td class="px-4 py-3">
                                {{ a.member.first_name }}
                                {{ a.member.last_name }}
                                <span class="text-xs text-muted-foreground">{{
                                    a.member.member_code
                                }}</span>
                            </td>
                            <td class="px-4 py-3">{{ a.service_type }}</td>
                            <td class="px-4 py-3">{{ a.scanned_at }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
