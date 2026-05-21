<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import FlashMessages from '@/Components/FlashMessages.vue';

const form = useForm({
    email: '',
    mot_de_passe: '', // Note : ton contrôleur attend 'mot_de_passe'
    se_souvenir: false,
});

const submit = () => {
    form.post(route('connexion.traiter'), {
        onFinish: () => form.reset('mot_de_passe'),
    });
};
</script>

<template>
    <Head title="Connexion" />

    <div class="min-h-screen flex items-center justify-center bg-gray-50 p-6">
        <FlashMessages />
        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
            <div class="text-center mb-8">
                <Link :href="route('accueil')" class="text-3xl font-extrabold text-indigo-900">
                    Artis<span class="text-orange-500">link</span>
                </Link>
                <h2 class="text-xl font-semibold text-gray-700 mt-4">Bon retour parmi nous !</h2>
            </div>

            <form @submit.prevent="submit">
                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Adresse Email</label>
                    <input 
                        v-model="form.email"
                        type="email" 
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition"
                        placeholder="nom@exemple.com"
                        required
                    />
                    <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
                </div>

                <!-- Mot de passe -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                    <input 
                        v-model="form.mot_de_passe"
                        type="password" 
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition"
                        placeholder="••••••••"
                        required
                    />
                    <div v-if="form.errors.mot_de_passe" class="text-red-500 text-xs mt-1">{{ form.errors.mot_de_passe }}</div>
                </div>

                <!-- Se souvenir de moi -->
                <div class="flex items-center justify-between mb-8">
                    <label class="flex items-center text-sm text-gray-600">
                        <input type="checkbox" v-model="form.se_souvenir" class="mr-2 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        Se souvenir de moi
                    </label>
                    <a href="#" class="text-sm text-indigo-600 hover:underline">Mot de passe oublié ?</a>
                </div>

                <button 
                    type="submit" 
                    :disabled="form.processing"
                    class="w-full py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition disabled:opacity-50"
                >
                    {{ form.processing ? 'Connexion...' : 'Se connecter' }}
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-gray-500">
                Pas encore de compte ? 
                <Link :href="route('accueil')" class="text-indigo-600 font-bold hover:underline">Inscrivez-vous</Link>
            </p>
        </div>
    </div>
</template>
