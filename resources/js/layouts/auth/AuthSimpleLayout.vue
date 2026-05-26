<script setup lang="ts">
import ChurchLogo from '@/components/ChurchLogo.vue';
import ThemeToggle from '@/components/ThemeToggle.vue';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="relative flex min-h-svh flex-col bg-background lg:flex-row"
    >
        <div
            class="absolute right-4 top-4 z-20 lg:left-auto lg:right-6"
        >
            <ThemeToggle />
        </div>

        <div
            class="relative flex flex-1 flex-col justify-center overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 px-8 py-12 text-white lg:max-w-[48%] lg:px-14"
        >
            <div
                class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-amber-500/10 blur-3xl"
            />
            <div
                class="pointer-events-none absolute -bottom-16 -left-16 h-48 w-48 rounded-full bg-amber-400/10 blur-2xl"
            />

            <div class="relative z-10 mx-auto max-w-md text-center lg:text-left">
                <ChurchLogo size="xl" variant="auth" class="lg:items-start" />
                <p class="mt-8 text-lg leading-relaxed text-slate-300">
                    Bienvenue dans l'espace de gestion spirituelle et
                    organisationnelle de votre communauté.
                </p>
                <ul class="mt-8 hidden space-y-3 text-sm text-slate-400 lg:block">
                    <li class="flex items-center gap-2">
                        <span class="h-1 w-1 rounded-full bg-amber-400" />
                        Membres & cartes QR
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="h-1 w-1 rounded-full bg-amber-400" />
                        Présences & Sainte Cène
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="h-1 w-1 rounded-full bg-amber-400" />
                        Impression professionnelle A4
                    </li>
                </ul>
            </div>
        </div>

        <div
            class="flex flex-1 flex-col items-center justify-center px-6 py-10 lg:px-16"
        >
            <div class="w-full max-w-sm">
                <div class="mb-8 text-center lg:hidden">
                    <ChurchLogo size="lg" variant="auth" />
                </div>
                <div class="mb-6 space-y-1 text-center lg:text-left">
                    <h1 class="text-2xl font-semibold tracking-tight">
                        {{ title }}
                    </h1>
                    <p class="text-sm text-muted-foreground">
                        {{ description }}
                    </p>
                </div>
                <slot />
                <p class="mt-8 text-center text-xs text-muted-foreground">
                    <a
                        href="/membre/connexion"
                        class="font-medium text-amber-700 underline-offset-2 hover:underline dark:text-amber-400"
                    >
                        Accès espace membre
                    </a>
                </p>
            </div>
        </div>
    </div>
</template>
