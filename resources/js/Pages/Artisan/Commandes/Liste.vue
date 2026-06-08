<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { getProductImage } from '@/utils';
import { ref, onMounted } from 'vue';

const props = defineProps<{
    commandes: Array<any>;
    auth: any;
}>();

onMounted(() => {
    if (window.Echo) {
        window.Echo.private(`user.${props.auth.user.id}`)
            .listen('NouvelleCommandeRecue', (e: any) => {
                router.reload({ only: ['commandes'] });
            });
    }
});

const selectedStatus = ref<string>('');
const processing = ref<number | null>(null);

const changerStatut = (commandeId: number, nouveauStatut: string) => {
    if (confirm(`Voulez-vous passer cette commande au statut "${getStatusLabel(nouveauStatut)}" ?`)) {
        processing.value = commandeId;
        router.patch(route('artisan.commandes.statut', commandeId), {
            statut: nouveauStatut
        }, {
            preserveScroll: true,
            onFinish: () => processing.value = null
        });
    }
};

const getStatusClass = (status: string) => {
    switch (status) {
        case 'en_attente': return 'bg-sand text-muted_artika border-gold/30';
        case 'confirmee': return 'bg-gold/10 text-gold border-gold/20';
        case 'expediee': return 'bg-blue-50 text-blue-600 border-blue-100';
        case 'livree': return 'bg-green_artika/10 text-green_artika border-green_artika/20';
        case 'annulee': return 'bg-red-50 text-red-600 border-red-100';
        default: return 'bg-gray-100 text-gray-700 border-gray-200';
    }
};

const getStatusLabel = (status: string) => {
    switch (status) {
        case 'en_attente': return 'En attente';
        case 'confirmee': return 'Confirmée';
        case 'expediee': return 'Expédiée';
        case 'livree': return 'Livrée';
        case 'annulee': return 'Annulée';
        default: return status;
    }
};

const formatPrix = (prix: number | string) => {
    return new Intl.NumberFormat('fr-FR').format(Number(prix));
};
</script>

