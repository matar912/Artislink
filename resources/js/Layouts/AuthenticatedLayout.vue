<script setup lang="ts">
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import FlashMessages from '@/Components/FlashMessages.vue';
import { cartCount } from '@/Stores/cart';

const page = usePage();
const auth = page.props.auth as { user: any };

const showingMobileMenu = ref(false);

const navItems = {
    artisan: [
        { name: 'Tableau de bord', route: 'artisan.tableau-bord', icon: '⊞', active: 'artisan.tableau-bord' },
        { name: 'Mes produits', route: 'artisan.produits.liste', icon: '📦', active: 'artisan.produits.*' },
        { name: 'Commandes', route: 'artisan.commandes.liste', icon: '🛒', active: 'artisan.commandes.*' },
        { name: 'Mon Profil', route: 'artisan.profil.formulaire', icon: '👤', active: 'artisan.profil.*' },
    ],
    admin: [
        { name: 'Tableau de bord', route: 'admin.tableau-bord', icon: '⊞', active: 'admin.tableau-bord' },
        { name: 'Utilisateurs', route: 'admin.utilisateurs.liste', icon: '👥', active: 'admin.utilisateurs.*' },
    ],
    visiteur: [
        { name: 'Boutique', route: 'visiteur.boutique', icon: '🛍️', active: 'visiteur.boutique' },
        { name: 'Mes commandes', route: 'visiteur.tableau-bord', icon: '🛒', active: 'visiteur.*' },
        { name: 'Mes favoris', route: 'visiteur.favoris.liste', icon: '❤️', active: 'visiteur.favoris.*' },
    ]
};

const currentNav = navItems[auth.user.role as keyof typeof navItems] || [];
</script>

<template>
    <div class="min-h-screen bg-cream flex flex-col md:flex-row">
        <!-- Sidebar -->
        <aside :class="[showingMobileMenu ? 'translate-x-0' : '-translate-x-full md:translate-x-0']" class="fixed inset-y-0 left-0 z-50 w-64 bg-deep text-white transition-transform duration-300 md:relative md:flex flex-col">
            <div class="p-6">
                <Link :href="route('accueil')" class="font-serif text-2xl text-gold tracking-tighter">Artika</Link>
            </div>

            <nav class="flex-grow px-4 space-y-1">
                <Link 
                    v-for="item in currentNav" 
                    :key="item.name"
                    :href="route(item.route)"
                    :class="[
                        'flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all',
                        route().current(item.active) ? 'bg-gold/15 text-gold' : 'text-white/60 hover:bg-white/5 hover:text-white'
                    ]"
                >
                    <span class="text-lg w-6 text-center">{{ item.icon }}</span>
                    {{ item.name }}
                </Link>
            </nav>

            <div class="p-4 border-t border-white/10 mt-auto">
                <div class="flex items-center gap-3 px-4 py-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-gold/20 flex items-center justify-center font-bold text-gold uppercase overflow-hidden ring-2 ring-white/10">
                        <img v-if="auth.user.avatar_url" :src="auth.user.avatar_url" class="w-full h-full object-cover">
                        <span v-else>{{ auth.user.prenom[0] }}{{ auth.user.nom[0] }}</span>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-bold truncate">{{ auth.user.name }}</p>
                        <p class="text-[10px] text-white/40 uppercase tracking-widest">{{ auth.user.role }}</p>
                    </div>
                </div>
                
                <Link :href="route('deconnexion')" method="post" as="button" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-red-400 hover:bg-red-400/10 transition-all">
                    <span class="text-lg w-6 text-center">🚪</span> Déconnexion
                </Link>
            </div>
        </aside>

        <!-- Mobile Header -->
        <header class="md:hidden bg-deep p-4 flex items-center justify-between sticky top-0 z-40 border-b border-white/10">
            <Link :href="route('accueil')" class="font-serif text-xl text-gold tracking-tighter">Artika</Link>
            <button @click="showingMobileMenu = !showingMobileMenu" class="text-white">
                <svg v-if="!showingMobileMenu" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </header>

        <!-- Main Content -->
        <main class="flex-grow flex flex-col min-w-0">
            <FlashMessages />
            
            <header v-if="$slots.header" class="bg-cream border-b border-earth/10 px-6 py-8 md:px-12">
                <div class="max-w-6xl flex justify-between items-center">
                    <div>
                        <slot name="header" />
                    </div>
                    
                    <!-- Panier Rapide (Uniquement pour les Visiteurs) -->
                    <Link v-if="auth.user.role === 'visiteur'" :href="route('visiteur.panier')" class="relative p-3 bg-white rounded-xl shadow-sm border border-earth/10 text-deep hover:text-terr transition-all shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span v-if="cartCount() > 0" class="absolute -top-1 -right-1 bg-gold text-deep text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white">
                            {{ cartCount() }}
                        </span>
                    </Link>
                </div>
            </header>

            <div class="p-6 md:p-12 overflow-y-auto max-w-7xl">
                <slot />
            </div>
        </main>

        <!-- Mobile Overlay -->
        <div v-if="showingMobileMenu" @click="showingMobileMenu = false" class="fixed inset-0 bg-deep/60 backdrop-blur-sm z-40 md:hidden"></div>
    </div>
</template>
