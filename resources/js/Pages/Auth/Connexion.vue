<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import FlashMessages from '@/Components/FlashMessages.vue';
import { ref, watch } from 'vue';

const form = useForm({
    email: '',
    mot_de_passe: '',
    role: 'visiteur', // Par défaut
    se_souvenir: false,
});

const currentRole = ref('visiteur');

const selectRole = (role: string) => {
    currentRole.value = role;
    form.role = role;
};

// S'assurer que le formulaire a toujours le bon rôle
watch(currentRole, (newRole) => {
    form.role = newRole;
});

const submit = () => {
    form.post(route('connexion.traiter'), {
        onFinish: () => form.reset('mot_de_passe'),
    });
};
</script>

<template>
    <Head title="Bienvenue — Artika" />

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-cream">
        <!-- Left Side: Brand Decor -->
        <div class="hidden lg:flex flex-col items-center justify-center bg-gradient-to-br from-deep via-[#3d1f00] to-[#5c2e00] p-12 relative overflow-hidden">
            <div class="absolute inset-0 opacity-[0.05]" style="background-image: repeating-linear-gradient(45deg, #d4a017 0, #d4a017 1px, transparent 0, transparent 30px); background-size: 30px 30px;"></div>
            
            <div class="relative z-10 text-center">
                <Link :href="route('accueil')" class="font-serif text-7xl text-gold tracking-tighter mb-4 block">
                    Artika
                </Link>
                <p class="text-white/60 text-xl font-medium leading-relaxed max-w-sm">
                    La plateforme e-commerce de l'artisanat sénégalais authentique
                </p>
            </div>
            
            <!-- Floating Elements / Pattern Decor -->
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-gold/5 rounded-full blur-3xl"></div>
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-earth/5 rounded-full blur-3xl"></div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md">
                <div class="mb-10">
                    <Link :href="route('accueil')" class="lg:hidden font-serif text-3xl font-bold text-terr tracking-tighter mb-8 block">
                        Artik<span class="text-gold">a</span>
                    </Link>
                    <h2 class="font-serif text-3xl text-deep mb-2">Bienvenue 👋</h2>
                    <p class="text-muted_artika">Connectez-vous pour accéder à votre espace</p>
                </div>

                <FlashMessages />

                <!-- Role Selection Tabs -->
                <div class="grid grid-cols-3 gap-2 mb-8">
                    <button 
                        @click="selectRole('visiteur')"
                        type="button"
                        :class="[
                            'py-2.5 text-xs font-bold rounded-lg border-2 transition-all',
                            currentRole === 'visiteur' ? 'bg-terr border-terr text-white' : 'bg-white border-earth/20 text-muted_artika hover:border-terr'
                        ]"
                    >
                        🛍️ Client
                    </button>
                    <button 
                        @click="selectRole('artisan')"
                        type="button"
                        :class="[
                            'py-2.5 text-xs font-bold rounded-lg border-2 transition-all',
                            currentRole === 'artisan' ? 'bg-terr border-terr text-white' : 'bg-white border-earth/20 text-muted_artika hover:border-terr'
                        ]"
                    >
                        🏺 Artisan
                    </button>
                    <button 
                        @click="selectRole('admin')"
                        type="button"
                        :class="[
                            'py-2.5 text-xs font-bold rounded-lg border-2 transition-all',
                            currentRole === 'admin' ? 'bg-terr border-terr text-white' : 'bg-white border-earth/20 text-muted_artika hover:border-terr'
                        ]"
                    >
                        ⚙️ Admin
                    </button>
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Email -->
                    <div>
                        <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Email ou téléphone</label>
                        <input 
                            v-model="form.email"
                            type="email" 
                            class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors"
                            placeholder="votre@email.com"
                            required
                        />
                        <div v-if="form.errors.email" class="text-red-500 text-xs mt-1 font-medium">{{ form.errors.email }}</div>
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest">Mot de passe</label>
                            <Link :href="route('password.request')" class="text-[10px] font-bold text-earth uppercase tracking-widest hover:underline">Oublié ?</Link>
                        </div>
                        <input 
                            v-model="form.mot_de_passe"
                            type="password" 
                            class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors"
                            placeholder="••••••••"
                            required
                        />
                        <div v-if="form.errors.mot_de_passe" class="text-red-500 text-xs mt-1 font-medium">{{ form.errors.mot_de_passe }}</div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input type="checkbox" v-model="form.se_souvenir" id="remember" class="w-4 h-4 rounded border-earth/20 text-terr focus:ring-terr">
                        <label for="remember" class="ml-2 text-xs font-medium text-muted_artika">Se souvenir de moi</label>
                    </div>

                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="w-full bg-gradient-to-br from-terr to-earth text-white font-bold py-4 rounded-xl shadow-[0_4px_16px_rgba(139,69,19,0.35)] hover:scale-[1.01] transition-all disabled:opacity-50"
                    >
                        <span v-if="currentRole === 'artisan'">Accéder à mon espace artisan →</span>
                        <span v-else-if="currentRole === 'admin'">Accéder à l'administration →</span>
                        <span v-else>Se connecter en tant que Client →</span>
                    </button>
                </form>

                <div class="mt-8 text-center text-sm text-muted_artika font-medium">
                    Pas encore de compte ? 
                    <Link :href="route('inscription')" class="text-terr font-bold hover:underline">S'inscrire gratuitement</Link>
                </div>

                <div class="mt-6">
                    <Link :href="route('accueil')" class="flex items-center justify-center gap-2 text-xs font-bold text-muted_artika uppercase tracking-widest hover:text-terr transition-colors">
                        ← Retour à l'accueil
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
