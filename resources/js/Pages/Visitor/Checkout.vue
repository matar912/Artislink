<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { cart, cartTotal, cartCount, clearCart } from '@/Stores/cart';
import FlashMessages from '@/Components/FlashMessages.vue';
import { onMounted } from 'vue';

const form = useForm({
    produits: cart.items.map(item => ({
        id: item.id,
        quantite: item.quantite
    })),
    adresse_livraison: '',
    ville_livraison: '',
    telephone_contact: '',
    notes_client: '',
});

const submit = () => {
    form.post(route('visiteur.commandes.creer'), {
        onSuccess: () => {
            clearCart();
        },
    });
};

const formatPrix = (prix: number) => {
    return new Intl.NumberFormat('fr-FR').format(prix);
};

onMounted(() => {
    if (cart.items.length === 0) {
        // Rediriger vers le panier si vide
        window.location.href = route('visiteur.panier');
    }
});
</script>

<template>
    <Head title="Validation de commande — Artika" />

    <div class="min-h-screen bg-cream">
        <nav class="bg-white border-b border-earth/10 px-6 py-4 flex items-center justify-between sticky top-0 z-50">
            <Link :href="route('accueil')" class="font-serif text-2xl font-bold text-terr tracking-tighter">
                Artik<span class="text-gold">a</span>
            </Link>
            <Link :href="route('visiteur.panier')" class="text-xs font-bold text-muted_artika uppercase tracking-widest hover:text-terr transition-colors">
                ← Retour au panier
            </Link>
        </nav>

        <FlashMessages />

        <main class="container mx-auto px-6 py-12 max-w-5xl">
            <h1 class="font-serif text-4xl text-deep mb-10">Finaliser ma commande</h1>

            <form @submit.prevent="submit" class="grid lg:grid-cols-3 gap-12">
                <!-- Informations de livraison -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-earth/5">
                        <h2 class="font-serif text-xl text-deep mb-8 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-sand flex items-center justify-center text-xs">01</span>
                            Informations de livraison
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Adresse complète</label>
                                <input v-model="form.adresse_livraison" type="text" class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors" placeholder="Quartier, Rue, Immeuble..." required />
                                <div v-if="form.errors.adresse_livraison" class="text-red-500 text-xs mt-1">{{ form.errors.adresse_livraison }}</div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Ville</label>
                                <input v-model="form.ville_livraison" type="text" class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors" placeholder="Dakar, Saint-Louis..." required />
                                <div v-if="form.errors.ville_livraison" class="text-red-500 text-xs mt-1">{{ form.errors.ville_livraison }}</div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Téléphone de contact</label>
                                <input v-model="form.telephone_contact" type="tel" class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors" placeholder="7x xxx xx xx" required />
                                <div v-if="form.errors.telephone_contact" class="text-red-500 text-xs mt-1">{{ form.errors.telephone_contact }}</div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Notes pour le livreur (Optionnel)</label>
                                <textarea v-model="form.notes_client" rows="3" class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors" placeholder="Indications particulières..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-earth/5">
                        <h2 class="font-serif text-xl text-deep mb-8 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-sand flex items-center justify-center text-xs">02</span>
                            Méthode de paiement
                        </h2>
                        
                        <div class="p-4 bg-sand/10 border-2 border-gold/30 rounded-xl flex items-center gap-4">
                            <div class="w-10 h-10 bg-gold rounded-full flex items-center justify-center text-deep text-lg">💵</div>
                            <div>
                                <p class="font-bold text-deep text-sm">Paiement à la livraison (Cash on Delivery)</p>
                                <p class="text-[10px] text-muted_artika uppercase tracking-wider">Payez en espèces lorsque vous recevez vos produits</p>
                            </div>
                        </div>
                        <p class="text-[10px] text-muted_artika mt-4 italic">Les paiements par Orange Money et Wave seront bientôt disponibles.</p>
                    </div>
                </div>

                <!-- Récapitulatif et Validation -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-earth/5">
                        <h2 class="font-serif text-xl text-deep mb-6">Ma Commande</h2>
                        
                        <div class="max-h-60 overflow-y-auto mb-6 pr-2 space-y-4">
                            <div v-for="item in cart.items" :key="item.id" class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-sand/30 rounded-lg overflow-hidden flex-shrink-0">
                                    <img v-if="item.image_principale" :src="'/storage/' + item.image_principale" class="w-full h-full object-cover" />
                                    <div v-else class="w-full h-full flex items-center justify-center text-xl">🏺</div>
                                </div>
                                <div class="flex-grow min-w-0">
                                    <p class="text-xs font-bold text-deep truncate">{{ item.nom }}</p>
                                    <p class="text-[10px] text-muted_artika">Qté: {{ item.quantite }} · {{ formatPrix(item.prix) }} FCFA</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3 mb-8 pt-6 border-t border-earth/10">
                            <div class="flex justify-between text-sm text-muted_artika">
                                <span>Sous-total</span>
                                <span>{{ formatPrix(cartTotal()) }} FCFA</span>
                            </div>
                            <div class="flex justify-between text-sm text-muted_artika">
                                <span>Livraison</span>
                                <span class="font-bold text-green-600">Gratuite</span>
                            </div>
                            <div class="pt-4 flex justify-between items-end">
                                <span class="font-bold text-deep uppercase text-xs tracking-widest">Total à payer</span>
                                <span class="font-serif text-2xl font-bold text-terr">{{ formatPrix(cartTotal()) }} <span class="text-xs font-sans font-normal uppercase">FCFA</span></span>
                            </div>
                        </div>

                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="w-full bg-deep text-white py-4 rounded-xl font-bold text-base hover:bg-black transition-all shadow-xl disabled:opacity-50"
                        >
                            {{ form.processing ? 'Traitement...' : 'Confirmer ma commande' }}
                        </button>
                    </div>
                </div>
            </form>
        </main>
    </div>
</template>
