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
                        class="font-medium text-primary underline-offset-2 hover:underline dark:text-blue-400"
                    >
                        Accès espace membre
                    </a>
                </p>
            </div>
        </div>
</template>
