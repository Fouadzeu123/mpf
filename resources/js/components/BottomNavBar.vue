<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import {
    Church,
    CreditCard,
    LayoutGrid,
    Printer,
    QrCode,
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
    { title: 'Membres', href: '/members', icon: Users, roles: ['admin', 'secretaire', 'ancienne'] },
    { title: 'Visiteurs', href: '/visitors', icon: UserPlus, roles: ['admin', 'secretaire'] },
    { title: 'Scanner QR', href: '/scanner', icon: QrCode, roles: ['admin', 'protocole', 'ancienne', 'secretaire'] },
    { title: 'Présences', href: '/presences', icon: UserCheck, roles: ['admin'] },
    { title: 'Sainte Cène', href: '/sainte-cene', icon: Wine, roles: ['admin'] },
    { title: 'Impression A4', href: '/impression', icon: Printer, roles: ['admin', 'secretaire'] },
    { title: 'Paiements', href: '/paiements', icon: CreditCard, roles: ['admin'] },
];

const navItems: NavItem[] = allNavItems.filter((item) => {
    return !item.roles || (role ? item.roles.includes(role) : false);
});

// Limit to 5 main items for mobile, add more only if screen is large
const displayedItems = navItems.slice(0, 5);
</script>

<template>
    <nav class="fixed bottom-0 left-0 right-0 border-t border-border bg-background md:hidden">
        <div class="flex h-16 items-center justify-around px-2">
            <Link
                v-for="item in displayedItems"
                :key="item.href"
                :href="item.href"
                class="flex flex-col items-center justify-center gap-1 px-2 py-1 text-xs font-medium transition-colors"
                :class="[
                    isCurrentUrl(item.href)
                        ? 'text-primary'
                        : 'text-muted-foreground hover:text-foreground',
                ]"
            >
                <component :is="item.icon" class="h-5 w-5" />
                <span class="text-[10px] text-center">{{ item.title }}</span>
            </Link>
        </div>
    </nav>
</template>
