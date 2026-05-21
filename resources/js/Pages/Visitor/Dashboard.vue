<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import FlashMessages from '@/Components/FlashMessages.vue';

defineProps<{
    commandes: any[];
}>();
</script>

<template>
    <Head title="Mon Espace Visiteur" />

    <div class="min-h-screen bg-gray-100">
        <FlashMessages />
        <!-- Barre de navigation -->
        <nav class="bg-orange-500 text-white p-4 shadow-md">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="text-2xl font-bold">Artislink <span class="text-sm font-normal text-orange-100">| Explorateur</span></div>
                <div class="flex items-center space-x-6">
                    <Link :href="route('artisans.liste')" class="hover:text-orange-100 font-bold">Découvrir les Artisans</Link>
                    <Link :href="route('visiteur.commandes.liste')" class="hover:text-orange-100">Mes Commandes</Link>
                    <Link :href="route('deconnexion')" method="post" as="button" class="bg-white text-orange-600 px-4 py-2 rounded-lg font-bold hover:bg-orange-50 transition">Déconnexion</Link>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-10 px-6">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Ravi de vous revoir, {{ $page.props.auth.user.prenom }} !</h1>
                <Link :href="route('visiteur.favoris.liste')" class="bg-orange-100 text-orange-700 px-6 py-2 rounded-xl font-bold hover:bg-orange-200 transition flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                    </svg>
                    Mes Artisans Favoris
                </Link>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Historique des commandes -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center text-orange-600">
                        <h2 class="text-xl font-bold">Mes derniers achats</h2>
                        <Link :href="route('visiteur.commandes.liste')" class="text-sm font-bold hover:underline">Voir l'historique</Link>
                    </div>
                    <div class="p-6">
                        <div v-if="commandes.length === 0" class="text-center py-10">
                            <p class="text-gray-500 mb-4">Vous n'avez pas encore passé de commande.</p>
                            <Link :href="route('artisans.liste')" class="inline-block bg-orange-500 text-white px-6 py-2 rounded-xl font-bold hover:bg-orange-600 transition">
                                Parcourir les ateliers
                            </Link>
                        </div>
                        <div v-else class="space-y-4">
                            <div v-for="commande in commandes" :key="commande.id" class="flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:border-orange-200 transition">
                                <div>
                                    <div class="font-bold text-gray-800">Commande #{{ commande.id }}</div>
                                    <div class="text-sm text-gray-500">{{ new Date(commande.created_at).toLocaleDateString() }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-orange-600">{{ commande.montant_total }} FCFA</div>
                                    <div class="text-xs uppercase font-bold text-gray-400">{{ commande.statut }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Favoris ou suggestions -->
                <div class="bg-indigo-900 text-white p-8 rounded-2xl shadow-lg relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="text-2xl font-bold mb-4">Besoin d'inspiration ?</h3>
                        <p class="text-indigo-200 mb-8">Découvrez les artisans les mieux notés de votre région.</p>
                        <Link :href="route('artisans.liste')" class="block text-center bg-white text-indigo-900 py-3 rounded-xl font-bold hover:bg-indigo-50 transition">
                            Voir la sélection
                        </Link>
                    </div>
                    <!-- Décoration -->
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-indigo-800 rounded-full blur-2xl opacity-50"></div>
                </div>
            </div>
        </main>
    </div>
</template>
