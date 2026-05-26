<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { LogOut } from 'lucide-vue-next';
import ChurchLogo from '@/components/ChurchLogo.vue';
import ThemeToggle from '@/components/ThemeToggle.vue';
import ToastFlash from '@/components/ToastFlash.vue';
import BottomNavBarMember from '@/components/BottomNavBarMember.vue';

defineProps<{ title?: string }>();
</script>

<template>
    <Head :title="title" />
    <ToastFlash />
    <div
        class="min-h-screen bg-gradient-to-b from-background via-background to-accent/30"
    >
        <header
            class="sticky top-0 z-10 border-b border-border/60 bg-card/80 px-4 py-3 backdrop-blur-md"
        >
            <div class="mx-auto flex max-w-lg items-center justify-between">
                <ChurchLogo size="sm" :show-name="false" />
                <div class="flex items-center gap-1">
                    <ThemeToggle />
                    <Link
                        href="/membre/deconnexion"
                        method="post"
                        as="button"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-muted-foreground hover:bg-muted hover:text-foreground"
                        title="Déconnexion"
                    >
                        <LogOut class="h-4 w-4" />
                    </Link>
                </div>
            </div>
        </header>
        <main class="mx-auto max-w-lg p-4 pb-20">
            <slot />
        </main>
        <BottomNavBarMember />
    </div>
</template>
