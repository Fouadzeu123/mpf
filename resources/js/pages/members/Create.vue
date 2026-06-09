<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
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
    birth_date: '',
    gender: '',
    phone: '',
    address_description: '',
    department: '',
    password: '',
    photo: null as File | null,
});

const availableDepartments = [
    'Anciens',
    'Évangélisation',
    'Nettoyage',
    'Chorale',
    'Pasteur',
    'Protocole',
    'Communication',
    "Culte d'enfant",
    'Diacres',
    'Moniteurs'
];

const selectedDepartments = ref<string[]>([]);

watch(selectedDepartments, (newVal) => {
    form.department = newVal.join(', ');
}, { deep: true });

function submit() {
    form.post('/members', { forceFormData: true });
}
</script>

<template>
    <Head title="Nouveau membre" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <form class="mx-auto max-w-xl space-y-4 p-4" @submit.prevent="submit">
            <h1 class="text-2xl font-bold">Nouveau membre</h1>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="text-sm font-medium">Prénom *</label>
                    <input
                        v-model="form.first_name"
                        required
                        class="mt-1 w-full rounded-lg border px-3 py-2"
                    />
                    <InputError :message="form.errors.first_name" />
                </div>
                <div>
                    <label class="text-sm font-medium">Nom *</label>
                    <input
                        v-model="form.last_name"
                        required
                        class="mt-1 w-full rounded-lg border px-3 py-2"
                    />
                    <InputError :message="form.errors.last_name" />
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="text-sm font-medium">Date de naissance</label>
                    <input
                        v-model="form.birth_date"
                        type="date"
                        class="mt-1 w-full rounded-lg border px-3 py-2"
                    />
                    <InputError :message="form.errors.birth_date" />
                </div>
                <div>
                    <label class="text-sm font-medium">Sexe</label>
                    <select
                        v-model="form.gender"
                        class="mt-1 w-full rounded-lg border px-3 py-2"
                    >
                        <option value="">—</option>
                        <option value="M">M</option>
                        <option value="F">F</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium">Téléphone</label>
                <input
                    v-model="form.phone"
                    class="mt-1 w-full rounded-lg border px-3 py-2"
                />
            </div>
            <div>
                <label class="text-sm font-medium">Adresse</label>
                <textarea
                    v-model="form.address_description"
                    rows="2"
                    class="mt-1 w-full rounded-lg border px-3 py-2"
                />
            </div>
            <div>
                <label class="text-sm font-medium block mb-2">Départements</label>
                <div class="grid grid-cols-2 gap-2 rounded-lg border p-3 bg-card dark:border-slate-800">
                    <label
                        v-for="dept in availableDepartments"
                        :key="dept"
                        class="flex items-center gap-2 text-sm font-medium cursor-pointer text-slate-700 dark:text-slate-300"
                    >
                        <input
                            type="checkbox"
                            :value="dept"
                            v-model="selectedDepartments"
                            class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                        />
                        {{ dept }}
                    </label>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium"
                    >Mot de passe membre (optionnel)</label
                >
                <input
                    v-model="form.password"
                    class="mt-1 w-full rounded-lg border px-3 py-2"
                    placeholder="Auto: mpf-XXXXXX"
                />
            </div>
            <div>
                <label class="text-sm font-medium">Photo</label>
                <input
                    type="file"
                    accept="image/*"
                    class="mt-1"
                    @change="
                        form.photo =
                            ($event.target as HTMLInputElement).files?.[0] ??
                            null
                    "
                />
            </div>
            <Button type="submit" :disabled="form.processing"
                >Enregistrer</Button
            >
        </form>
    </AppLayout>
</template>
