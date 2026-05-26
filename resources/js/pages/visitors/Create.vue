<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';

const form = useForm({
    first_name: '',
    last_name: '',
    phone: '',
    address_description: '',
    visit_date: new Date().toISOString().slice(0, 10),
});
</script>

<template>
    <Head title="Nouveau visiteur" />
    <AppLayout :breadcrumbs="[{ title: 'Visiteurs', href: '/visitors' }, { title: 'Nouveau', href: '#' }]">
        <form class="mx-auto max-w-md space-y-4 p-4" @submit.prevent="form.post('/visitors')">
            <h1 class="text-2xl font-bold">Nouveau visiteur</h1>
            <input v-model="form.first_name" required placeholder="Prénom" class="w-full rounded-lg border px-3 py-2" />
            <input v-model="form.last_name" required placeholder="Nom" class="w-full rounded-lg border px-3 py-2" />
            <input v-model="form.phone" placeholder="Téléphone" class="w-full rounded-lg border px-3 py-2" />
            <input v-model="form.visit_date" type="date" class="w-full rounded-lg border px-3 py-2" />
            <Button type="submit" :disabled="form.processing">Enregistrer</Button>
        </form>
    </AppLayout>
</template>
