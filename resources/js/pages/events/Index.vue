<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    Plus,
    Edit,
    Trash2,
    Calendar,
    MapPin,
    Eye,
    Loader2,
    ImageIcon,
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
    DialogFooter,
} from '@/components/ui/dialog';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

type EventItem = {
    id: number;
    title: string;
    description: string | null;
    location: string | null;
    start_date: string;
    end_date: string | null;
    banner_url: string | null;
    total_contributions: number;
};

const props = defineProps<{
    events: EventItem[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Événements', href: '/admin-events' },
];

const isOpen = ref(false);
const isEditMode = ref(false);
const editingEventId = ref<number | null>(null);

const form = useForm({
    title: '',
    description: '',
    location: '',
    start_date: '',
    end_date: '',
    banner_file: null as File | null,
});

function openCreateModal() {
    isEditMode.value = false;
    editingEventId.value = null;
    form.reset();
    form.clearErrors();
    isOpen.value = true;
}

function openEditModal(event: EventItem) {
    isEditMode.value = true;
    editingEventId.value = event.id;
    form.clearErrors();
    
    // Format dates for datetime-local input (YYYY-MM-DDTHH:MM)
    const formatForInput = (dateStr: string) => {
        // Input: "DD/MM/YYYY HH:MM" or "YYYY-MM-DD HH:MM"
        // Let's replace space with T and format correctly
        const d = new Date(dateStr.replace(' ', 'T'));
        if (isNaN(d.getTime())) return '';
        const pad = (n: number) => String(n).padStart(2, '0');
        return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
    };

    form.title = event.title;
    form.description = event.description ?? '';
    form.location = event.location ?? '';
    form.start_date = formatForInput(event.start_date);
    form.end_date = event.end_date ? formatForInput(event.end_date) : '';
    form.banner_file = null;
    
    isOpen.value = true;
}

function handleFileChange(event: Event) {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        form.banner_file = target.files[0];
    } else {
        form.banner_file = null;
    }
}

function submitForm() {
    if (isEditMode.value && editingEventId.value) {
        form.transform((data) => ({
            ...data,
            _method: 'PUT',
        })).post(`/admin-events/${editingEventId.value}`, {
            forceFormData: true,
            onSuccess: () => {
                isOpen.value = false;
                form.reset();
            },
        });
    } else {
        form.post('/admin-events', {
            forceFormData: true,
            onSuccess: () => {
                isOpen.value = false;
                form.reset();
            },
        });
    }
}

function deleteEvent(id: number) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')) {
        form.delete(`/admin-events/${id}`);
    }
}
</script>

