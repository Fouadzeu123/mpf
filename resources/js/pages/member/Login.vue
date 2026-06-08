<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { IdCard, KeyRound, LogIn } from 'lucide-vue-next';
import ChurchLogo from '@/components/ChurchLogo.vue';
import InputError from '@/components/InputError.vue';
import ThemeToggle from '@/components/ThemeToggle.vue';

const form = useForm({
    member_code: '',
    password: '',
    remember: false,
});

function submit() {
    form.post('/membre/connexion');
}
</script>

<template>
    <Head title="Espace membre" />
    <div
        class="relative flex min-h-screen flex-col items-center justify-center bg-gradient-to-br from-blue-950 via-indigo-950 to-slate-900 p-4"
    >
        <div class="absolute top-4 right-4">
            <ThemeToggle />
        </div>

        <div
            class="w-full max-w-md overflow-hidden rounded-[2rem] border border-white/10 bg-white shadow-2xl dark:bg-slate-950"
        >
            <div class="px-6 py-8 text-center">
                <ChurchLogo size="lg" variant="auth" class="mx-auto" />
                <h1
                    class="mt-5 text-2xl font-black text-slate-950 dark:text-white"
                >
                    Espace membre
                </h1>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    Connectez-vous avec votre numéro membre et votre mot de
                    passe.
                </p>
            </div>

            <form class="space-y-5 px-6 pb-6" @submit.prevent="submit">
                <div>
                    <label
                        class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-800 dark:text-slate-100"
                    >
                        <IdCard class="h-4 w-4 text-blue-500" />
                        Numéro membre
                    </label>
                    <input
                        v-model="form.member_code"
                        type="text"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 uppercase focus-visible:ring-2 focus-visible:ring-primary/40 focus-visible:outline-none dark:border-slate-800 dark:bg-slate-900"
                        placeholder="MEM-000001"
                    />
                    <InputError :message="form.errors.member_code" />
                </div>
                <div>
                    <label
                        class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-800 dark:text-slate-100"
                    >
                        <KeyRound class="h-4 w-4 text-blue-500" />
                        Mot de passe
                    </label>
                    <input
                        v-model="form.password"
                        type="password"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus-visible:ring-2 focus-visible:ring-primary/40 focus-visible:outline-none dark:border-slate-800 dark:bg-slate-900"
                        placeholder="mpf-000001"
                    />
                    <InputError :message="form.errors.password" />
                </div>
                <label
                    class="flex items-center gap-2 text-sm text-muted-foreground"
                >
                    <input v-model="form.remember" type="checkbox" />
                    Se souvenir de moi
                </label>
                <button
                    type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-2xl bg-primary py-3.5 font-black text-primary-foreground shadow-lg shadow-primary/20 transition hover:opacity-90 disabled:opacity-50"
                    :disabled="form.processing"
                >
                    <LogIn class="h-4 w-4" />
                    Connexion
                </button>
            </form>
            <p
                class="border-t border-slate-100 px-6 py-4 text-center text-xs text-slate-500 dark:border-slate-800"
            >
                <a
                    href="/login"
                    class="font-semibold text-primary underline dark:text-blue-400"
                    >Accès personnel église</a
                >
            </p>
        </div>
    </div>
</template>
