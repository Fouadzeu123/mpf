<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    visitor: {
        id: number;
        first_name: string;
        last_name: string;
        phone: string | null;
        qr_code: string;
        visit_date: string;
    };
}>();

function convert() {
    if (confirm('Transformer ce visiteur en membre ?')) {
        router.post(`/visitors/${props.visitor.id}/convert`);
    }
}
</script>

<template>
    <Head title="Visiteur" />
    <AppLayout :breadcrumbs="[{ title: 'Visiteurs', href: '/visitors' }]">
        <div class="p-4">
            <h1 class="text-2xl font-bold">{{ visitor.first_name }} {{ visitor.last_name }}</h1>
            <p class="font-mono text-sm">{{ visitor.qr_code }}</p>
            <p class="mt-2 text-sm">Visite : {{ visitor.visit_date }}</p>
            <Button class="mt-6" @click="convert">Transformer en membre</Button>
        </div>
    </AppLayout>
</template>
