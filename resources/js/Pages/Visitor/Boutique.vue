<script setup lang="ts">
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import FlashMessages from '@/Components/FlashMessages.vue';
import { ref, computed, watch } from 'vue';
import { addToCart, cartCount } from '@/Stores/cart';
import debounce from 'lodash/debounce';

const props = defineProps<{
    produits: any;
    filtres: {
        recherche?: string;
        categorie?: string;
    };
    favorisIds: number[];
}>();

const rapideSearch = ref(props.filtres.recherche || '');
const categorieActive = ref(props.filtres.categorie || '');

const page = usePage();
const auth = computed(() => page.props.auth as { user: any });

const categories = [
    { name: 'Poterie', icon: '🏺' },
    { name: 'Couture', icon: '🧵' },
    { name: 'Bijouterie', icon: '💍' },
    { name: 'Menuiserie', icon: '🪵' },
    { name: 'Peinture', icon: '🎨' },
    { name: 'Maroquinerie', icon: '👜' },
    { name: 'Gastronomie', icon: '🍯' },
];

const getCategoryPlaceholder = (category: string) => {
    const images: Record<string, string> = {
        'Poterie': 'https://images.unsplash.com/photo-1595111051515-56885368a5c3?w=500',
        'Couture': 'https://images.unsplash.com/photo-1544441893-675973e31985?w=500',
        'Bijouterie': 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=500',
        'Menuiserie': 'https://images.unsplash.com/photo-1533090161767-e6ffed986c88?w=500',
        'Peinture': 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=500',
        'Maroquinerie': 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=500',
        'Gastronomie': 'https://images.unsplash.com/photo-1559181567-c3190ca9959b?w=500',
    };
    return images[category] || 'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?w=500';
};

const getProductImage = (produit: any) => {
    if (!produit.image_principale) return getCategoryPlaceholder(produit.categorie_produit);
    return produit.image_principale.startsWith('http') 
        ? produit.image_principale 
        : '/storage/' + produit.image_principale;
};

const formatPrix = (prix: any) => {
    try {
        return new Intl.NumberFormat('fr-FR').format(Number(prix) || 0);
    } catch (e) {
        return prix;
    }
};

const filter = () => {
    router.get(route('visiteur.boutique'), {
        recherche: rapideSearch.value,
        categorie: categorieActive.value
    }, {
        preserveState: true,
        replace: true
    });
};

watch(rapideSearch, debounce(() => {
    filter();
}, 500));

const setCategory = (cat: string) => {
    categorieActive.value = categorieActive.value === cat ? '' : cat;
    filter();
};

// Gestion des favoris réelle
const toggleFavori = (artisanId: number) => {
    router.post(route('visiteur.favoris.basculer', artisanId), {}, {
        preserveScroll: true,
    });
};
const isFavori = (artisanId: number) => props.favorisIds.includes(artisanId);

</script>

