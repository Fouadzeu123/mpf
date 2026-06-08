<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Home, CreditCard, LogOut, Tv } from 'lucide-vue-next';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import type { NavItem } from '@/types';

const { isCurrentUrl } = useCurrentUrl();

const navItems: NavItem[] = [
    { title: 'Accueil', href: '/membre/espace', icon: Home },
    { title: 'Flux Vidéo', href: '/membre/flux', icon: Tv },
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
        class="fixed bottom-0 left-1/2 z-50 w-full max-w-lg -translate-x-1/2 border-t border-slate-200/80 bg-card/90 px-4 py-2 shadow-lg backdrop-blur-md dark:border-slate-800/80 dark:bg-slate-950/90"
    >
        <div class="flex items-center justify-around">
            <Link
                v-for="item in navItems"
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
            <Link
                :href="logoutItem.href"
                method="post"
                as="button"
                class="relative flex flex-col items-center justify-center gap-1 px-3 py-1.5 text-xs font-semibold text-muted-foreground transition-all duration-300 hover:text-foreground"
            >
                <div class="flex h-9 w-9 items-center justify-center rounded-xl transition-all duration-300 text-muted-foreground hover:bg-red-500/10 hover:text-red-500">
                    <component :is="logoutItem.icon" class="h-5 w-5" />
                </div>
                <span class="text-center text-[9px] tracking-tight">{{ logoutItem.title }}</span>
            </Link>
        </div>
    </nav>
</template>
