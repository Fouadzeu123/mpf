<script setup lang="ts">
import type { Component } from 'vue';

defineProps<{
    title: string;
    value: number | string;
    icon?: Component;
    accent?: string;
}>();
</script>

<template>
    <div
        class="rounded-xl border border-sidebar-border/30 bg-card p-5 shadow-sm transition hover:shadow-md"
    >
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-muted-foreground">{{ title }}</p>
                <p class="mt-1 text-3xl font-bold text-foreground">{{ value }}</p>
            </div>
            <div
                v-if="icon"
                class="rounded-lg bg-primary/10 p-2.5 text-primary"
            >
                <component :is="icon" class="h-5 w-5" />
            </div>
        </div>
    </div>
</template>
