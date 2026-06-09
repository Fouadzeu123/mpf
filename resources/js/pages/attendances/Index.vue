<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Trash2 } from 'lucide-vue-next';

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

function deleteAttendance(id: number) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cette présence ?")) {
        router.delete(`/presences/${id}`);
    }
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
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="a in attendances.data"
                            :key="a.id"
                            class="border-t hover:bg-muted/10"
                        >
                            <td class="px-4 py-3">
                                {{ a.member.first_name }}
                                {{ a.member.last_name }}
                                <span class="text-xs text-muted-foreground ml-1">({{
                                    a.member.member_code
                                }})</span>
                            </td>
                            <td class="px-4 py-3">{{ a.service_type }}</td>
                            <td class="px-4 py-3">{{ a.scanned_at }}</td>
                            <td class="px-4 py-3 text-right">
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    class="h-8 w-8 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-950/20"
                                    @click="deleteAttendance(a.id)"
                                >
                                    <Trash2 class="h-4 w-4" />
                                </Button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
