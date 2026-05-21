<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import FlashMessages from '@/Components/FlashMessages.vue';

const page = usePage();
const auth = page.props.auth as { user: any };
</script>

<template>
    <Head title="Bienvenue sur Artislink" />

    <div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center p-6">
        <FlashMessages />
        <!-- Logo ou Nom du projet -->
        <div class="mb-12 text-center">
            <h1 class="text-5xl font-extrabold text-indigo-900 tracking-tight">
                Artis<span class="text-orange-500">link</span>
            </h1>
            <p class="mt-4 text-gray-600 text-lg max-w-md">
                La plateforme qui connecte les artisans passionnés et les visiteurs curieux.
            </p>
        </div>

        <!-- Section des boutons de choix -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-4xl">

            <!-- Carte Artisan -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow text-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Je suis un Artisan</h2>
                <p class="text-gray-500 mb-6">Proposez vos services, créez des visites de votre atelier et gérez vos rendez-vous.</p>
                
                <template v-if="auth.user && auth.user.role === 'artisan'">
                    <Link
                        :href="route('artisan.tableau-bord')"
                        class="inline-block w-full py-3 px-6 bg-indigo-900 text-white font-semibold rounded-xl hover:bg-black transition"
                    >
                        Accéder à mon espace
                    </Link>
                </template>
                <template v-else-if="auth.user">
                    <div class="py-3 px-6 bg-gray-100 text-gray-400 rounded-xl font-semibold cursor-not-allowed">
                        Compte visiteur actif
                    </div>
                </template>
                <template v-else>
                    <Link
                        :href="route('inscription.artisan')"
                        class="inline-block w-full py-3 px-6 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition"
                    >
                        Devenir exposant
                    </Link>
                </template>
            </div>

            <!-- Carte Visiteur -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow text-center">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Je suis un Visiteur</h2>
                <p class="text-gray-500 mb-6">Découvrez des artisans locaux, réservez des visites et soutenez l'artisanat.</p>
                
                <template v-if="auth.user && auth.user.role === 'visiteur'">
                    <Link
                        :href="route('visiteur.tableau-bord')"
                        class="inline-block w-full py-3 px-6 bg-orange-600 text-white font-semibold rounded-xl hover:bg-orange-700 transition"
                    >
                        Mon espace visiteur
                    </Link>
                </template>
                <template v-else-if="auth.user">
                    <div class="py-3 px-6 bg-gray-100 text-gray-400 rounded-xl font-semibold cursor-not-allowed">
                        Compte artisan actif
                    </div>
                </template>
                <template v-else>
                    <Link
                        :href="route('inscription.visiteur')"
                        class="inline-block w-full py-3 px-6 bg-orange-500 text-white font-semibold rounded-xl hover:bg-orange-600 transition"
                    >
                        Explorer les ateliers
                    </Link>
                </template>
            </div>

        </div>

        <!-- Footer / Connexion -->
        <div class="mt-12 text-gray-600">
            <template v-if="auth.user">
                Connecté en tant que <span class="font-bold text-indigo-900">{{ auth.user.prenom }}</span>.
                <Link :href="route('deconnexion')" method="post" as="button" class="text-red-500 font-bold hover:underline ml-2">
                    Déconnexion
                </Link>
            </template>
            <template v-else>
                Déjà un compte ?
                <Link :href="route('connexion')" class="text-indigo-600 font-bold hover:underline ml-1">
                    Connectez-vous ici
                </Link>
            </template>
        </div>
    </div>
</template>
