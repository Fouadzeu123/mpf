<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    BookOpen,
    CreditCard,
    Wine,
    Smartphone,
    Download,
    Calendar,
    MapPin,
    Coins,
    Loader2,
    X,
} from 'lucide-vue-next';
import MemberCardPreview from '@/components/church/MemberCardPreview.vue';
import MemberLayout from '@/layouts/MemberLayout.vue';

const props = defineProps<{
    member: {
        member_code: string;
        first_name: string;
        last_name: string;
        birth_date: string | null;
        gender: string | null;
        phone: string | null;
        department: string | null;
        verse_history: Array<{
            verse_reference: string;
            verse_text: string;
            created_at: string;
        }>;
        communion_preparations: Array<{
            verse_reference: string;
            created_at: string;
        }>;
    };
    qrDataUri: string;
    preparedToday: boolean;
    communionFee: number;
    programs: string[];
    upcomingEvents: Array<{
        id: number;
        title: string;
        description: string | null;
        location: string | null;
        start_date: string;
        end_date: string | null;
        banner_url: string | null;
    }>;
    pastEvents: Array<{
        id: number;
        title: string;
        description: string | null;
        location: string | null;
        start_date: string;
        end_date: string | null;
        banner_url: string | null;
    }>;
    contributions: Array<{
        id: number;
        event_title: string;
        amount: number;
        payment_method: string;
        date: string;
    }>;
    totalContributions: number;
}>();

const activeEventsTab = ref<'upcoming' | 'past'>('upcoming');
const selectedEvent = ref<{ id: number; title: string } | null>(null);
const showContributionModal = ref(false);

const contributionForm = useForm({
    amount: '' as string | number,
});

function openContributionModal(event: { id: number; title: string }) {
    selectedEvent.value = event;
    contributionForm.amount = '';
    showContributionModal.value = true;
}

function submitContribution() {
    if (!selectedEvent.value) return;
    contributionForm.post(`/membre/evenements/${selectedEvent.value.id}/contribuer`, {
        onSuccess: () => {
            showContributionModal.value = false;
            selectedEvent.value = null;
        },
    });
}
</script>

