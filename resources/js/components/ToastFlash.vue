<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { watch } from 'vue';

const page = usePage();

function showToast() {
    const toast = page.props.toast as { type?: string; message?: string } | null;

    if (!toast?.message) {
return;
}

    const el = document.createElement('div');
    el.className = `fixed top-4 right-4 z-[100] max-w-sm rounded-lg px-4 py-3 text-sm shadow-lg transition ${
        toast.type === 'error'
            ? 'bg-red-600 text-white'
            : toast.type === 'warning'
              ? 'bg-amber-500 text-slate-900'
              : 'bg-emerald-600 text-white'
    }`;
    el.textContent = toast.message;
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 4000);
}

watch(() => page.props.toast, showToast, { immediate: true });
</script>

<template>
    <span class="hidden" />
</template>