<template>
    <Head title="Gestion des Événements" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6">
            <!-- Header section -->
            <div class="flex flex-wrap items-center justify-between gap-4 border-b pb-5">
                <div>
                    <h1 class="text-2xl font-black tracking-tight flex items-center gap-2">
                        <Calendar class="h-6 w-6 text-primary" />
                        Gestion des Événements
                    </h1>
                    <p class="text-sm text-muted-foreground mt-1">
                        Créez des cultes spéciaux, des séminaires, et récoltez des contributions financières en ligne ou en espèces.
                    </p>
                </div>
                <Button @click="openCreateModal" class="shadow-sm">
                    <Plus class="mr-2 h-4 w-4" /> Nouvel événement
                </Button>
            </div>

            <!-- Table or empty state -->
            <div v-if="events.length === 0" class="flex flex-col items-center justify-center p-12 text-center rounded-2xl border border-dashed bg-card space-y-4">
                <div class="p-3 bg-muted rounded-full text-muted-foreground">
                    <Calendar class="h-8 w-8" />
                </div>
                <div class="space-y-1">
                    <h3 class="text-lg font-bold">Aucun événement créé</h3>
                    <p class="text-sm text-muted-foreground max-w-sm">
                        Commencez par ajouter votre premier événement pour l'afficher sur le portail des membres.
                    </p>
                </div>
                <Button @click="openCreateModal" variant="outline">
                    <Plus class="mr-2 h-4 w-4" /> Créer un événement
                </Button>
            </div>

            <div v-else class="overflow-hidden rounded-xl border bg-card shadow-sm">
                <!-- Wrapper for responsive horizontal scroll on mobile -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-muted/50 border-b">
                            <tr>
                                <th class="px-5 py-3 text-left font-bold text-slate-500 dark:text-slate-400">Bannière</th>
                                <th class="px-5 py-3 text-left font-bold text-slate-500 dark:text-slate-400">Événement</th>
                                <th class="px-5 py-3 text-left font-bold text-slate-500 dark:text-slate-400">Dates</th>
                                <th class="px-5 py-3 text-left font-bold text-slate-500 dark:text-slate-400">Lieu</th>
                                <th class="px-5 py-3 text-left font-bold text-slate-500 dark:text-slate-400">Collecte</th>
                                <th class="px-5 py-3 text-right"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            <tr
                                v-for="event in events"
                                :key="event.id"
                                class="hover:bg-muted/20 transition-colors duration-200"
                            >
                                <td class="px-5 py-4">
                                    <img
                                        v-if="event.banner_url"
                                        :src="event.banner_url"
                                        class="h-10 w-16 object-cover rounded-lg border"
                                        alt="Bannière"
                                    />
                                    <div
                                        v-else
                                        class="h-10 w-16 bg-muted rounded-lg border flex items-center justify-center text-muted-foreground"
                                    >
                                        <ImageIcon class="h-4 w-4" />
                                    </div>
                                </td>
                                <td class="px-5 py-4 font-bold text-foreground">
                                    {{ event.title }}
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-600 dark:text-slate-300">
                                    <div>Début: {{ event.start_date }}</div>
                                    <div v-if="event.end_date">Fin: {{ event.end_date }}</div>
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-600 dark:text-slate-300">
                                    <span v-if="event.location" class="inline-flex items-center gap-1">
                                        <MapPin class="h-3 w-3 text-muted-foreground" /> {{ event.location }}
                                    </span>
                                    <span v-else class="text-muted-foreground italic">-</span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400 border border-emerald-200/40">
                                        {{ event.total_contributions }} FCFA
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <Link :href="`/admin-events/${event.id}`">
                                            <Button
                                                variant="outline"
                                                size="sm"
                                                class="h-8 px-2 text-slate-600 dark:text-slate-300"
                                            >
                                                <Eye class="h-3.5 w-3.5 mr-1" /> Détails
                                            </Button>
                                        </Link>
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            class="h-8 px-2 text-slate-600 dark:text-slate-300"
                                            @click="openEditModal(event)"
                                        >
                                            <Edit class="h-3.5 w-3.5" />
                                        </Button>
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            class="h-8 px-2 text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 border-red-100 hover:bg-red-50 dark:border-red-900/30 dark:hover:bg-red-900/20"
                                            @click="deleteEvent(event.id)"
                                        >
                                            <Trash2 class="h-3.5 w-3.5" />
                                        </Button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal for Event Create & Edit -->
            <Dialog v-model:open="isOpen">
                <DialogContent class="sm:max-w-lg">
                    <DialogHeader>
                        <DialogTitle class="text-lg font-black">
                            {{ isEditMode ? 'Modifier l\'événement' : 'Créer un nouvel événement' }}
                        </DialogTitle>
                        <DialogDescription class="text-xs text-muted-foreground">
                            Entrez les détails de l'événement ci-dessous.
                        </DialogDescription>
                    </DialogHeader>

                    <form @submit.prevent="submitForm" class="space-y-4 py-2">
                        <!-- Title -->
                        <div>
                            <label class="text-xs font-bold text-slate-600 dark:text-slate-300">Titre de l'événement *</label>
                            <input
                                v-model="form.title"
                                type="text"
                                required
                                placeholder="ex: Séminaire de consécration"
                                class="mt-1.5 w-full rounded-lg border px-3 py-2 text-sm bg-background focus:outline-none focus:ring-2 focus:ring-primary/50"
                            />
                            <InputError :message="form.errors.title" />
                        </div>

                        <!-- Location -->
                        <div>
                            <label class="text-xs font-bold text-slate-600 dark:text-slate-300">Lieu</label>
                            <input
                                v-model="form.location"
                                type="text"
                                placeholder="ex: Temple principal, Douala"
                                class="mt-1.5 w-full rounded-lg border px-3 py-2 text-sm bg-background focus:outline-none focus:ring-2 focus:ring-primary/50"
                            />
                            <InputError :message="form.errors.location" />
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-600 dark:text-slate-300">Date de début *</label>
                                <input
                                    v-model="form.start_date"
                                    type="datetime-local"
                                    required
                                    class="mt-1.5 w-full rounded-lg border px-3 py-2 text-sm bg-background focus:outline-none focus:ring-2 focus:ring-primary/50"
                                />
                                <InputError :message="form.errors.start_date" />
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-600 dark:text-slate-300">Date de fin</label>
                                <input
                                    v-model="form.end_date"
                                    type="datetime-local"
                                    class="mt-1.5 w-full rounded-lg border px-3 py-2 text-sm bg-background focus:outline-none focus:ring-2 focus:ring-primary/50"
                                />
                                <InputError :message="form.errors.end_date" />
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="text-xs font-bold text-slate-600 dark:text-slate-300">Description</label>
                            <textarea
                                v-model="form.description"
                                rows="3"
                                placeholder="Objectif de l'événement, thématique de prière..."
                                class="mt-1.5 w-full rounded-lg border px-3 py-2 text-sm bg-background focus:outline-none focus:ring-2 focus:ring-primary/50 resize-none"
                            />
                            <InputError :message="form.errors.description" />
                        </div>

                        <!-- Banner Image Upload -->
                        <div>
                            <label class="text-xs font-bold text-slate-600 dark:text-slate-300 block mb-1.5">Bannière / Affiche (Optionnelle)</label>
                            <input
                                type="file"
                                accept="image/*"
                                class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer"
                                @change="handleFileChange"
                            />
                            <p v-if="isEditMode" class="text-[10px] text-muted-foreground italic mt-1">
                                Laissez vide pour conserver l'image existante.
                            </p>
                            <InputError :message="form.errors.banner_file" />
                        </div>

                        <!-- Footer Actions -->
                        <DialogFooter class="border-t pt-4 mt-6 gap-2 sm:gap-0">
                            <Button
                                type="button"
                                variant="outline"
                                :disabled="form.processing"
                                @click="isOpen = false"
                            >
                                Annuler
                            </Button>
                            <Button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex items-center"
                            >
                                <Loader2 v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                                {{ isEditMode ? 'Enregistrer les modifications' : 'Créer l\'événement' }}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>
