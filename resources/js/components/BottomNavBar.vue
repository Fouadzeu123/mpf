<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import {
    CreditCard,
    LayoutGrid,
    Printer,
    QrCode,
    Tv,
    UserCheck,
    Users,
    UserPlus,
    Wine,
} from 'lucide-vue-next';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

const page = usePage();
const role = page.props.auth.user?.role as string | undefined;
const { isCurrentUrl } = useCurrentUrl();

const allNavItems: Array<NavItem & { roles?: string[] }> = [
    { title: 'Tableau de bord', href: dashboard(), icon: LayoutGrid },
    {
        title: 'Flux Vidéo',
        href: '/flux',
        icon: Tv,
    },
    {
        title: 'Membres',
        href: '/members',
        icon: Users,
        roles: ['admin', 'secretaire', 'ancienne'],
    },
    {
        title: 'Visiteurs',
        href: '/visitors',
        icon: UserPlus,
        roles: ['admin', 'secretaire'],
    },
    {
        title: 'Scanner QR',
        href: '/scanner',
        icon: QrCode,
        roles: ['admin', 'protocole', 'ancienne', 'secretaire'],
    },
    {
        title: 'Présences',
        href: '/presences',
        icon: UserCheck,
        roles: ['admin'],
    },
    {
        title: 'Sainte Cène',
        href: '/sainte-cene',
        icon: Wine,
        roles: ['admin'],
    },
    {
        title: 'Impression A4',
        href: '/impression',
        icon: Printer,
        roles: ['admin', 'secretaire'],
    },
    {
        title: 'Paiements',
        href: '/paiements',
        icon: CreditCard,
        roles: ['admin'],
    },
    {
        title: 'Gérer Vidéos',
        href: '/admin-videos',
        icon: Tv,
        roles: ['admin'],
    },
];

const navItems: NavItem[] = allNavItems.filter((item) => {
    return !item.roles || (role ? item.roles.includes(role) : false);
});

// Limit to 5 main items for mobile, add more only if screen is large
const displayedItems = navItems.slice(0, 5);
</script>

<template>
    <nav
        class="fixed bottom-0 left-0 right-0 z-50 border-t border-slate-200/80 bg-card/90 px-4 py-2 shadow-lg backdrop-blur-md dark:border-slate-800/80 dark:bg-slate-950/90 md:hidden"
    >
        <div class="flex items-center justify-around">
            <Link
                v-for="item in displayedItems"
                :key="item.title"
                :href="item.href"
                class="relative flex flex-col items-center justify-center gap-1 px-3 py-1.5 text-xs font-semibold transition-all duration-300"
                :class="[
                    isCurrentUrl(item.href)
                        ? 'text-primary scale-105'
                        : 'text-muted-foreground hover:text-foreground',
                ]"
            >
                <div
                    class="flex h-9 w-9 items-center justify-center rounded-xl transition-all duration-300"
                    :class="[
                        isCurrentUrl(item.href)
                            ? 'bg-primary/10 text-primary shadow-sm shadow-primary/5 dark:bg-primary/20'
                            : 'text-muted-foreground hover:text-foreground',
                    ]"
                >
                    <component :is="item.icon" class="h-5 w-5" />
                </div>
                <span class="text-center text-[9px] tracking-tight">{{ item.title }}</span>
                <span
                    v-if="isCurrentUrl(item.href)"
                    class="absolute -bottom-1 h-1 w-1 rounded-full bg-primary"
                />
            </Link>
        </div>
    </nav>
</template>
