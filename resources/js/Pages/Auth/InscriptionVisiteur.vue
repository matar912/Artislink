<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import FlashMessages from '@/Components/FlashMessages.vue';

const form = useForm({
    nom: '',
    prenom: '',
    email: '',
    mot_de_passe: '',
    mot_de_passe_confirmation: '',
    pays: '',
    ville: '',
});

const submit = () => {
    form.post(route('inscription.visiteur.traiter'));
};
</script>

<template>
    <Head title="Inscription Visiteur" />

    <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-6">
        <FlashMessages />
        <div class="w-full max-w-md bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
            <div class="bg-orange-500 p-8 text-center">
                <h1 class="text-3xl font-bold text-white">Bienvenue Voyageur</h1>
                <p class="text-orange-100 mt-2">Prêt à découvrir des trésors artisanaux ?</p>
            </div>

            <form @submit.prevent="submit" class="p-8 space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <!-- Nom -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Votre nom</label>
                        <input v-model="form.nom" type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 outline-none transition" placeholder="Dupont" required />
                        <div v-if="form.errors.nom" class="text-red-500 text-xs mt-1">{{ form.errors.nom }}</div>
                    </div>

                    <!-- Prénom -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Votre prénom</label>
                        <input v-model="form.prenom" type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 outline-none transition" placeholder="Jean" required />
                        <div v-if="form.errors.prenom" class="text-red-500 text-xs mt-1">{{ form.errors.prenom }}</div>
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input v-model="form.email" type="email" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 outline-none transition" placeholder="jean@exemple.com" required />
                    <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Ville -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Ville</label>
                        <input v-model="form.ville" type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 outline-none transition" placeholder="Paris" />
                    </div>
                    <!-- Pays -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Pays</label>
                        <input v-model="form.pays" type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 outline-none transition" placeholder="France" />
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Mot de passe</label>
                    <input v-model="form.mot_de_passe" type="password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 outline-none transition" required />
                    <div v-if="form.errors.mot_de_passe" class="text-red-500 text-xs mt-1">{{ form.errors.mot_de_passe }}</div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Confirmer le mot de passe</label>
                    <input v-model="form.mot_de_passe_confirmation" type="password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 outline-none transition" required />
                    <div v-if="form.errors.mot_de_passe_confirmation" class="text-red-500 text-xs mt-1">{{ form.errors.mot_de_passe_confirmation }}</div>
                </div>

                <button 
                    type="submit" 
                    :disabled="form.processing"
                    class="w-full py-4 bg-orange-500 text-white font-bold rounded-xl hover:bg-orange-600 shadow-lg shadow-orange-100 transition disabled:opacity-50 mt-4"
                >
                    {{ form.processing ? 'Création de votre compte...' : 'Découvrir les artisans' }}
                </button>

                <p class="text-center text-sm text-gray-500">
                    Déjà un explorateur ? 
                    <Link :href="route('connexion')" class="text-orange-600 font-bold hover:underline">Connectez-vous</Link>
                </p>
            </form>
        </div>
    </div>
</template>
