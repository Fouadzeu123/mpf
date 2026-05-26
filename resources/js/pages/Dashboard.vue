<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import {
    CreditCard,
    IdCard,
    MapPinned,
    QrCode,
    UserCheck,
    Users,
    UserPlus,
    Wine,
} from 'lucide-vue-next';
import StatCard from '@/components/church/StatCard.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    role: string;
    stats: {
        members: number;
        visitors: number;
        attendances_today: number;
        communion_today: number;
    };
    recentPayments: Array<{
        id: number;
        reference: string;
        amount: number;
        status: string;
        member_name: string;
        created_at: string;
    }>;
    recentActivities: Array<{
        id: number;
        action: string;
        user: string | null;
        created_at: string;
    }>;
    attendanceChart: Array<{ date: string; total: number }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard() },
];

const quickActions = [
    { title: 'Ajouter membre', href: '/members/create', icon: UserPlus, tone: 'bg-slate-950 text-white', roles: ['admin', 'secretaire'] },
    { title: 'Scanner QR', href: '/scanner', icon: QrCode, tone: 'bg-amber-500 text-slate-950', roles: ['admin', 'protocole', 'ancienne', 'secretaire'] },
    { title: 'PDF cartes A4', href: '/impression', icon: IdCard, tone: 'bg-white text-slate-950', roles: ['admin', 'secretaire'] },
    { title: 'Profils membres', href: '/members', icon: Users, tone: 'bg-white text-slate-950', roles: ['admin', 'secretaire', 'ancienne'] },
    { title: 'Présences', href: '/presences', icon: MapPinned, tone: 'bg-slate-100 text-slate-950', roles: ['admin'] },
];

const visibleQuickActions = computed(() => {
    return quickActions.filter((action) => action.roles.includes(props.role));
});

const visibleStats = computed(() => {
    return [
        { title: 'Membres', value: props.stats.members, icon: Users, roles: ['admin', 'secretaire', 'ancienne'] },
        { title: 'Visiteurs', value: props.stats.visitors, icon: UserPlus, roles: ['admin', 'secretaire'] },
        { title: 'Présences aujourd\'hui', value: props.stats.attendances_today, icon: UserCheck, roles: ['admin'] },
        { title: 'Sainte Cène aujourd\'hui', value: props.stats.communion_today, icon: Wine, roles: ['admin'] },
    ].filter((stat) => stat.roles.includes(props.role));
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 bg-slate-50 p-4 dark:bg-slate-950">
            <div class="rounded-3xl bg-gradient-to-br from-slate-950 via-slate-900 to-amber-950 p-6 text-white shadow-xl">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-amber-300">
                    Administration
                </p>
                <h1 class="mt-2 text-3xl font-black tracking-tight">
                    Tableau de bord
                </h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-300">
                    Accès rapide aux membres, présences, cartes et opérations de l'église.
                </p>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <a
                    v-for="action in visibleQuickActions"
                    :key="action.title"
                    :href="action.href"
                    class="group rounded-3xl border border-white/70 p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg dark:border-slate-800"
                    :class="action.tone"
                >
                    <component :is="action.icon" class="h-7 w-7" />
                    <p class="mt-5 text-lg font-black">{{ action.title }}</p>
                    <p class="mt-1 text-xs opacity-70">
                        {{ action.href === '/impression' ? 'Sélectionner et télécharger' : 'Ouvrir' }}
                    </p>
                </a>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <StatCard
                    v-for="stat in visibleStats"
                    :key="stat.title"
                    :title="stat.title"
                    :value="stat.value"
                    :icon="stat.icon"
                />
            </div>

            <div v-if="role === 'admin'" class="grid gap-6 lg:grid-cols-2">
                <div
                    class="rounded-xl border bg-card p-5 shadow-sm"
                >
                    <h2
                        class="mb-4 flex items-center gap-2 font-semibold"
                    >
                        <CreditCard class="h-4 w-4" />
                        Paiements récents
                    </h2>
                    <ul class="space-y-2 text-sm">
                        <li
                            v-for="p in recentPayments"
                            :key="p.id"
                            class="flex justify-between border-b py-2 last:border-0"
                        >
                            <span>{{ p.member_name }}</span>
                            <span class="text-muted-foreground"
                                >{{ p.amount }} FCFA · {{ p.status }}</span
                            >
                        </li>
                        <li
                            v-if="!recentPayments.length"
                            class="text-muted-foreground"
                        >
                            Aucun paiement récent
                        </li>
                    </ul>
                </div>

                <div
                    class="rounded-xl border bg-card p-5 shadow-sm"
                >
                    <h2 class="mb-4 font-semibold">Activités récentes</h2>
                    <ul class="space-y-2 text-sm">
                        <li
                            v-for="a in recentActivities"
                            :key="a.id"
                            class="border-b py-2 last:border-0"
                        >
                            <span class="font-medium">{{ a.action }}</span>
                            <span
                                v-if="a.user"
                                class="text-muted-foreground"
                            >
                                — {{ a.user }}</span
                            >
                            <span class="block text-xs text-muted-foreground">{{
                                a.created_at
                            }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div
                v-if="attendanceChart.length"
                class="rounded-xl border bg-card p-5"
            >
                <h2 class="mb-4 font-semibold">Présences (7 jours)</h2>
                <div class="flex h-32 items-end gap-2">
                    <div
                        v-for="d in attendanceChart"
                        :key="d.date"
                        class="flex flex-1 flex-col items-center gap-1"
                    >
                        <div
                            class="w-full rounded-t bg-primary/80"
                            :style="{
                                height: `${Math.max(8, d.total * 12)}px`,
                            }"
                        />
                        <span class="text-[10px] text-muted-foreground">{{
                            d.date
                        }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
