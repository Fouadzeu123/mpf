<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    Plus,
    Edit,
    Trash2,
    Tv,
    FileVideo,
    Link2,
    Heart,
    Share2,
    MessageCircle,
    Loader2,
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

type VideoItem = {
    id: number;
    category: string;
    title: string;
    speaker: string;
    description: string | null;
    bible_reference: string | null;
    video_url: string | null;
    video_path: string | null;
    resolved_video_url: string;
    likes: number;
    shares: number;
    comments_count: number;
    date: string;
};

const props = defineProps<{
    videos: VideoItem[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Vidéos', href: '/admin-videos' },
];

const isOpen = ref(false);
const isEditMode = ref(false);
const editingVideoId = ref<number | null>(null);

const form = useForm({
    category: 'enseignement',
    title: '',
    speaker: '',
    description: '',
    bible_reference: '',
    source_type: 'url' as 'url' | 'file',
    video_url: '',
    video_file: null as File | null,
});

function openCreateModal() {
    isEditMode.value = false;
    editingVideoId.value = null;
    form.reset();
    form.clearErrors();
    isOpen.value = true;
}

function openEditModal(video: VideoItem) {
    isEditMode.value = true;
    editingVideoId.value = video.id;
    form.clearErrors();
    
    const sourceType = video.video_path ? 'file' : 'url';
    
    form.category = video.category;
    form.title = video.title;
    form.speaker = video.speaker;
    form.description = video.description ?? '';
    form.bible_reference = video.bible_reference ?? '';
    form.source_type = sourceType;
    form.video_url = video.video_url ?? '';
    form.video_file = null;
    
    isOpen.value = true;
}

function handleFileChange(event: Event) {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        form.video_file = target.files[0];
    } else {
        form.video_file = null;
    }
}

function submitForm() {
    if (isEditMode.value && editingVideoId.value) {
        // multipart/form-data doesn't support PUT natively in PHP, so we spoof it with POST and _method: 'PUT'
        form.post(`/admin-videos/${editingVideoId.value}`, {
            forceFormData: true,
            onSuccess: () => {
                isOpen.value = false;
                form.reset();
            },
        });
    } else {
        form.post('/admin-videos', {
            forceFormData: true,
            onSuccess: () => {
                isOpen.value = false;
                form.reset();
            },
        });
    }
}

const formWithMethod = {
    ...form,
    // Add method spoofing for Laravel updates
    post: (url: string, options?: any) => {
        if (isEditMode.value) {
            form.transform((data) => ({
                ...data,
                _method: 'PUT',
            })).post(url, options);
        } else {
            form.post(url, options);
        }
    }
};

function deleteVideo(id: number) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette vidéo ?')) {
        form.delete(`/admin-videos/${id}`);
    }
}

function getCategoryLabel(category: string) {
    switch (category) {
        case 'enseignement':
            return 'Enseignement';
        case 'priere':
            return 'Prière';
        case 'temoignage':
            return 'Témoignage';
        case 'louange':
            return 'Louange';
        case 'adoration':
            return 'Adoration';
        case 'autres':
            return 'Autre';
        default:
            return category;
    }
}

function getCategoryColor(category: string) {
    switch (category) {
        case 'enseignement':
            return 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300 border-blue-200 dark:border-blue-800/30';
        case 'priere':
            return 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 border-amber-200 dark:border-amber-800/30';
        case 'temoignage':
            return 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300 border-purple-200 dark:border-purple-800/30';
        case 'louange':
            return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/30';
        case 'adoration':
            return 'bg-pink-100 text-pink-800 dark:bg-pink-900/40 dark:text-pink-300 border-pink-200 dark:border-pink-800/30';
        case 'autres':
            return 'bg-slate-100 text-slate-800 dark:bg-slate-900 dark:text-slate-300 border-slate-200';
        default:
            return 'bg-slate-100 text-slate-800 dark:bg-slate-900 dark:text-slate-300';
    }
}
</script>

