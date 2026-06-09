<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    member: {
        id: number;
        first_name: string;
        last_name: string;
        photo_url?: string | null;
        birth_date: string | null;
        gender: string | null;
        profession: string | null;
        phone: string | null;
        address_description: string | null;
        department: string | null;
        status: string;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Membres', href: '/members' },
    { title: 'Modifier', href: `/members/${props.member.id}/edit` },
];

const form = useForm({
    first_name: props.member.first_name,
    last_name: props.member.last_name,
    birth_date: props.member.birth_date ?? '',
    gender: props.member.gender ?? '',
    profession: props.member.profession ?? '',
    phone: props.member.phone ?? '',
    address_description: props.member.address_description ?? '',
    department: props.member.department ?? '',
    status: props.member.status,
    password: '',
    photo: null as string | File | null,
    _method: 'put',
});

const availableDepartments = [
    'Apôtre',
    'Pasteur',
    'Évangéliste',
    'Anciens',
    'Diacres',
    'Chorale',
    'Nettoyage',
    'Protocole',
    'Communication',
    "Culte d'enfant",
    'Moniteurs'
];

const selectedDepartments = ref<string[]>(
    props.member.department
        ? props.member.department.split(',').map((d) => d.trim()).filter((d) => d !== '')
        : []
);

watch(selectedDepartments, (newVal) => {
    form.department = newVal.join(', ');
}, { deep: true });

const uploadingPhoto = ref(false);
const photoPreviewUrl = ref<string | null>(props.member.photo_url || null);

function handlePhotoChange(event: Event) {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (!file) return;

    uploadingPhoto.value = true;

    const reader = new FileReader();
    reader.onload = (e) => {
        const img = new Image();
        img.onload = () => {
            const canvas = document.createElement('canvas');
            const MAX_WIDTH = 800;
            const MAX_HEIGHT = 800;
            let width = img.width;
            let height = img.height;

            if (width > height) {
                if (width > MAX_WIDTH) {
                    height *= MAX_WIDTH / width;
                    width = MAX_WIDTH;
                }
            } else {
                if (height > MAX_HEIGHT) {
                    width *= MAX_HEIGHT / height;
                    height = MAX_HEIGHT;
                }
            }

            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');
            ctx?.drawImage(img, 0, 0, width, height);

            canvas.toBlob(
                (blob) => {
                    if (!blob) {
                        uploadingPhoto.value = false;
                        return;
                    }

                    const formData = new FormData();
                    formData.append('photo', blob, 'photo.jpg');

                    fetch('/members/upload-photo', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? '',
                        },
                        body: formData,
                    })
                        .then((res) => res.json())
                        .then((data) => {
                            if (data.path) {
                                form.photo = data.path;
                                photoPreviewUrl.value = data.url;
                            }
                        })
                        .catch((err) => {
                            console.error('Error uploading photo:', err);
                        })
                        .finally(() => {
                            uploadingPhoto.value = false;
                        });
                },
                'image/jpeg',
                0.8
            );
        };
        img.src = e.target?.result as string;
    };
    reader.readAsDataURL(file);
}

function submit() {
    form.post(`/members/${props.member.id}`, { forceFormData: true });
}
</script>

<template>
    <Head title="Modifier membre" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <form class="mx-auto max-w-xl space-y-4 p-4" @submit.prevent="submit">
            <h1 class="text-2xl font-bold">Modifier membre</h1>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="text-sm font-medium">Prénom</label>
                    <input
                        v-model="form.first_name"
                        required
                        class="mt-1 w-full rounded-lg border px-3 py-2"
                    />
                    <InputError :message="form.errors.first_name" />
                </div>
                <div>
                    <label class="text-sm font-medium">Nom</label>
                    <input
                        v-model="form.last_name"
                        required
                        class="mt-1 w-full rounded-lg border px-3 py-2"
                    />
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
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="text-sm font-medium">Téléphone</label>
                    <input
                        v-model="form.phone"
                        class="mt-1 w-full rounded-lg border px-3 py-2"
                    />
                </div>
                <div>
                    <label class="text-sm font-medium">Profession *</label>
                    <input
                        v-model="form.profession"
                        required
                        class="mt-1 w-full rounded-lg border px-3 py-2"
                        placeholder="Ex: Enseignant, Commerçant"
                    />
                    <InputError :message="form.errors.profession" />
                </div>
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
                <label class="text-sm font-medium">Nouveau mot de passe</label>
                <input
                    v-model="form.password"
                    class="mt-1 w-full rounded-lg border px-3 py-2"
                />
            </div>
            <div>
                <label class="text-sm font-medium">Photo</label>
                <div class="mt-1 flex items-center gap-4">
                    <input
                        type="file"
                        accept="image/*"
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        @change="handlePhotoChange"
                    />
                    <div v-if="uploadingPhoto" class="flex items-center gap-2 text-xs text-slate-500">
                        <span class="animate-spin h-4 w-4 border-2 border-blue-500 border-t-transparent rounded-full" />
                        Compression & envoi...
                    </div>
                </div>
                <div v-if="photoPreviewUrl" class="mt-3">
                    <img :src="photoPreviewUrl" class="h-24 w-20 rounded-lg object-cover border" alt="Aperçu" />
                </div>
                <InputError :message="form.errors.photo" />
            </div>
            <Button type="submit" :disabled="form.processing"
                >Mettre à jour</Button
            >
        </form>
    </AppLayout>
</template>
