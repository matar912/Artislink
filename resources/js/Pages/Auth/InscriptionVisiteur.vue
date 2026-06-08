<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import FlashMessages from '@/Components/FlashMessages.vue';

const form = useForm({
    nom: '',
    prenom: '',
    email: '',
    mot_de_passe: '',
    mot_de_passe_confirmation: '',
    pays: 'Sénégal',
    ville: '',
});

const submit = () => {
    form.post(route('inscription.visiteur.traiter'));
};
</script>

<template>
    <Head title="Inscription Client — Artika" />

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-cream">
        <!-- Left Side: Brand Decor -->
        <div class="hidden lg:flex flex-col items-center justify-center bg-gradient-to-br from-deep via-[#3d1f00] to-[#5c2e00] p-12 relative overflow-hidden">
            <div class="absolute inset-0 opacity-[0.05]" style="background-image: repeating-linear-gradient(45deg, #d4a017 0, #d4a017 1px, transparent 0, transparent 30px); background-size: 30px 30px;"></div>
            
            <div class="relative z-10 text-center">
                <Link :href="route('accueil')" class="font-serif text-7xl text-gold tracking-tighter mb-4 block">
                    Artika
                </Link>
                <p class="text-white/60 text-xl font-medium leading-relaxed max-w-sm">
                    Découvrez des trésors uniques et soutenez l'artisanat local sénégalais.
                </p>
            </div>
            
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-gold/5 rounded-full blur-3xl"></div>
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-earth/5 rounded-full blur-3xl"></div>
        </div>

        <!-- Right Side: Registration Form -->
        <div class="flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md">
                <div class="mb-10 text-center lg:text-left">
                    <Link :href="route('accueil')" class="lg:hidden font-serif text-3xl font-bold text-terr tracking-tighter mb-8 block">
                        Artik<span class="text-gold">a</span>
                    </Link>
                    <h2 class="font-serif text-3xl text-deep mb-2">Bienvenue Voyageur 👋</h2>
                    <p class="text-muted_artika">Créez votre compte client pour commander</p>
                </div>

                <FlashMessages />

                <form @submit.prevent="submit" class="space-y-5">
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Prénom -->
                        <div>
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Prénom</label>
                            <input 
                                v-model="form.prenom"
                                type="text" 
                                class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors"
                                placeholder="Jean"
                                required
                            />
                            <div v-if="form.errors.prenom" class="text-red-500 text-xs mt-1 font-medium">{{ form.errors.prenom }}</div>
                        </div>

                        <!-- Nom -->
                        <div>
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Nom</label>
                            <input 
                                v-model="form.nom"
                                type="text" 
                                class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors"
                                placeholder="Dupont"
                                required
                            />
                            <div v-if="form.errors.nom" class="text-red-500 text-xs mt-1 font-medium">{{ form.errors.nom }}</div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Email</label>
                        <input 
                            v-model="form.email"
                            type="email" 
                            class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors"
                            placeholder="votre@email.com"
                            required
                        />
                        <div v-if="form.errors.email" class="text-red-500 text-xs mt-1 font-medium">{{ form.errors.email }}</div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Ville -->
                        <div>
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Ville</label>
                            <input 
                                v-model="form.ville"
                                type="text" 
                                class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors"
                                placeholder="Dakar"
                            />
                        </div>
                        <!-- Pays -->
                        <div>
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Pays</label>
                            <input 
                                v-model="form.pays"
                                type="text" 
                                class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors"
                                placeholder="Sénégal"
                            />
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Mot de passe</label>
                        <input 
                            v-model="form.mot_de_passe"
                            type="password" 
                            class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors"
                            placeholder="••••••••"
                            required
                        />
                        <div v-if="form.errors.mot_de_passe" class="text-red-500 text-xs mt-1 font-medium">{{ form.errors.mot_de_passe }}</div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Confirmation</label>
                        <input 
                            v-model="form.mot_de_passe_confirmation"
                            type="password" 
                            class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors"
                            placeholder="••••••••"
                            required
                        />
                    </div>

                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="w-full bg-gradient-to-br from-terr to-earth text-white font-bold py-4 rounded-xl shadow-[0_4px_16px_rgba(139,69,19,0.35)] hover:scale-[1.01] transition-all disabled:opacity-50"
                    >
                        {{ form.processing ? 'Exploration en cours...' : 'Créer mon compte client →' }}
                    </button>
                </form>

                <div class="mt-8 text-center text-sm text-muted_artika font-medium">
                    Déjà un compte ? 
                    <Link :href="route('login')" class="text-terr font-bold hover:underline">Se connecter</Link>
                </div>

                <div class="mt-6">
                    <Link :href="route('inscription')" class="flex items-center justify-center gap-2 text-xs font-bold text-muted_artika uppercase tracking-widest hover:text-terr transition-colors">
                        ← Changer de type de compte
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
