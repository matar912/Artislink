<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import FlashMessages from '@/Components/FlashMessages.vue';

const form = useForm({
    nom: '',
    prenom: '',
    email: '',
    mot_de_passe: '',
    mot_de_passe_confirmation: '',
    telephone: '',
    categorie: '',
    description: '',
    ville: '',
});

const submit = () => {
    form.post(route('inscription.artisan.traiter'));
};
</script>

<template>
    <Head title="Inscription Artisan — Artika" />

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-cream">
        <!-- Left Side: Brand Decor -->
        <div class="hidden lg:flex flex-col items-center justify-center bg-gradient-to-br from-deep via-[#3d1f00] to-[#5c2e00] p-12 relative overflow-hidden">
            <div class="absolute inset-0 opacity-[0.05]" style="background-image: repeating-linear-gradient(45deg, #d4a017 0, #d4a017 1px, transparent 0, transparent 30px); background-size: 30px 30px;"></div>
            
            <div class="relative z-10 text-center">
                <Link :href="route('accueil')" class="font-serif text-7xl text-gold tracking-tighter mb-4 block">
                    Artika
                </Link>
                <p class="text-white/60 text-xl font-medium leading-relaxed max-w-sm">
                    Exposez votre savoir-faire au monde entier et développez votre activité.
                </p>
            </div>
            
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-gold/5 rounded-full blur-3xl"></div>
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-earth/5 rounded-full blur-3xl"></div>
        </div>

        <!-- Right Side: Registration Form -->
        <div class="flex items-center justify-center p-6 sm:p-12 overflow-y-auto">
            <div class="w-full max-w-2xl">
                <div class="mb-10">
                    <Link :href="route('accueil')" class="lg:hidden font-serif text-3xl font-bold text-terr tracking-tighter mb-8 block">
                        Artik<span class="text-gold">a</span>
                    </Link>
                    <h2 class="font-serif text-3xl text-deep mb-2">Devenir Artisan ✨</h2>
                    <p class="text-muted_artika">Créez votre boutique en quelques minutes</p>
                </div>

                <FlashMessages />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Prénom -->
                        <div>
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Prénom</label>
                            <input 
                                v-model="form.prenom"
                                type="text" 
                                class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors"
                                placeholder="Abdoulaye"
                                required
                            />
                            <div v-if="form.errors.prenom" class="text-red-500 text-xs mt-1 font-medium">{{ form.errors.prenom }}</div>
                        </div>

                        <!-- Nom -->
                        <div>
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Nom / Enseigne</label>
                            <input 
                                v-model="form.nom"
                                type="text" 
                                class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors"
                                placeholder="Ndiaye"
                                required
                            />
                            <div v-if="form.errors.nom" class="text-red-500 text-xs mt-1 font-medium">{{ form.errors.nom }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Email -->
                        <div>
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Email professionnel</label>
                            <input 
                                v-model="form.email"
                                type="email" 
                                class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors"
                                placeholder="contact@atelier.sn"
                                required
                            />
                            <div v-if="form.errors.email" class="text-red-500 text-xs mt-1 font-medium">{{ form.errors.email }}</div>
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Téléphone</label>
                            <input 
                                v-model="form.telephone"
                                type="tel" 
                                class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors"
                                placeholder="77 000 00 00"
                            />
                            <div v-if="form.errors.telephone" class="text-red-500 text-xs mt-1 font-medium">{{ form.errors.telephone }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Catégorie -->
                        <div>
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Votre métier</label>
                            <select 
                                v-model="form.categorie" 
                                class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors appearance-none"
                                required
                            >
                                <option value="">Choisir...</option>
                                <option value="Menuiserie">Menuiserie</option>
                                <option value="Poterie">Poterie</option>
                                <option value="Bijouterie">Bijouterie</option>
                                <option value="Couture">Couture</option>
                                <option value="Gastronomie">Gastronomie</option>
                                <option value="Autre">Autre</option>
                            </select>
                            <div v-if="form.errors.categorie" class="text-red-500 text-xs mt-1 font-medium">{{ form.errors.categorie }}</div>
                        </div>

                        <!-- Ville -->
                        <div>
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Ville</label>
                            <input 
                                v-model="form.ville"
                                type="text" 
                                class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors"
                                placeholder="Dakar, Mbour..."
                                required
                            />
                            <div v-if="form.errors.ville" class="text-red-500 text-xs mt-1 font-medium">{{ form.errors.ville }}</div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Description de votre activité</label>
                        <textarea 
                            v-model="form.description"
                            rows="2"
                            class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors"
                            placeholder="Décrivez votre savoir-faire..."
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
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
                    </div>

                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="w-full bg-gradient-to-br from-terr to-earth text-white font-bold py-4 rounded-xl shadow-[0_4px_16px_rgba(139,69,19,0.35)] hover:scale-[1.01] transition-all disabled:opacity-50"
                    >
                        {{ form.processing ? 'Création en cours...' : 'Créer mon profil artisan →' }}
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
