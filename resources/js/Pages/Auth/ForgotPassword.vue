<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import FlashMessages from '@/Components/FlashMessages.vue';

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Mot de passe oublié — Artika" />

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-cream">
        <!-- Left Side: Brand Decor -->
        <div class="hidden lg:flex flex-col items-center justify-center bg-gradient-to-br from-deep via-[#3d1f00] to-[#5c2e00] p-12 relative overflow-hidden">
            <div class="absolute inset-0 opacity-[0.05]" style="background-image: repeating-linear-gradient(45deg, #d4a017 0, #d4a017 1px, transparent 0, transparent 30px); background-size: 30px 30px;"></div>
            
            <div class="relative z-10 text-center">
                <Link :href="route('accueil')" class="font-serif text-7xl text-gold tracking-tighter mb-4 block">
                    Artika
                </Link>
                <p class="text-white/60 text-xl font-medium leading-relaxed max-w-sm">
                    Sécurisez votre accès à l'artisanat sénégalais authentique
                </p>
            </div>
            
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-gold/5 rounded-full blur-3xl"></div>
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-earth/5 rounded-full blur-3xl"></div>
        </div>

        <!-- Right Side: Form -->
        <div class="flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md">
                <div class="mb-10">
                    <Link :href="route('accueil')" class="lg:hidden font-serif text-3xl font-bold text-terr tracking-tighter mb-8 block">
                        Artik<span class="text-gold">a</span>
                    </Link>
                    <h2 class="font-serif text-3xl text-deep mb-3">Récupération 🔑</h2>
                    <p class="text-muted_artika leading-relaxed">
                        Mot de passe oublié ? Pas de problème. Indiquez votre e-mail et nous vous enverrons un lien de réinitialisation.
                    </p>
                </div>

                <FlashMessages />

                <div v-if="status" class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl font-medium">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Votre adresse e-mail</label>
                        <input 
                            v-model="form.email"
                            type="email" 
                            class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors"
                            placeholder="votre@email.com"
                            required
                            autofocus
                        />
                        <div v-if="form.errors.email" class="text-red-500 text-xs mt-1 font-medium">{{ form.errors.email }}</div>
                    </div>

                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="w-full bg-gradient-to-br from-terr to-earth text-white font-bold py-4 rounded-xl shadow-[0_4px_16px_rgba(139,69,19,0.35)] hover:scale-[1.01] transition-all disabled:opacity-50"
                    >
                        {{ form.processing ? 'Envoi en cours...' : 'Envoyer le lien de récupération →' }}
                    </button>
                </form>

                <div class="mt-10 text-center">
                    <Link :href="route('login')" class="text-xs font-bold text-muted_artika uppercase tracking-widest hover:text-terr transition-colors">
                        ← Retour à la connexion
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