<template>
    <Head title="Mes Commandes Reçues" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 py-2">
                <div>
                    <h2 class="font-serif text-3xl font-bold text-deep leading-tight">
                        Commandes <span class="text-gold italic">Reçues</span>
                    </h2>
                    <p class="text-muted_artika text-sm mt-1">Suivez et gérez les commandes de vos clients.</p>
                </div>
            </div>
        </template>

        <div class="py-12 bg-cream min-h-[calc(100vh-64px)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div v-if="commandes.length === 0" class="bg-white rounded-[2rem] p-20 text-center shadow-artika border border-earth/10">
                    <div class="h-24 w-24 bg-sand/30 rounded-full flex items-center justify-center mx-auto mb-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gold/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="font-serif text-2xl font-bold text-deep mb-4">Aucune commande pour le moment</h3>
                    <p class="text-muted_artika max-w-md mx-auto">Dès qu'un client passera commande sur l'un de vos produits, elle apparaîtra ici.</p>
                </div>

                <div v-else class="space-y-8">
                    <div v-for="commande in commandes" :key="commande.id" 
                         class="bg-white rounded-[2.5rem] shadow-artika border border-earth/10 overflow-hidden transition-all hover:shadow-xl">
                        
                        <!-- Header de la commande -->
                        <div class="bg-sand/20 px-8 py-6 border-b border-earth/5 flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 bg-white rounded-xl flex items-center justify-center text-terr shadow-sm border border-earth/10 font-bold">
                                    #{{ commande.id }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-deep">Commande de {{ commande.visiteur.user.prenom }} {{ commande.visiteur.user.nom }}</h4>
                                    <p class="text-xs text-muted_artika uppercase tracking-widest font-bold mt-0.5">
                                        Reçue le {{ new Date(commande.created_at).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' }) }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span :class="getStatusClass(commande.statut)" class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest border">
                                    {{ getStatusLabel(commande.statut) }}
                                </span>
                            </div>
                        </div>

                        <div class="p-8 grid grid-cols-1 lg:grid-cols-3 gap-10">
                            <!-- Produits commandés -->
                            <div class="lg:col-span-2 space-y-4">
                                <h5 class="text-[10px] font-bold text-gold uppercase tracking-[2px] mb-2">Produits commandés</h5>
                                <div class="divide-y divide-earth/5">
                                    <div v-for="produit in commande.produits" :key="produit.id" class="py-4 flex items-center gap-4 first:pt-0 last:pb-0">
                                        <div class="h-16 w-16 rounded-xl overflow-hidden bg-cream border border-earth/10 flex-shrink-0">
                                            <img :src="getProductImage(produit)" class="w-full h-full object-cover">
                                        </div>
                                        <div class="flex-grow">
                                            <h6 class="font-bold text-deep">{{ produit.nom }}</h6>
                                            <p class="text-xs text-muted_artika">Quantité : {{ produit.pivot.quantite }} · {{ formatPrix(produit.pivot.prix_unitaire) }} FCFA / unité</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-terr">{{ formatPrix(produit.pivot.quantite * produit.pivot.prix_unitaire) }} FCFA</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Infos client & Actions -->
                            <div class="space-y-6">
                                <div class="bg-cream/50 rounded-2xl p-6 border border-earth/5">
                                    <h5 class="text-[10px] font-bold text-gold uppercase tracking-[2px] mb-4">Informations de livraison</h5>
                                    <div class="space-y-3">
                                        <div class="flex items-start gap-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-terr mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="text-sm text-muted_artika leading-tight">{{ commande.adresse_livraison }}, {{ commande.ville_livraison }}</span>
                                        </div>
                                        <div class="flex items-start gap-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-terr mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            <span class="text-sm text-muted_artika font-bold">{{ commande.telephone_contact }}</span>
                                        </div>
                                    </div>
                                    
                                    <div v-if="commande.notes_client" class="mt-4 pt-4 border-t border-earth/10">
                                        <p class="text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-1">Notes :</p>
                                        <p class="text-xs italic text-muted_artika">"{{ commande.notes_client }}"</p>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <h5 class="text-[10px] font-bold text-deep uppercase tracking-[2px] mb-2">Modifier le statut</h5>
                                    <div class="grid grid-cols-2 gap-2">
                                        <button v-if="commande.statut === 'en_attente'" 
                                                @click="changerStatut(commande.id, 'confirmee')"
                                                :disabled="processing === commande.id"
                                                class="px-4 py-2 bg-gold text-white rounded-xl text-xs font-bold hover:bg-gold/90 transition disabled:opacity-50">
                                            Confirmer
                                        </button>
                                        <button v-if="commande.statut === 'confirmee'" 
                                                @click="changerStatut(commande.id, 'expediee')"
                                                :disabled="processing === commande.id"
                                                class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition disabled:opacity-50">
                                            Expédier
                                        </button>
                                        <button v-if="commande.statut === 'expediee'" 
                                                @click="changerStatut(commande.id, 'livree')"
                                                :disabled="processing === commande.id"
                                                class="px-4 py-2 bg-green_artika text-white rounded-xl text-xs font-bold hover:bg-green_artika/90 transition disabled:opacity-50">
                                            Marquer Livrée
                                        </button>
                                        <button v-if="['en_attente', 'confirmee'].includes(commande.statut)" 
                                                @click="changerStatut(commande.id, 'annulee')"
                                                :disabled="processing === commande.id"
                                                class="px-4 py-2 bg-white border border-red-200 text-red-600 rounded-xl text-xs font-bold hover:bg-red-50 transition disabled:opacity-50">
                                            Annuler
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer de la commande -->
                        <div class="bg-sand/5 px-8 py-4 border-t border-earth/5 flex justify-between items-center">
                            <span class="text-sm font-bold text-muted_artika">Total de la commande :</span>
                            <span class="font-serif text-2xl font-bold text-terr">{{ formatPrix(commande.montant_total) }} FCFA</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
