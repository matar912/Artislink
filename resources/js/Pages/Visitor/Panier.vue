<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { cart, removeFromCart, updateQuantity, cartTotal, cartCount } from '@/Stores/cart';
import FlashMessages from '@/Components/FlashMessages.vue';

const formatPrix = (prix: number) => {
    return new Intl.NumberFormat('fr-FR').format(prix);
};
</script>

<template>
    <Head title="Mon Panier — Artika" />

    <div class="min-h-screen bg-cream">
        <!-- Navigation Minimaliste -->
        <nav class="bg-white border-b border-earth/10 px-6 py-4 flex items-center justify-between sticky top-0 z-50">
            <Link :href="route('accueil')" class="font-serif text-2xl font-bold text-terr tracking-tighter">
                Artik<span class="text-gold">a</span>
            </Link>
            <Link :href="route('accueil')" class="text-xs font-bold text-muted_artika uppercase tracking-widest hover:text-terr transition-colors">
                ← Continuer mes achats
            </Link>
        </nav>

        <FlashMessages />

        <main class="container mx-auto px-6 py-12 max-w-5xl">
            <h1 class="font-serif text-4xl text-deep mb-10">Mon Panier</h1>

            <div v-if="cart.items.length > 0" class="grid lg:grid-cols-3 gap-12">
                <!-- Liste des produits -->
                <div class="lg:col-span-2 space-y-6">
                    <div v-for="item in cart.items" :key="item.id" class="bg-white rounded-2xl p-6 shadow-sm border border-earth/5 flex gap-6 group">
                        <div class="w-24 h-24 bg-sand/30 rounded-xl overflow-hidden flex-shrink-0">
                            <img v-if="item.image_principale" :src="'/storage/' + item.image_principale" class="w-full h-full object-cover" />
                            <div v-else class="w-full h-full flex items-center justify-center text-3xl">🏺</div>
                        </div>

                        <div class="flex-grow flex flex-col justify-between py-1">
                            <div>
                                <div class="flex justify-between items-start">
                                    <h3 class="font-bold text-deep">{{ item.nom }}</h3>
                                    <button @click="removeFromCart(item.id)" class="text-muted_artika hover:text-red-500 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-xs text-muted_artika mt-1">{{ item.artisan_nom }}</p>
                            </div>

                            <div class="flex justify-between items-center mt-4">
                                <div class="flex items-center gap-3 bg-sand/20 rounded-lg p-1">
                                    <button @click="updateQuantity(item.id, item.quantite - 1)" class="w-8 h-8 flex items-center justify-center font-bold text-deep hover:bg-white rounded-md transition-colors shadow-sm disabled:opacity-20" :disabled="item.quantite <= 1">-</button>
                                    <span class="text-sm font-bold w-6 text-center">{{ item.quantite }}</span>
                                    <button @click="updateQuantity(item.id, item.quantite + 1)" class="w-8 h-8 flex items-center justify-center font-bold text-deep hover:bg-white rounded-md transition-colors shadow-sm">+</button>
                                </div>
                                <span class="font-serif text-lg font-bold text-terr">{{ formatPrix(item.prix * item.quantite) }} <span class="text-[10px] font-sans text-muted_artika uppercase font-normal">FCFA</span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Récapitulatif -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-earth/5 sticky top-28">
                        <h2 class="font-serif text-xl text-deep mb-6">Récapitulatif</h2>
                        
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between text-sm text-muted_artika">
                                <span>Sous-total ({{ cartCount() }} articles)</span>
                                <span>{{ formatPrix(cartTotal()) }} FCFA</span>
                            </div>
                            <div class="flex justify-between text-sm text-muted_artika">
                                <span>Frais de livraison</span>
                                <span class="italic">Calculés à l'étape suivante</span>
                            </div>
                            <div class="pt-4 border-t border-earth/10 flex justify-between items-end">
                                <span class="font-bold text-deep uppercase text-xs tracking-widest">Total</span>
                                <span class="font-serif text-3xl font-bold text-terr">{{ formatPrix(cartTotal()) }} <span class="text-xs font-sans font-normal uppercase">FCFA</span></span>
                            </div>
                        </div>

                        <Link :href="route('visiteur.checkout')" class="block w-full btn-artika-gold py-4 text-center font-bold text-base shadow-lg shadow-gold/20">
                            Passer la commande →
                        </Link>

                        <p class="text-[10px] text-muted_artika text-center mt-6 uppercase tracking-wider leading-relaxed">
                            Paiement sécurisé · Livraison partout au Sénégal
                        </p>
                    </div>
                </div>
            </div>

            <!-- Panier Vide -->
            <div v-else class="bg-white rounded-3xl p-20 text-center border-2 border-dashed border-earth/10">
                <span class="text-7xl block mb-6">🛒</span>
                <h2 class="text-2xl font-bold text-deep mb-2">Votre panier est vide</h2>
                <p class="text-muted_artika mb-10 max-w-xs mx-auto">Vous n'avez pas encore ajouté de trésors artisanaux à votre panier.</p>
                <Link :href="route('accueil')" class="btn-artika-gold py-3 px-8 text-sm">
                    Découvrir les produits
                </Link>
            </div>
        </main>
    </div>
</template>
