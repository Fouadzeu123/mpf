<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { LogIn, Mail, Shield } from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();
</script>

<template>
    <AuthBase
        title="Connexion"
        description="Accédez à l'espace personnel de l'église"
    >
        <Head title="Connexion" />

        <div
            v-if="status"
            class="mb-4 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-center text-sm font-medium text-white dark:text-emerald-400"
        >
            {{ status }}
        </div>

        <Form
            v-bind="store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-5">
                <div class="grid gap-2">
                    <Label for="email" class="flex items-center gap-2">
                        <Mail class="h-4 w-4 text-blue-500" />
                        Adresse e-mail
                    </Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        placeholder="vous@exemple.com"
                        class="border-slate-200 focus-visible:ring-primary/40"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password" class="flex items-center gap-2">
                            <Shield class="h-4 w-4 text-blue-500" />
                            Mot de passe
                        </Label>
                        <TextLink
                            v-if="canResetPassword"
                            :href="request()"
                            class="text-xs text-primary dark:text-blue-400"
                            :tabindex="5"
                        >
                            Mot de passe oublié ?
                        </TextLink>
                    </div>
                    <PasswordInput
                        id="password"
                        name="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        placeholder="••••••••"
                    />
                    <InputError :message="errors.password" />
                </div>

                <Label
                    for="remember"
                    class="flex cursor-pointer items-center gap-3 text-sm"
                >
                    <Checkbox id="remember" name="remember" :tabindex="3" />
                    Se souvenir de moi
                </Label>

                <Button
                    type="submit"
                    class="mt-2 w-full gap-2 bg-primary text-primary-foreground hover:opacity-90 dark:bg-primary dark:text-primary-foreground dark:hover:opacity-90"
                    :tabindex="4"
                    :disabled="processing"
                >
                    <Spinner v-if="processing" />
                    <LogIn v-else class="h-4 w-4" />
                    Se connecter
                </Button>
            </div>
        </Form>
    </AuthBase>
</template>
