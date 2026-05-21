<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import FlashMessages from '@/Components/FlashMessages.vue';

defineProps<{
    artisan: any;
    commandes: any[];
}>();
</script>

<template>
    <Head title="Tableau de Bord Artisan" />

    <div class="min-h-screen bg-gray-100">
        <FlashMessages />
        <!-- Barre de navigation simple -->
        <nav class="bg-indigo-900 text-white p-4 shadow-md">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="text-2xl font-bold">Artislink <span class="text-sm font-normal text-indigo-300">| Espace Artisan</span></div>
                <div class="flex items-center space-x-6">
                    <Link :href="route('artisan.produits.liste')" class="hover:text-indigo-300">Mes Produits</Link>
                    <Link :href="route('artisan.commandes.liste')" class="hover:text-indigo-300">Commandes</Link>
                    <Link :href="route('deconnexion')" method="post" as="button" class="bg-red-500 px-4 py-2 rounded-lg hover:bg-red-600 transition">Déconnexion</Link>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-10 px-6">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Bienvenue, {{ artisan.user.prenom }} !</h1>
                <div class="flex space-x-4">
                    <Link :href="route('artisan.produits.formulaire-creation')" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-indigo-700 transition flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Ajouter un produit
                    </Link>
                    <Link :href="route('artisan.profil.formulaire')" class="bg-white border border-gray-200 text-gray-700 px-6 py-2 rounded-xl font-bold hover:bg-gray-50 transition flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                        Modifier mon profil
                    </Link>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                    <div class="text-gray-500 text-sm uppercase font-bold mb-1">Note Moyenne</div>
                    <div class="text-3xl font-bold text-indigo-900">{{ artisan.note_moyenne }} / 5</div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                    <div class="text-gray-500 text-sm uppercase font-bold mb-1">Avis Reçus</div>
                    <div class="text-3xl font-bold text-indigo-900">{{ artisan.nombre_avis }}</div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                    <div class="text-gray-500 text-sm uppercase font-bold mb-1">Statut Profil</div>
                    <div class="flex items-center mt-1">
                        <span :class="artisan.est_actif ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'" class="px-3 py-1 rounded-full text-xs font-bold">
                            {{ artisan.est_actif ? 'ACTIF' : 'INACTIF' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">Dernières commandes reçues</h2>
                    <Link :href="route('artisan.commandes.liste')" class="text-indigo-600 text-sm font-bold hover:underline">Voir tout</Link>
                </div>
                <div class="p-6">
                    <div v-if="commandes.length === 0" class="text-center py-10 text-gray-500">
                        Aucune commande pour le moment.
                    </div>
                    <table v-else class="w-full text-left">
                        <thead>
                            <tr class="text-gray-400 text-sm uppercase">
                                <th class="pb-4">Commande #</th>
                                <th class="pb-4">Client</th>
                                <th class="pb-4">Montant</th>
                                <th class="pb-4">Statut</th>
                                <th class="pb-4">Date</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <tr v-for="commande in commandes" :key="commande.id" class="border-t border-gray-50">
                                <td class="py-4 font-mono text-sm">#{{ commande.id }}</td>
                                <td class="py-4 font-bold">{{ commande.visiteur.user.name }}</td>
                                <td class="py-4">{{ commande.montant_total }} FCFA</td>
                                <td class="py-4">
                                    <span class="px-2 py-1 rounded-lg text-xs font-bold uppercase bg-gray-100 text-gray-600">
                                        {{ commande.statut }}
                                    </span>
                                </td>
                                <td class="py-4 text-sm">{{ new Date(commande.created_at).toLocaleDateString() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</template>
