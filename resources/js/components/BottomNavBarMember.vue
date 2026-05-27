<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Home, CreditCard, LogOut } from 'lucide-vue-next';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import type { NavItem } from '@/types';

const { isCurrentUrl } = useCurrentUrl();

const navItems: NavItem[] = [
    { title: 'Accueil', href: '/membre/espace', icon: Home },
    { title: 'Paiement', href: '/membre/paiement', icon: CreditCard },
];

const logoutItem: NavItem = {
    title: 'Déconnexion',
    href: '/membre/deconnexion',
    icon: LogOut,
};
</script>

<template>
    <nav
        class="fixed right-0 bottom-0 left-0 border-t border-border bg-background"
    >
        <div
            class="mx-auto flex h-16 max-w-lg items-center justify-around px-2"
        >
            <Link
                v-for="item in navItems"
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
                <span class="text-center text-[10px]">{{ item.title }}</span>
            </Link>
            <Link
                :href="logoutItem.href"
                method="post"
                as="button"
                class="flex flex-col items-center justify-center gap-1 px-2 py-1 text-xs font-medium text-muted-foreground transition-colors hover:text-foreground"
            >
                <component :is="logoutItem.icon" class="h-5 w-5" />
                <span class="text-center text-[10px]">{{
                    logoutItem.title
                }}</span>
            </Link>
        </div>
    </nav>
</template>
