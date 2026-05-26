<script setup lang="ts">
import { Moon, Sun } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { useAppearance } from '@/composables/useAppearance';

const { resolvedAppearance, updateAppearance } = useAppearance();

function toggle() {
    updateAppearance(resolvedAppearance.value === 'dark' ? 'light' : 'dark');
}
</script>

<template>
    <Button
        variant="ghost"
        size="icon"
        class="h-9 w-9 shrink-0 text-muted-foreground hover:text-foreground"
        :title="resolvedAppearance === 'dark' ? 'Mode clair' : 'Mode sombre'"
        @click="toggle"
    >
        <Sun
            v-if="resolvedAppearance === 'dark'"
            class="h-4 w-4 text-amber-400"
        />
        <Moon v-else class="h-4 w-4 text-slate-600" />
    </Button>
</template>
