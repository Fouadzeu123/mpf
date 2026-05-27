<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus, Search } from 'lucide-vue-next';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    members: {
        data: Array<{
            id: number;
            member_code: string;
            first_name: string;
            last_name: string;
            department: string | null;
            phone: string | null;
            status: string;
        }>;
        links: unknown;
    };
    filters: { search?: string; department?: string; status?: string };
    departments: string[];
}>();

const search = ref(props.filters.search ?? '');
const department = ref(props.filters.department ?? '');
const status = ref(props.filters.status ?? '');

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Membres', href: '/members' }];

function applyFilters() {
    router.get(
        '/members',
        {
            search: search.value || undefined,
            department: department.value || undefined,
            status: status.value || undefined,
        },
        { preserveState: true },
    );
}
</script>

<template>
    <Head title="Membres" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 p-4">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h1 class="text-2xl font-bold">Membres</h1>
                <Link href="/members/create">
                    <Button
                        ><Plus class="mr-2 h-4 w-4" /> Nouveau membre</Button
                    >
                </Link>
            </div>

            <div class="flex flex-wrap gap-2">
                <div class="relative min-w-[200px] flex-1">
                    <Search
                        class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground"
                    />
                    <input
                        v-model="search"
                        type="search"
                        placeholder="Rechercher..."
                        class="w-full rounded-lg border py-2 pr-3 pl-9 text-sm"
                        @keyup.enter="applyFilters"
                    />
                </div>
                <select
                    v-model="department"
                    class="rounded-lg border px-3 py-2 text-sm"
                    @change="applyFilters"
                >
                    <option value="">Tous départements</option>
                    <option v-for="d in departments" :key="d" :value="d">
                        {{ d }}
                    </option>
                </select>
                <select
                    v-model="status"
                    class="rounded-lg border px-3 py-2 text-sm"
                    @change="applyFilters"
                >
                    <option value="">Tous statuts</option>
                    <option value="active">Actif</option>
                    <option value="inactive">Inactif</option>
                </select>
                <Button variant="secondary" @click="applyFilters"
                    >Filtrer</Button
                >
            </div>

            <div class="overflow-hidden rounded-xl border bg-card">
                <table class="w-full text-sm">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-4 py-3 text-left">Code</th>
                            <th class="px-4 py-3 text-left">Nom</th>
                            <th class="px-4 py-3 text-left">Département</th>
                            <th class="px-4 py-3 text-left">Téléphone</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="m in members.data"
                            :key="m.id"
                            class="border-t hover:bg-muted/30"
                        >
                            <td class="px-4 py-3 font-mono text-xs">
                                {{ m.member_code }}
                            </td>
                            <td class="px-4 py-3">
                                {{ m.first_name }} {{ m.last_name }}
                            </td>
                            <td class="px-4 py-3">{{ m.department ?? '—' }}</td>
                            <td class="px-4 py-3">{{ m.phone ?? '—' }}</td>
                            <td class="px-4 py-3 text-right">
                                <Link
                                    :href="`/members/${m.id}`"
                                    class="text-primary hover:underline"
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
