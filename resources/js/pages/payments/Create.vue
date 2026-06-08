<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import MemberLayout from '@/layouts/MemberLayout.vue';

defineProps<{
    member: { first_name: string; member_code: string };
    amount: number;
}>();

const form = useForm({});
</script>

<template>
    <Head title="Paiement" />
    <MemberLayout title="Paiement Sainte Cène">
        <div class="rounded-2xl bg-white p-6 shadow-lg">
            <h1 class="text-xl font-bold">Préparation à distance</h1>
            <p class="mt-2 text-slate-600">
                Bonjour {{ member.first_name }}, montant :
                <strong>{{ amount }} FCFA</strong>
            </p>
            <p class="mt-2 text-sm text-slate-500">
                Orange Money / MTN via Notch Pay
            </p>
            <form class="mt-6" @submit.prevent="form.post('/membre/paiement')">
                <button
                    type="submit"
                    class="w-full rounded-xl bg-primary py-3 font-semibold text-primary-foreground transition hover:opacity-90 shadow-md shadow-primary/20 active:scale-[0.98]"
                    :disabled="form.processing"
                >
                    Payer et préparer
                </button>
            </form>
        </div>
    </MemberLayout>
</template>
