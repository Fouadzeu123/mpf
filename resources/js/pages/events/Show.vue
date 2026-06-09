<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
    Calendar,
    MapPin,
    ArrowLeft,
    Coins,
    Users,
    CreditCard,
    Plus,
    Loader2,
} from 'lucide-vue-next';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

type EventDetail = {
    id: number;
    title: string;
    description: string | null;
    location: string | null;
    start_date: string;
    end_date: string | null;
    banner_url: string | null;
    total_contributions: number;
};

type ContributionItem = {
    id: number;
    member_name: string;
    member_code: string;
    amount: number;
    payment_method: string;
    recorder_name: string | null;
    date: string;
};

type MemberOption = {
    id: number;
    name: string;
    code: string;
};

const props = defineProps<{
    event: EventDetail;
    contributions: ContributionItem[];
    members: MemberOption[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Événements', href: '/admin-events' },
    { title: props.event.title, href: `/admin-events/${props.event.id}` },
];

const form = useForm({
    member_id: '',
    amount: '' as string | number,
});

// Member search logic for manual input
const searchQuery = ref('');
const showDropdown = ref(false);

function filterMembers() {
    if (!searchQuery.value) return props.members;
    const q = searchQuery.value.toLowerCase();
    return props.members.filter(
        (m) =>
            m.name.toLowerCase().includes(q) ||
            m.code.toLowerCase().includes(q)
    );
}

function selectMember(member: MemberOption) {
    form.member_id = String(member.id);
    searchQuery.value = `${member.name} (${member.code})`;
    showDropdown.value = false;
}

function submitContribution() {
    form.post(`/admin-events/${props.event.id}/contributions`, {
        onSuccess: () => {
            form.reset('amount');
            // Keep member_id & searchQuery selected for fast consecutive logs, or reset
            form.reset('member_id');
            searchQuery.value = '';
        },
    });
}
</script>

<template>
    <Head :title="event.title" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6">
            <!-- Navigation back -->
            <div class="flex items-center gap-3">
                <Link
                    href="/admin-events"
                    class="rounded-full border bg-card p-2 hover:bg-muted transition-colors duration-200"
                >
                    <ArrowLeft class="h-4 w-4" />
                </Link>
                <div>
                    <h1 class="text-xl font-black">{{ event.title }}</h1>
                    <p class="text-xs text-muted-foreground mt-0.5">Détails de l'événement et contributions</p>
                </div>
            </div>

            <!-- Main grid -->
            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Left Details & Contributions list -->
                <div class="space-y-6 lg:col-span-2">
                    <!-- Event Info Card -->
                    <div class="overflow-hidden rounded-2xl border bg-card shadow-sm">
                        <div v-if="event.banner_url" class="h-48 w-full relative">
                            <img :src="event.banner_url" class="h-full w-full object-cover" alt="Bannière" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent" />
                        </div>
                        <div class="p-6">
                            <div class="flex flex-wrap items-center gap-4 text-xs text-muted-foreground mb-4">
                                <span class="inline-flex items-center gap-1">
                                    <Calendar class="h-3.5 w-3.5" />
                                    Du {{ event.start_date }}
                                    <template v-if="event.end_date"> au {{ event.end_date }}</template>
                                </span>
                                <span v-if="event.location" class="inline-flex items-center gap-1">
                                    <MapPin class="h-3.5 w-3.5" />
                                    {{ event.location }}
                                </span>
                            </div>
                            <h2 class="text-lg font-black mb-3">Description</h2>
                            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-wrap">
                                {{ event.description || 'Aucune description fournie.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Statistics / Collected Widget -->
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border bg-card p-5 shadow-sm flex items-center gap-4">
                            <div class="p-3 bg-emerald-50 text-emerald-600 dark:bg-emerald-950/20 dark:text-emerald-400 rounded-full">
                                <Coins class="h-6 w-6" />
                            </div>
                            <div>
                                <p class="text-xs text-muted-foreground font-semibold">Total Collecté</p>
                                <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">
                                    {{ event.total_contributions }} FCFA
                                </p>
                            </div>
                        </div>
                        <div class="rounded-2xl border bg-card p-5 shadow-sm flex items-center gap-4">
                            <div class="p-3 bg-blue-50 text-blue-600 dark:bg-blue-950/20 dark:text-blue-400 rounded-full">
                                <Users class="h-6 w-6" />
                            </div>
                            <div>
                                <p class="text-xs text-muted-foreground font-semibold">Donateurs</p>
                                <p class="text-2xl font-black text-blue-600 dark:text-blue-400 mt-1">
                                    {{ contributions.length }} membre(s)
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Contributions Table list -->
                    <div class="rounded-2xl border bg-card shadow-sm overflow-hidden">
                        <div class="border-b px-5 py-4 flex items-center justify-between">
                            <h3 class="font-black text-base flex items-center gap-2">
                                <CreditCard class="h-5 w-5 text-primary" />
                                Liste des contributions validées
                            </h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-muted/50 border-b">
                                    <tr>
                                        <th class="px-5 py-3 text-left font-bold text-slate-500 dark:text-slate-400">Membre</th>
                                        <th class="px-5 py-3 text-left font-bold text-slate-500 dark:text-slate-400">Code</th>
                                        <th class="px-5 py-3 text-left font-bold text-slate-500 dark:text-slate-400">Montant</th>
                                        <th class="px-5 py-3 text-left font-bold text-slate-500 dark:text-slate-400">Méthode</th>
                                        <th class="px-5 py-3 text-left font-bold text-slate-500 dark:text-slate-400">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border">
                                    <tr
                                        v-for="contrib in contributions"
                                        :key="contrib.id"
                                        class="hover:bg-muted/20 transition-colors duration-200"
                                    >
                                        <td class="px-5 py-4 font-bold text-foreground">
                                            {{ contrib.member_name }}
                                        </td>
                                        <td class="px-5 py-4 font-mono text-xs text-slate-500">
                                            {{ contrib.member_code }}
                                        </td>
                                        <td class="px-5 py-4 font-bold text-emerald-600 dark:text-emerald-400">
                                            {{ contrib.amount }} FCFA
                                        </td>
                                        <td class="px-5 py-4 text-xs">
                                            <div>{{ contrib.payment_method }}</div>
                                            <div v-if="contrib.recorder_name" class="text-[10px] text-muted-foreground italic">
                                                Par {{ contrib.recorder_name }}
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 text-xs text-slate-500">
                                            {{ contrib.date }}
                                        </td>
                                    </tr>
                                    <tr v-if="contributions.length === 0">
                                        <td colspan="5" class="px-5 py-8 text-center text-muted-foreground italic">
                                            Aucune contribution n'a été enregistrée pour cet événement.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar: Manual Logging Form -->
                <div class="space-y-6">
                    <div class="rounded-2xl border bg-card p-5 shadow-sm space-y-4">
                        <div class="border-b pb-3">
                            <h3 class="font-black text-base flex items-center gap-2">
                                <Plus class="h-5 w-5 text-primary" />
                                Enregistrer contribution
                            </h3>
                            <p class="text-xs text-muted-foreground mt-0.5">Saisir un paiement manuel (espèces, etc.)</p>
                        </div>

                        <form @submit.prevent="submitContribution" class="space-y-4">
                            <!-- Searchable Member Select -->
                            <div class="relative">
                                <label class="text-xs font-bold text-slate-600 dark:text-slate-300">Sélectionner un membre *</label>
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    required
                                    placeholder="Rechercher nom ou code..."
                                    @focus="showDropdown = true"
                                    class="mt-1.5 w-full rounded-lg border px-3 py-2 text-sm bg-background focus:outline-none focus:ring-2 focus:ring-primary/50"
                                />
                                <input v-model="form.member_id" type="hidden" required />
                                
                                <!-- Dropdown list -->
                                <div
                                    v-if="showDropdown && filterMembers().length > 0"
                                    class="absolute left-0 right-0 z-30 mt-1 max-h-48 overflow-y-auto rounded-lg border bg-popover text-popover-foreground shadow-lg text-sm"
                                >
                                    <div
                                        v-for="member in filterMembers()"
                                        :key="member.id"
                                        class="cursor-pointer px-3 py-2 hover:bg-muted font-medium"
                                        @click="selectMember(member)"
                                    >
                                        {{ member.name }} <span class="font-mono text-xs text-muted-foreground">({{ member.code }})</span>
                                    </div>
                                </div>
                                <div
                                    v-if="showDropdown && searchQuery && filterMembers().length === 0"
                                    class="absolute left-0 right-0 z-30 mt-1 rounded-lg border bg-popover p-3 text-center text-xs text-muted-foreground shadow-lg"
                                >
                                    Aucun membre trouvé
                                </div>
                                <InputError :message="form.errors.member_id" />
                            </div>

                            <!-- Amount -->
                            <div>
                                <label class="text-xs font-bold text-slate-600 dark:text-slate-300">Montant (FCFA) *</label>
                                <input
                                    v-model="form.amount"
                                    type="number"
                                    required
                                    min="100"
                                    placeholder="ex: 5000"
                                    class="mt-1.5 w-full rounded-lg border px-3 py-2 text-sm bg-background focus:outline-none focus:ring-2 focus:ring-primary/50"
                                />
                                <InputError :message="form.errors.amount" />
                            </div>

                            <Button
                                type="submit"
                                class="w-full flex justify-center items-center gap-1.5"
                                :disabled="form.processing || !form.member_id"
                            >
                                <Loader2 v-if="form.processing" class="h-4 w-4 animate-spin" />
                                Enregistrer
                            </Button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
