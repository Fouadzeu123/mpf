<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { BookOpen, CreditCard, Wine, Smartphone, Download } from 'lucide-vue-next';
import MemberCardPreview from '@/components/church/MemberCardPreview.vue';
import MemberLayout from '@/layouts/MemberLayout.vue';

defineProps<{
    member: {
        member_code: string;
        first_name: string;
        last_name: string;
        department: string | null;
        phone: string | null;
        age?: number | null;
        gender?: string | null;
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
}>();
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
