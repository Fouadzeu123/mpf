<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

defineProps<{
    preparations: {
        data: Array<{
            id: number;
            verse_reference: string;
            verse_text: string;
            whatsapp_sent: boolean;
            remote: boolean;
            created_at: string;
            member: { first_name: string; last_name: string };
        }>;
    };
}>();
</script>

<template>
    <Head title="Sainte Cène" />
    <AppLayout :breadcrumbs="[{ title: 'Sainte Cène', href: '/sainte-cene' }]">
        <div class="p-4">
            <h1 class="mb-4 text-2xl font-bold">Préparations Sainte Cène</h1>
            <div class="space-y-3">
                <div
                    v-for="p in preparations.data"
                    :key="p.id"
                    class="rounded-xl border bg-card p-4"
                >
                    <div class="flex justify-between">
                        <strong
                            >{{ p.member.first_name }}
                            {{ p.member.last_name }}</strong
                        >
                        <span class="text-xs text-muted-foreground">{{
                            p.created_at
                        }}</span>
                    </div>
                    <p class="mt-2 text-sm font-medium">
                        {{ p.verse_reference }}
                    </p>
                    <p class="text-sm text-muted-foreground">
                        {{ p.verse_text }}
                    </p>
                    <div class="mt-2 flex gap-2 text-xs">
                        <span v-if="p.whatsapp_sent" class="text-emerald-600"
                            >WhatsApp ✓</span
                        >
                        <span v-if="p.remote" class="text-amber-600"
                            >À distance</span
                        >
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
