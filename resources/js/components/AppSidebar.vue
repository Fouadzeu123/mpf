<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import {
    CreditCard,
    LayoutGrid,
    Printer,
    QrCode,
    UserCheck,
    Users,
    UserPlus,
    Wine,
} from 'lucide-vue-next';
import AppLogo from '@/components/AppLogo.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import ThemeToggle from '@/components/ThemeToggle.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

const page = usePage();
const role = page.props.auth.user?.role as string | undefined;

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

const mainNavItems: NavItem[] = allNavItems.filter((item) => {
    return !item.roles || (role ? item.roles.includes(role) : false);
});
</script>

<template>
    <div class="hidden md:block">
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" as-child>
                            <Link :href="dashboard()">
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain :items="mainNavItems" />
            </SidebarContent>

            <SidebarFooter>
                <div class="flex items-center justify-between px-2 py-1">
                    <ThemeToggle />
                </div>
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    </div>
    <slot />
</template>
