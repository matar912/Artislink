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
    region: '',
});

const submit = () => {
    form.post(route('inscription.artisan.traiter'));
};
</script>

<template>
    <Head title="Inscription Artisan" />

    <div class="min-h-screen bg-gray-50 py-12 px-6">
        <FlashMessages />
        <div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-xl overflow-hidden">
            <div class="bg-indigo-900 p-8 text-center">
                <h1 class="text-3xl font-bold text-white">Rejoignez l'aventure Artislink</h1>
                <p class="text-indigo-200 mt-2">Créez votre profil artisan et commencez à exposer votre savoir-faire.</p>
            </div>

            <form @submit.prevent="submit" class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Prénom -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Prénom</label>
                        <input v-model="form.prenom" type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition" required />
                        <div v-if="form.errors.prenom" class="text-red-500 text-xs mt-1">{{ form.errors.prenom }}</div>
                    </div>

                    <!-- Nom -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nom / Enseigne</label>
                        <input v-model="form.nom" type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition" required />
                        <div v-if="form.errors.nom" class="text-red-500 text-xs mt-1">{{ form.errors.nom }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email professionnel</label>
                        <input v-model="form.email" type="email" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition" required />
                        <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
                    </div>

                    <!-- Catégorie -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Catégorie de métier</label>
                        <select v-model="form.categorie" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
                            <option value="">Choisissez une catégorie</option>
                            <option value="Menuiserie">Menuiserie</option>
                            <option value="Poterie">Poterie</option>
                            <option value="Bijouterie">Bijouterie</option>
                            <option value="Couture">Couture</option>
                            <option value="Autre">Autre</option>
                        </select>
                        <div v-if="form.errors.categorie" class="text-red-500 text-xs mt-1">{{ form.errors.categorie }}</div>
                    </div>
                </div>

                <!-- Téléphone -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Téléphone</label>
                    <input v-model="form.telephone" type="tel" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition" />
                    <div v-if="form.errors.telephone" class="text-red-500 text-xs mt-1">{{ form.errors.telephone }}</div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Description de votre activité</label>
                    <textarea v-model="form.description" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition" placeholder="Parlez-nous de votre passion..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Ville -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Ville</label>
                        <input v-model="form.ville" type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition" />
                    </div>

                    <!-- Région -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Région</label>
                        <input v-model="form.region" type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Mot de passe</label>
                        <input v-model="form.mot_de_passe" type="password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition" required />
                        <div v-if="form.errors.mot_de_passe" class="text-red-500 text-xs mt-1">{{ form.errors.mot_de_passe }}</div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Confirmer le mot de passe</label>
                        <input v-model="form.mot_de_passe_confirmation" type="password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition" required />
                        <div v-if="form.errors.mot_de_passe_confirmation" class="text-red-500 text-xs mt-1">{{ form.errors.mot_de_passe_confirmation }}</div>
                    </div>
                </div>

                <button 
                    type="submit" 
                    :disabled="form.processing"
                    class="w-full py-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition disabled:opacity-50 mt-4"
                >
                    {{ form.processing ? 'Création de votre compte...' : 'Créer mon profil Artisan' }}
                </button>

                <p class="text-center text-sm text-gray-500">
                    Vous avez déjà un compte ? 
                    <Link :href="route('connexion')" class="text-indigo-600 font-bold hover:underline">Connectez-vous</Link>
                </p>
            </form>
        </div>
    </div>
</template>