<template>
    <MemberLayout title="Mon espace">
        <div class="space-y-6">
            <div
                class="rounded-[2rem] border border-blue-500/20 bg-gradient-to-br from-blue-950 via-indigo-950 to-slate-900 p-6 text-white shadow-xl"
            >
                <p
                    class="text-xs font-semibold tracking-[0.25em] text-blue-300 uppercase"
                >
                    Espace personnel
                </p>
                <h1 class="mt-2 text-2xl font-black">
                    Bonjour, {{ member.first_name }}
                </h1>
                <p class="mt-1 font-mono text-sm text-blue-300/90">
                    {{ member.member_code }}
                </p>
                <p v-if="member.department" class="mt-2 text-sm text-slate-300">
                    {{ member.department }}
                </p>
            </div>

            <MemberCardPreview
                :member="member"
                :qr-data-uri="qrDataUri"
                :programs="programs"
            />

            <div class="rounded-2xl border bg-card p-5 shadow-sm">
                <h2
                    class="flex items-center gap-2 font-semibold text-foreground"
                >
                    <Wine class="h-5 w-5 text-blue-500" />
                    Sainte Cène
                </h2>
                <p
                    v-if="preparedToday"
                    class="mt-3 rounded-lg bg-emerald-500/10 px-3 py-2 text-sm text-emerald-700 dark:text-emerald-400"
                >
                    Préparation enregistrée aujourd'hui
                </p>
                <template v-else>
                    <p class="mt-2 text-sm text-muted-foreground">
                        Préparation à distance - {{ communionFee }} FCFA
                    </p>
                    <Link
                        href="/membre/paiement"
                        class="mt-4 flex w-full items-center justify-center gap-2 rounded-2xl bg-primary py-3 font-bold text-primary-foreground shadow-lg shadow-primary/20 transition hover:bg-primary/90 active:scale-[0.98]"
                    >
                        <CreditCard class="h-4 w-4" />
                        Faire ma préparation
                    </Link>
                </template>
            </div>

            <!-- Events widget -->
            <div class="rounded-2xl border bg-card p-5 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b pb-3">
                    <h2 class="flex items-center gap-2 font-semibold text-foreground">
                        <Calendar class="h-5 w-5 text-blue-500" />
                        Événements
                    </h2>
                    
                    <!-- Tabs -->
                    <div class="flex gap-2 bg-slate-100 dark:bg-slate-900 rounded-full p-0.5 text-xs font-semibold">
                        <button
                            @click="activeEventsTab = 'upcoming'"
                            class="px-3 py-1 rounded-full transition"
                            :class="activeEventsTab === 'upcoming' ? 'bg-primary text-white shadow-sm' : 'text-slate-600 dark:text-slate-400'"
                        >
                            À venir
                        </button>
                        <button
                            @click="activeEventsTab = 'past'"
                            class="px-3 py-1 rounded-full transition"
                            :class="activeEventsTab === 'past' ? 'bg-primary text-white shadow-sm' : 'text-slate-600 dark:text-slate-400'"
                        >
                            Passés
                        </button>
                    </div>
                </div>

                <div class="space-y-4">
                    <template v-if="activeEventsTab === 'upcoming'">
                        <div
                            v-for="event in upcomingEvents"
                            :key="event.id"
                            class="rounded-xl border overflow-hidden bg-slate-50/50 dark:bg-slate-900/10 shadow-sm"
                        >
                            <img
                                v-if="event.banner_url"
                                :src="event.banner_url"
                                class="h-28 w-full object-cover"
                                alt=""
                            />
                            <div class="p-4 space-y-2">
                                <h3 class="font-bold text-sm text-foreground">{{ event.title }}</h3>
                                <p class="text-xs text-muted-foreground line-clamp-2 leading-relaxed">{{ event.description }}</p>
                                <div class="flex flex-col gap-1 text-[11px] text-slate-500 font-medium pt-1">
                                    <span class="flex items-center gap-1"><Calendar class="h-3 w-3" /> {{ event.start_date }}</span>
                                    <span v-if="event.location" class="flex items-center gap-1"><MapPin class="h-3 w-3" /> {{ event.location }}</span>
                                </div>
                                <Button
                                    size="sm"
                                    class="w-full mt-2 font-bold shadow-md shadow-primary/10"
                                    @click="openContributionModal(event)"
                                >
                                    <Coins class="mr-1.5 h-3.5 w-3.5" /> Contribuer
                                </Button>
                            </div>
                        </div>
                        <p v-if="!upcomingEvents.length" class="text-xs text-muted-foreground italic text-center py-4">Aucun événement à venir.</p>
                    </template>

                    <template v-else>
                        <div
                            v-for="event in pastEvents"
                            :key="event.id"
                            class="rounded-xl border overflow-hidden bg-slate-50/50 dark:bg-slate-900/10 opacity-80"
                        >
                            <img
                                v-if="event.banner_url"
                                :src="event.banner_url"
                                class="h-24 w-full object-cover grayscale"
                                alt=""
                            />
                            <div class="p-4 space-y-1.5">
                                <h3 class="font-bold text-sm text-foreground">{{ event.title }}</h3>
                                <div class="flex flex-col gap-1 text-[11px] text-slate-500 pt-1">
                                    <span>Date : {{ event.start_date }}</span>
                                    <span v-if="event.location">Lieu : {{ event.location }}</span>
                                </div>
                            </div>
                        </div>
                        <p v-if="!pastEvents.length" class="text-xs text-muted-foreground italic text-center py-4">Aucun événement passé.</p>
                    </template>
                </div>
            </div>

            <!-- Contributions Widget -->
            <div class="rounded-2xl border bg-card p-5 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b pb-3">
                    <h2 class="flex items-center gap-2 font-semibold text-foreground">
                        <Coins class="h-5 w-5 text-blue-500" />
                        Mes Contributions
                    </h2>
                    <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400 border border-emerald-200/40">
                        Total : {{ totalContributions }} FCFA
                    </span>
                </div>

                <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                    <div
                        v-for="contrib in contributions"
                        :key="contrib.id"
                        class="rounded-xl border p-3 flex justify-between items-center text-xs bg-slate-50/50 dark:bg-slate-900/30"
                    >
                        <div class="min-w-0 flex-1 pr-2">
                            <p class="font-bold truncate text-foreground">{{ contrib.event_title }}</p>
                            <p class="text-[10px] text-muted-foreground mt-0.5">
                                {{ contrib.date }} · {{ contrib.payment_method }}
                            </p>
                        </div>
                        <span class="font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap shrink-0">
                            + {{ contrib.amount }} FCFA
                        </span>
                    </div>
                    
                    <p v-if="!contributions.length" class="text-xs text-muted-foreground italic text-center py-4">
                        Aucune contribution enregistrée pour le moment.
                    </p>
                </div>
            </div>

            <!-- Modal for mobile money contribution entry -->
            <div
                v-if="showContributionModal && selectedEvent"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
                @click.self="showContributionModal = false"
            >
                <div class="w-full max-w-sm rounded-[2rem] border border-blue-900/30 bg-card p-6 shadow-2xl space-y-4">
                    <div class="flex items-center justify-between border-b pb-2">
                        <h3 class="font-black text-sm text-foreground flex items-center gap-2">
                            <Coins class="h-5 w-5 text-amber-500 animate-bounce" />
                            Faire une contribution
                        </h3>
                        <button
                            @click="showContributionModal = false"
                            class="rounded-full hover:bg-muted p-1 transition"
                        >
                            <X class="h-4 w-4 text-muted-foreground" />
                        </button>
                    </div>

                    <div class="space-y-1 text-center">
                        <p class="text-[10px] font-black uppercase text-amber-500 tracking-wider">Événement ciblé</p>
                        <h4 class="font-bold text-sm text-foreground line-clamp-1">{{ selectedEvent.title }}</h4>
                    </div>

                    <form @submit.prevent="submitContribution" class="space-y-4 pt-2">
                        <div>
                            <label class="text-xs font-bold text-slate-600 dark:text-slate-300 font-medium">Montant de votre don (FCFA) *</label>
                            <input
                                v-model="contributionForm.amount"
                                type="number"
                                required
                                min="100"
                                placeholder="ex: 2000"
                                class="mt-1.5 w-full rounded-lg border px-3 py-2 text-sm bg-background focus:outline-none focus:ring-2 focus:ring-primary/50 text-foreground"
                            />
                            <p class="text-[9px] text-muted-foreground italic mt-1 leading-normal">
                                Vous serez redirigé vers NotchPay pour compléter le paiement par Mobile Money.
                            </p>
                            <InputError :message="contributionForm.errors.amount" />
                        </div>

                        <Button
                            type="submit"
                            class="w-full flex justify-center items-center gap-1.5 font-bold py-3.5 rounded-xl shadow-lg shadow-primary/20"
                            :disabled="contributionForm.processing || !contributionForm.amount"
                        >
                            <Loader2 v-if="contributionForm.processing" class="h-4 w-4 animate-spin" />
                            Payer par Mobile Money
                        </Button>
                    </form>
                </div>
            </div>

            <!-- Mobile App Download Widget -->
            <div class="rounded-2xl border bg-card p-5 shadow-sm">
                <h2
                    class="flex items-center gap-2 font-semibold text-foreground"
                >
                    <Smartphone class="h-5 w-5 text-blue-500" />
                    Application mobile
                </h2>
                <p class="mt-2 text-sm text-muted-foreground">
                    Installez l'application mobile pour un accès rapide à votre carte membre et une navigation ultra-rapide.
                </p>
                <a
                    href="/mpf.apk"
                    download
                    class="mt-4 flex w-full items-center justify-center gap-2 rounded-2xl bg-primary py-3 font-bold text-primary-foreground shadow-lg shadow-primary/20 transition hover:bg-primary/90 active:scale-[0.98]"
                >
                    <Download class="h-4 w-4" />
                    Télécharger l'APK Android
                </a>
            </div>

            <div class="rounded-2xl border bg-card p-5 shadow-sm">
                <h2
                    class="flex items-center gap-2 font-semibold text-foreground"
                >
                    <BookOpen class="h-5 w-5 text-blue-500" />
                    Mes versets
                </h2>
                <ul class="mt-4 space-y-3">
                    <li
                        v-for="v in member.verse_history"
                        :key="v.created_at + v.verse_reference"
                        class="border-l-2 border-blue-500/60 pl-3 text-sm"
                    >
                        <strong class="text-foreground">{{
                            v.verse_reference
                        }}</strong>
                        <p class="text-muted-foreground">{{ v.verse_text }}</p>
                    </li>
                    <li
                        v-if="!member.verse_history?.length"
                        class="text-sm text-muted-foreground"
                    >
                        Aucun verset pour le moment.
                    </li>
                </ul>
            </div>
        </div>
    </MemberLayout>
</template>