<template>
    <Head title="Gestion des Vidéos" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6">
            <!-- Header section -->
            <div class="flex flex-wrap items-center justify-between gap-4 border-b pb-5">
                <div>
                    <h1 class="text-2xl font-black tracking-tight flex items-center gap-2">
                        <Tv class="h-6 w-6 text-primary" />
                        Gestion des Vidéos
                    </h1>
                    <p class="text-sm text-muted-foreground mt-1">
                        Publiez des prédications, prières et témoignages vidéo sur le flux mobile des membres.
                    </p>
                </div>
                <Button @click="openCreateModal" class="shadow-sm">
                    <Plus class="mr-2 h-4 w-4" /> Nouvelle vidéo
                </Button>
            </div>

            <!-- Table or empty state -->
            <div v-if="videos.length === 0" class="flex flex-col items-center justify-center p-12 text-center rounded-2xl border border-dashed bg-card space-y-4">
                <div class="p-3 bg-muted rounded-full text-muted-foreground">
                    <Tv class="h-8 w-8" />
                </div>
                <div class="space-y-1">
                    <h3 class="text-lg font-bold">Aucune vidéo publiée</h3>
                    <p class="text-sm text-muted-foreground max-w-sm">
                        Commencez par ajouter votre première vidéo spirituelle pour alimenter le flux des membres.
                    </p>
                </div>
                <Button @click="openCreateModal" variant="outline">
                    <Plus class="mr-2 h-4 w-4" /> Ajouter une vidéo
                </Button>
            </div>

            <div v-else class="overflow-hidden rounded-xl border bg-card shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-muted/50 border-b">
                            <tr>
                                <th class="px-5 py-3 text-left font-bold text-slate-500 dark:text-slate-400">Vidéo</th>
                                <th class="px-5 py-3 text-left font-bold text-slate-500 dark:text-slate-400">Catégorie</th>
                                <th class="px-5 py-3 text-left font-bold text-slate-500 dark:text-slate-400">Source</th>
                                <th class="px-5 py-3 text-left font-bold text-slate-500 dark:text-slate-400">Date d'ajout</th>
                                <th class="px-5 py-3 text-left font-bold text-slate-500 dark:text-slate-400">Interactions</th>
                                <th class="px-5 py-3 text-right"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            <tr
                                v-for="video in videos"
                                :key="video.id"
                                class="hover:bg-muted/20 transition-colors duration-200"
                            >
                                <td class="px-5 py-4">
                                    <div class="font-bold text-foreground">{{ video.title }}</div>
                                    <div class="text-xs text-muted-foreground mt-0.5">Par {{ video.speaker }}</div>
                                    <div v-if="video.bible_reference" class="text-[11px] text-amber-500 dark:text-amber-400 font-semibold mt-1">
                                        Réf. Biblique : {{ video.bible_reference }}
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-semibold border"
                                        :class="getCategoryColor(video.category)"
                                    >
                                        {{ getCategoryLabel(video.category) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-1.5 text-xs">
                                        <template v-if="video.video_path">
                                            <FileVideo class="h-4 w-4 text-blue-500" />
                                            <span class="font-medium text-slate-600 dark:text-slate-300">Fichier local</span>
                                        </template>
                                        <template v-else>
                                            <Link2 class="h-4 w-4 text-amber-500" />
                                            <span class="font-medium text-slate-600 dark:text-slate-300">Lien URL</span>
                                        </template>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-slate-500 dark:text-slate-400 text-xs">
                                    {{ video.date }}
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
                                        <span class="flex items-center gap-1">
                                            <Heart class="h-3.5 w-3.5 text-red-500" /> {{ video.likes }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <Share2 class="h-3.5 w-3.5 text-green-500" /> {{ video.shares }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <MessageCircle class="h-3.5 w-3.5 text-blue-500" /> {{ video.comments_count }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            class="h-8 px-2 text-slate-600 dark:text-slate-300"
                                            @click="openEditModal(video)"
                                        >
                                            <Edit class="h-3.5 w-3.5" />
                                        </Button>
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            class="h-8 px-2 text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 border-red-100 hover:bg-red-50 dark:border-red-900/30 dark:hover:bg-red-900/20"
                                            @click="deleteVideo(video.id)"
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

            <!-- Modal for create and edit -->
            <Dialog v-model:open="isOpen">
                <DialogContent class="sm:max-w-lg">
                    <DialogHeader>
                        <DialogTitle class="text-lg font-black">
                            {{ isEditMode ? 'Modifier la vidéo' : 'Ajouter une nouvelle vidéo' }}
                        </DialogTitle>
                        <DialogDescription class="text-xs text-muted-foreground">
                            Remplissez les informations ci-dessous. Les vidéos téléversées seront lues directement dans le flux des membres.
                        </DialogDescription>
                    </DialogHeader>

                    <form @submit.prevent="submitForm" class="space-y-4 py-2">
                        <!-- Title -->
                        <div>
                            <label class="text-xs font-bold text-slate-600 dark:text-slate-300">Titre de la vidéo *</label>
                            <input
                                v-model="form.title"
                                type="text"
                                required
                                placeholder="ex: La Puissance de la prière"
                                class="mt-1.5 w-full rounded-lg border px-3 py-2 text-sm bg-background focus:outline-none focus:ring-2 focus:ring-primary/50"
                            />
                            <InputError :message="form.errors.title" />
                        </div>

                        <!-- Speaker & Category -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-600 dark:text-slate-300">Orateur *</label>
                                <input
                                    v-model="form.speaker"
                                    type="text"
                                    required
                                    placeholder="ex: Pasteur Moïse"
                                    class="mt-1.5 w-full rounded-lg border px-3 py-2 text-sm bg-background focus:outline-none focus:ring-2 focus:ring-primary/50"
                                />
                                <InputError :message="form.errors.speaker" />
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-600 dark:text-slate-300">Catégorie *</label>
                                <select
                                    v-model="form.category"
                                    required
                                    class="mt-1.5 w-full rounded-lg border px-3 py-2 text-sm bg-background focus:outline-none focus:ring-2 focus:ring-primary/50"
                                >
                                    <option value="enseignement">Enseignement</option>
                                    <option value="priere">Prière</option>
                                    <option value="temoignage">Témoignage</option>
                                    <option value="louange">Louange</option>
                                    <option value="adoration">Adoration</option>
                                    <option value="autres">Autre</option>
                                </select>
                                <InputError :message="form.errors.category" />
                            </div>
                        </div>

                        <!-- Description & Bible reference -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-600 dark:text-slate-300">Description</label>
                                <textarea
                                    v-model="form.description"
                                    rows="2"
                                    placeholder="Brève description de la prédication..."
                                    class="mt-1.5 w-full rounded-lg border px-3 py-2 text-sm bg-background focus:outline-none focus:ring-2 focus:ring-primary/50 resize-none"
                                />
                                <InputError :message="form.errors.description" />
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-xs font-bold text-slate-600 dark:text-slate-300">Référence Biblique</label>
                                <input
                                    v-model="form.bible_reference"
                                    type="text"
                                    placeholder="ex: Hébreux 11:1"
                                    class="mt-1.5 w-full rounded-lg border px-3 py-2 text-sm bg-background focus:outline-none focus:ring-2 focus:ring-primary/50"
                                />
                                <InputError :message="form.errors.bible_reference" />
                            </div>
                        </div>

                        <!-- Source type selection (URL or Upload) -->
                        <div class="border-t pt-4">
                            <label class="text-xs font-bold text-slate-600 dark:text-slate-300 block mb-2">Source de la vidéo</label>
                            <div class="flex gap-4 mb-3">
                                <label class="flex items-center gap-2 text-xs font-medium cursor-pointer">
                                    <input
                                        v-model="form.source_type"
                                        type="radio"
                                        value="url"
                                        class="h-4 w-4 text-primary focus:ring-primary"
                                    />
                                    <Link2 class="h-4 w-4 text-slate-400" />
                                    Lien URL externe
                                </label>
                                <label class="flex items-center gap-2 text-xs font-medium cursor-pointer">
                                    <input
                                        v-model="form.source_type"
                                        type="radio"
                                        value="file"
                                        class="h-4 w-4 text-primary focus:ring-primary"
                                    />
                                    <FileVideo class="h-4 w-4 text-slate-400" />
                                    Fichier vidéo (téléverser)
                                </label>
                            </div>

                            <!-- External URL Input -->
                            <div v-if="form.source_type === 'url'" class="space-y-1">
                                <input
                                    v-model="form.video_url"
                                    type="url"
                                    :required="form.source_type === 'url'"
                                    placeholder="https://exemple.com/video.mp4"
                                    class="w-full rounded-lg border px-3 py-2 text-sm bg-background focus:outline-none focus:ring-2 focus:ring-primary/50"
                                />
                                <p class="text-[10px] text-muted-foreground italic">
                                    Le lien doit pointer directement vers un fichier vidéo compatible (ex: se terminant par .mp4).
                                </p>
                                <InputError :message="form.errors.video_url" />
                            </div>

                            <!-- Local Upload Input -->
                            <div v-else class="space-y-1">
                                <div class="flex flex-col items-center justify-center border-2 border-dashed rounded-lg p-4 bg-muted/20 hover:bg-muted/40 cursor-pointer relative">
                                    <FileVideo class="h-8 w-8 text-muted-foreground mb-1.5" />
                                    <span class="text-xs font-bold text-slate-600 dark:text-slate-300">
                                        {{ form.video_file ? form.video_file.name : 'Sélectionner le fichier MP4' }}
                                    </span>
                                    <span class="text-[10px] text-muted-foreground mt-0.5">Taille max : 50 Mo</span>
                                    <input
                                        type="file"
                                        accept="video/mp4,video/webm,video/ogg,video/quicktime"
                                        :required="form.source_type === 'file' && !isEditMode"
                                        class="absolute inset-0 opacity-0 cursor-pointer"
                                        @change="handleFileChange"
                                    />
                                </div>
                                <p v-if="isEditMode && !form.video_file" class="text-[10px] text-muted-foreground italic mt-1">
                                    Laissez vide pour conserver le fichier vidéo existant.
                                </p>
                                <InputError :message="form.errors.video_file" />
                            </div>
                        </div>

                        <!-- Footer / Actions -->
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
                                {{ isEditMode ? 'Enregistrer les modifications' : 'Publier la vidéo' }}
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>
