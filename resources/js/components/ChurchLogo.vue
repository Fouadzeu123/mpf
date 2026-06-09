<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { Church } from 'lucide-vue-next';
import { computed, ref } from 'vue';

withDefaults(
    defineProps<{
        size?: 'sm' | 'md' | 'lg' | 'xl';
        showName?: boolean;
        variant?: 'default' | 'sidebar' | 'auth';
    }>(),
    {
        size: 'md',
        showName: true,
        variant: 'default',
    },
);

const page = usePage();
const churchName = computed(
    () => (page.props.name as string) || 'Ministère Prophétique de la Foi',
);

const sizeClasses = {
    sm: 'h-8 w-8',
    md: 'h-10 w-10',
    lg: 'h-14 w-14',
    xl: 'h-20 w-20',
};

function getAppUrl(path: string): string {
    const meta = document.querySelector<HTMLMetaElement>('meta[name="app-url"]');
    const baseUrl = meta?.content ? meta.content.replace(/\/$/, '') : '';
    return `${baseUrl}/${path.replace(/^\//, '')}`;
}

const imgError = ref(false);

const textClasses = {
    sm: 'text-sm',
    md: 'text-base',
    lg: 'text-lg',
    xl: 'text-xl',
};
</script>

<template>
    <div
        class="flex items-center gap-3"
        :class="variant === 'auth' ? 'flex-col text-center' : ''"
    >
        <div
            class="relative flex shrink-0 items-center justify-center overflow-hidden rounded-xl ring-1 ring-primary/30"
            :class="[
                sizeClasses[size],
                variant === 'sidebar'
                    ? 'bg-white/10'
                    : 'bg-gradient-to-br from-blue-50 to-white dark:from-blue-950/40 dark:to-slate-900',
            ]"
        >
            <img
                v-if="!imgError"
                :src="getAppUrl('/favicon.svg')"
                :alt="churchName"
                class="h-[85%] w-[85%] object-contain"
                @error="imgError = true"
            />
            <Church
                v-else
                class="h-1/2 w-1/2 text-primary"
                aria-hidden="true"
            />
        </div>
        <div v-if="showName" :class="variant === 'auth' ? 'space-y-0.5' : ''">
            <span
                class="font-semibold tracking-tight"
                :class="[
                    textClasses[size],
                    variant === 'sidebar'
                        ? 'text-sidebar-foreground'
                        : 'text-foreground',
                ]"
            >
                {{ churchName }}
            </span>
            <p v-if="variant === 'auth'" class="text-xs text-muted-foreground">
                Gestion des membres
            </p>
        </div>
    </div>
</template>
