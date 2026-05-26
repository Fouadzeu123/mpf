<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Membres', href: '/members' },
    { title: 'Nouveau', href: '/members/create' },
];

const form = useForm({
    first_name: '',
    last_name: '',
    age: '' as string | number,
    gender: '',
    phone: '',
    address_description: '',
    department: '',
    password: '',
    photo: null as File | null,
});

function submit() {
    form.post('/members', { forceFormData: true });
}
</script>

<template>
    <Head title="Nouveau membre" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <form
            class="mx-auto max-w-xl space-y-4 p-4"
            @submit.prevent="submit"
        >
            <h1 class="text-2xl font-bold">Nouveau membre</h1>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="text-sm font-medium">Prénom *</label>
                    <input v-model="form.first_name" required class="mt-1 w-full rounded-lg border px-3 py-2" />
                    <InputError :message="form.errors.first_name" />
                </div>
                <div>
                    <label class="text-sm font-medium">Nom *</label>
                    <input v-model="form.last_name" required class="mt-1 w-full rounded-lg border px-3 py-2" />
                    <InputError :message="form.errors.last_name" />
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="text-sm font-medium">Âge</label>
                    <input v-model="form.age" type="number" class="mt-1 w-full rounded-lg border px-3 py-2" />
                </div>
                <div>
                    <label class="text-sm font-medium">Sexe</label>
                    <select v-model="form.gender" class="mt-1 w-full rounded-lg border px-3 py-2">
                        <option value="">—</option>
                        <option value="M">M</option>
                        <option value="F">F</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium">Téléphone</label>
                <input v-model="form.phone" class="mt-1 w-full rounded-lg border px-3 py-2" />
            </div>
            <div>
                <label class="text-sm font-medium">Adresse</label>
                <textarea v-model="form.address_description" rows="2" class="mt-1 w-full rounded-lg border px-3 py-2" />
            </div>
            <div>
                <label class="text-sm font-medium">Département</label>
                <input v-model="form.department" class="mt-1 w-full rounded-lg border px-3 py-2" />
            </div>
            <div>
                <label class="text-sm font-medium">Mot de passe membre (optionnel)</label>
                <input v-model="form.password" class="mt-1 w-full rounded-lg border px-3 py-2" placeholder="Auto: mpf-XXXXXX" />
            </div>
            <div>
                <label class="text-sm font-medium">Photo</label>
                <input type="file" accept="image/*" class="mt-1" @change="form.photo = ($event.target as HTMLInputElement).files?.[0] ?? null" />
            </div>
            <Button type="submit" :disabled="form.processing">Enregistrer</Button>
        </form>
    </AppLayout>
</template>