<template>
    <Head title="Artika — Votre Boutique" />

    <div class="min-h-screen bg-[#FDFBF7]">
        <!-- Navigation Client Personnalisée -->
        <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-earth/10 px-6 md:px-12 py-4 flex items-center justify-between">
            <div class="flex items-center gap-12">
                <Link :href="route('accueil')" class="font-serif text-3xl font-bold text-terr tracking-tighter">
                    Artik<span class="text-gold">a</span>
                </Link>

                <div class="hidden lg:flex items-center gap-6">
                    <Link :href="route('visiteur.boutique')" class="text-sm font-bold text-deep px-4 py-2 bg-gold/10 rounded-xl">Boutique</Link>
                    <Link :href="route('visiteur.tableau-bord')" class="text-sm font-medium text-muted_artika hover:text-deep transition-colors px-4 py-2">Mes Commandes</Link>
                    <Link :href="route('visiteur.favoris.liste')" class="text-sm font-medium text-muted_artika hover:text-deep transition-colors px-4 py-2">Mes Favoris</Link>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <!-- Panier -->
                <Link :href="route('visiteur.panier')" class="relative p-2 text-deep hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span v-if="cartCount() > 0" class="absolute top-0 right-0 bg-terr text-white text-[10px] font-black px-1.5 py-0.5 rounded-full border-2 border-white min-w-[20px] text-center">
                        {{ cartCount() }}
                    </span>
                </Link>

                <!-- Profil Client -->
                <div v-if="auth.user" class="flex items-center gap-3 pl-6 border-l border-earth/10">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-black text-deep uppercase tracking-tighter">Bienvenue,</p>
                        <p class="text-sm font-bold text-terr">{{ auth.user.prenom || 'Client' }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-gold to-terr flex items-center justify-center text-white font-black shadow-lg shadow-gold/20 ring-2 ring-white overflow-hidden">
                        <img v-if="auth.user.avatar_url" :src="auth.user.avatar_url" class="w-full h-full object-cover">
                        <span v-else>{{ auth.user.prenom ? auth.user.prenom[0] : 'C' }}</span>
                    </div>
                </div>
                
                <Link :href="route('deconnexion')" method="post" as="button" class="p-2 text-muted_artika hover:text-red-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </Link>
            </div>
        </nav>

        <FlashMessages />

        <!-- Header de Bienvenue -->
        <header class="bg-white py-12 px-6 border-b border-earth/5">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-end justify-between gap-8">
                <div>
                    <h1 class="font-serif text-4xl text-deep mb-2">Bonjour <span class="text-terr">{{ auth.user.prenom }}</span>,</h1>
                    <p class="text-muted_artika text-lg">Prêt à dénicher de nouvelles merveilles artisanales ?</p>
                </div>

                <!-- Barre de recherche stylisée -->
                <div class="relative w-full md:max-w-md">
                    <input 
                        v-model="rapideSearch"
                        type="text" 
                        placeholder="Rechercher par produit ou artisan..." 
                        class="w-full bg-sand/20 border-2 border-transparent focus:border-gold rounded-2xl py-4 pl-12 pr-4 text-deep outline-none transition-all placeholder:text-muted_artika/60"
                    />
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xl opacity-40">🔍</span>
                </div>
            </div>
        </header>

        <!-- Filtres Catégories -->
        <div class="sticky top-[80px] z-40 bg-[#FDFBF7]/95 backdrop-blur-md py-6 border-b border-earth/5">
            <div class="max-w-7xl mx-auto px-6 overflow-x-auto flex gap-4 no-scrollbar">
                <button 
                    @click="setCategory('')"
                    :class="[
                        'px-6 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-all border-2',
                        categorieActive === '' ? 'bg-deep border-deep text-white shadow-xl' : 'bg-white border-earth/10 text-muted_artika hover:border-gold'
                    ]"
                >
                    Tous les produits
                </button>
                <button 
                    v-for="cat in categories" 
                    :key="cat.name"
                    @click="setCategory(cat.name)"
                    :class="[
                        'flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-all border-2',
                        categorieActive === cat.name ? 'bg-terr border-terr text-white shadow-xl' : 'bg-white border-earth/10 text-muted_artika hover:border-gold'
                    ]"
                >
                    <span>{{ cat.icon }}</span> {{ cat.name }}
                </button>
            </div>
        </div>

        <!-- Grille de Produits -->
        <main class="max-w-7xl mx-auto px-6 py-12">
            <div v-if="produits.data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <div v-for="produit in produits.data" :key="produit.id" class="group bg-white rounded-[2rem] overflow-hidden border border-earth/5 hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 relative">
                    <!-- Bouton Favori Rapide -->
                    <button 
                        @click="toggleFavori(produit.id)"
                        class="absolute top-4 right-4 z-10 w-10 h-10 rounded-full flex items-center justify-center transition-all shadow-lg active:scale-90"
                        :class="isFavori(produit.id) ? 'bg-red-500 text-white' : 'bg-white/90 text-deep hover:text-red-500'"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" :fill="isFavori(produit.id) ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>

                    <!-- Image -->
                    <div class="aspect-[4/5] bg-sand/10 relative overflow-hidden">
                        <img 
                            :src="getProductImage(produit)" 
                            :alt="produit.nom"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                        />
                        
                        <!-- Badge Artisan -->
                        <div class="absolute bottom-4 left-4 right-4 bg-white/10 backdrop-blur-md border border-white/20 p-3 rounded-2xl transform translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                            <p class="text-[10px] font-black text-white uppercase tracking-widest mb-0.5">Atelier de</p>
                            <p class="text-sm font-bold text-white truncate">{{ produit.artisan?.user?.name }}</p>
                        </div>
                    </div>

                    <!-- Infos -->
                    <div class="p-6">
                        <span class="text-[10px] font-black text-gold uppercase tracking-[0.2em] mb-2 block">{{ produit.categorie_produit || 'Artisanat' }}</span>
                        <h3 class="font-bold text-deep text-lg mb-1 truncate">{{ produit.nom }}</h3>
                        <p class="text-xs text-muted_artika mb-6 line-clamp-1">📍 {{ produit.artisan?.ville }}, Sénégal</p>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-earth/5">
                            <p class="font-serif text-2xl font-bold text-terr">
                                {{ formatPrix(produit.prix) }} <span class="text-xs font-sans font-normal text-muted_artika uppercase">FCFA</span>
                            </p>
                            
                            <button 
                                @click="addToCart(produit)"
                                class="bg-deep text-white px-6 py-3 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-terr transition-colors active:scale-95 shadow-lg shadow-deep/10"
                            >
                                Acheter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pas de résultats -->
            <div v-else class="py-32 text-center">
                <div class="w-24 h-24 bg-sand/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-5xl">🔭</span>
                </div>
                <h3 class="text-2xl font-serif text-deep mb-2">Aucun trésor trouvé</h3>
                <p class="text-muted_artika">Essayez de modifier votre recherche ou vos filtres.</p>
            </div>

            <!-- Pagination Stylisée -->
            <div v-if="produits.links.length > 3" class="flex justify-center gap-2 pt-20">
                <Link 
                    v-for="link in produits.links" 
                    :key="link.label"
                    :href="link.url || '#'"
                    v-html="link.label"
                    :class="[
                        'px-5 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all',
                        link.active ? 'bg-gold text-deep shadow-xl shadow-gold/20' : 'bg-white text-muted_artika hover:bg-sand/30',
                        !link.url ? 'opacity-30 cursor-not-allowed' : ''
                    ]"
                />
            </div>
        </main>

        <!-- Footer épuré -->
        <footer class="bg-white py-12 border-t border-earth/5 mt-20">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <p class="font-serif text-2xl text-deep mb-4">Artika</p>
                <p class="text-xs text-muted_artika uppercase tracking-[0.2em]">Soutenir l'authenticité sénégalaise</p>
            </div>
        </footer>
    </div>
</template>

<style>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
