<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import FlashMessages from '@/Components/FlashMessages.vue';
import { computed } from 'vue';
import { getProductImage } from '@/utils';

interface AppUser {
    id: number;
    nom: string;
    prenom: string;
    role: string;
    dashboard_route: string;
    avatar_url: string;
}

interface Artisan {
    id: number;
    user: AppUser;
    categorie: string;
    ville: string;
}

interface Produit {
    id: number;
    nom: string;
    prix: number | string;
    categorie_produit: string;
    image_principale?: string;
    artisan: Artisan;
}

const props = defineProps<{
    produits: Produit[];
    artisans: Artisan[];
    stats: {
        total_artisans: number;
        total_produits: number;
    };
}>();

const page = usePage();
const auth = computed(() => page.props.auth as any);

const categories = [
    { name: 'Poterie', icon: '🏺' },
    { name: 'Couture', icon: '🧵' },
    { name: 'Bijouterie', icon: '💍' },
    { name: 'Menuiserie', icon: '🪵' },
    { name: 'Peinture', icon: '🎨' },
    { name: 'Maroquinerie', icon: '👜' },
    { name: 'Gastronomie', icon: '🍯' },
];

const getCategoryIcon = (category: string) => {
    const cat = categories.find(c => c.name === category);
    return cat ? cat.icon : '📦';
};

const formatPrix = (prix: number | string) => {
    return new Intl.NumberFormat('fr-FR').format(Number(prix));
};
</script>

<template>
    <Head title="Artika — Artisanat Sénégalais" />

    <div class="min-h-screen bg-cream selection:bg-gold selection:text-deep">
        <!-- Navigation -->
        <nav class="sticky top-0 z-50 bg-cream/95 backdrop-blur-md border-b border-earth/20 px-6 md:px-12 py-4 flex items-center justify-between">
            <Link :href="route('accueil')" class="font-serif text-3xl font-bold text-terr tracking-tighter">
                Artik<span class="text-gold">a</span>
            </Link>

            <div class="hidden md:flex items-center gap-8">
                <Link :href="route('accueil')" class="text-sm font-medium text-muted_artika hover:text-terr transition-colors">Accueil</Link>
                <Link :href="auth.user ? auth.user.dashboard_route : route('login')" class="text-sm font-medium text-muted_artika hover:text-terr transition-colors">Boutique</Link>
                <Link :href="route('artisans.liste')" class="text-sm font-medium text-muted_artika hover:text-terr transition-colors">Artisans</Link>
            </div>

            <div class="flex items-center gap-3">
                <template v-if="auth.user">
                    <Link :href="auth.user.dashboard_route" class="btn-artika-outline py-2 px-4 text-xs">
                        Mon Espace
                    </Link>
                    <Link :href="route('deconnexion')" method="post" as="button" class="text-[10px] font-bold text-red-600 uppercase tracking-widest hover:underline ml-2">
                        Quitter
                    </Link>
                </template>
                <template v-else>
                    <Link :href="route('login')" class="btn-artika-outline py-2 px-4 text-xs">Connexion</Link>
                    <Link :href="route('inscription')" class="btn-artika-terr py-2 px-4 text-xs">S'inscrire</Link>
                </template>
            </div>
        </nav>

        <FlashMessages />

        <!-- Hero Section -->
        <section class="relative overflow-hidden bg-gradient-to-br from-deep via-[#3d1f00] to-[#5c2e00] min-h-[85vh] flex items-center">
            <div class="absolute inset-0 opacity-[0.06]" style="background-image: repeating-linear-gradient(45deg, #d4a017 0, #d4a017 1px, transparent 0, transparent 50%); background-size: 20px 20px;"></div>
            <div class="absolute right-0 top-0 bottom-0 w-[45%] hidden lg:block opacity-35 bg-[url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800')] bg-center bg-cover"></div>

            <div class="relative z-10 container mx-auto px-6 md:px-12 py-12 lg:py-24 max-w-7xl">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gold/20 text-gold text-xs font-bold uppercase tracking-[2px] mb-8">
                        ✦ Authentique & Sénégalais
                    </div>
                    
                    <h1 class="font-serif text-5xl md:text-7xl text-white leading-[1.1] mb-6 tracking-tight">
                        L'artisanat<br/>local,<br/><em class="text-gold italic">réinventé</em>
                    </h1>
                    
                    <p class="text-white/65 text-lg leading-relaxed mb-10 max-w-lg">
                        Découvrez des créations uniques directement auprès des artisans du Sénégal. Chaque objet raconte une histoire et soutient le savoir-faire de nos terroirs.
                    </p>

                    <div class="flex flex-wrap gap-4 mb-12">
                        <Link :href="auth.user ? auth.user.dashboard_route : route('login')" class="btn-artika-gold py-4 px-8 text-base font-bold">Découvrir les produits →</Link>
                        <Link :href="route('inscription.artisan')" class="btn-artika bg-white/10 border-white/20 text-white border-2 hover:bg-white/20 py-4 px-8 text-base font-semibold">Je suis artisan</Link>
                    </div>

                    <div class="flex gap-10 border-t border-white/10 pt-10">
                        <div class="text-center md:text-left">
                            <span class="font-serif text-3xl text-gold block">{{ stats.total_artisans }}</span>
                            <span class="text-[10px] text-white/50 uppercase tracking-widest font-bold">Artisans vérifiés</span>
                        </div>
                        <div class="text-center md:text-left">
                            <span class="font-serif text-3xl text-gold block">{{ stats.total_produits }}</span>
                            <span class="text-[10px] text-white/50 uppercase tracking-widest font-bold">Créations uniques</span>
                        </div>
                        <div class="text-center md:text-left">
                            <span class="font-serif text-3xl text-gold block">100%</span>
                            <span class="text-[10px] text-white/50 uppercase tracking-widest font-bold">Fait main</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Categories Section -->
        <section class="py-16 md:py-24 bg-cream">
            <div class="container mx-auto px-6 max-w-7xl">
                <div class="mb-12">
                    <h2 class="font-serif text-3xl md:text-4xl text-deep mb-2">Explorez par catégorie</h2>
                    <p class="text-muted_artika">Le meilleur du savoir-faire artisanal à portée de clic</p>
                </div>
                
                <div class="flex flex-wrap gap-3 md:gap-4">
                    <button v-for="cat in categories" :key="cat.name" class="flex items-center gap-2 bg-white border border-earth/20 rounded-full px-5 py-2.5 text-sm font-medium text-muted_artika hover:bg-terr hover:text-white hover:border-terr transition-all">
                        <span>{{ cat.icon }}</span>
                        {{ cat.name }}
                    </button>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-6 max-w-7xl">
                <div class="flex items-end justify-between mb-12">
                    <div>
                        <h2 class="font-serif text-3xl md:text-4xl text-deep mb-2">Aperçu des créations</h2>
                        <p class="text-muted_artika">Connectez-vous pour accéder à la boutique complète et commander</p>
                    </div>
                    <Link :href="route('login')" class="text-earth font-bold text-sm underline underline-offset-4 hover:text-terr transition-colors">
                        Voir tout →
                    </Link>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div v-for="produit in produits" :key="produit.id" class="card-artika group">
                        <div class="aspect-square bg-sand flex items-center justify-center text-6xl relative overflow-hidden">
                            <img :src="getProductImage(produit)" :alt="produit.nom" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="p-5">
                            <h3 class="font-bold text-deep mb-1 line-clamp-1">{{ produit.nom }}</h3>
                            <div class="flex items-center gap-2 mb-4">
                                <span class="badge-artika-verified">✓ Vérifié</span>
                                <span class="text-xs text-muted_artika">{{ produit.artisan.user.prenom }} {{ produit.artisan.user.nom }}</span>
                            </div>
                            <div class="flex items-center justify-between mt-auto">
                                <div class="font-serif text-xl font-bold text-terr">
                                    {{ formatPrix(produit.prix) }} <span class="font-sans text-[10px] text-muted_artika font-normal uppercase">FCFA</span>
                                </div>
                                <Link :href="route('login')" class="text-[10px] font-bold text-earth border border-earth px-3 py-1 rounded hover:bg-earth hover:text-white transition-all uppercase">
                                    Détails
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Artisans Featured -->
        <section class="py-16 md:py-24 bg-sand/30">
            <div class="container mx-auto px-6 max-w-7xl">
                <div class="flex items-end justify-between mb-12">
                    <div>
                        <h2 class="font-serif text-3xl md:text-4xl text-deep mb-2">Artisans en vedette</h2>
                        <p class="text-muted_artika">Rencontrez les créateurs derrière chaque objet</p>
                    </div>
                    <Link :href="route('login')" class="text-earth font-bold text-sm underline underline-offset-4 hover:text-terr transition-colors">
                        Voir tous →
                    </Link>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    <div v-for="artisan in artisans" :key="artisan.id" class="bg-white p-6 rounded-artika shadow-artika text-center hover:-translate-y-1 transition-transform cursor-pointer">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-earth to-gold mx-auto mb-4 flex items-center justify-center text-3xl text-white font-serif overflow-hidden">
                            <img v-if="artisan.user.avatar_url && !artisan.user.avatar_url.includes('ui-avatars.com')" :src="artisan.user.avatar_url" class="w-full h-full object-cover">
                            <span v-else>{{ artisan.user.prenom[0] }}</span>
                        </div>
                        <h4 class="font-bold text-deep mb-1">{{ artisan.user.prenom }} {{ artisan.user.nom }}</h4>
                        <p class="text-xs text-muted_artika mb-4">{{ artisan.categorie }} · {{ artisan.ville }}</p>
                        <span class="badge-artika-verified">✓ Artisan Vérifié</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Artisan Section -->
        <section class="py-16 md:py-24 bg-deep text-white overflow-hidden relative">
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: repeating-linear-gradient(45deg, #d4a017 0, #d4a017 1px, transparent 0, transparent 50%); background-size: 30px 30px;"></div>
            
            <div class="container mx-auto px-6 max-w-7xl relative z-10">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <div class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-gold/10 text-gold text-[10px] font-bold uppercase tracking-wider mb-8">
                            ✦ Rejoignez Artika
                        </div>
                        <h2 class="font-serif text-4xl md:text-5xl mb-6">Vous êtes artisan ? <br/> <span class="text-gold">Vendez vos créations</span></h2>
                        <p class="text-white/60 text-lg mb-10 leading-relaxed">
                            Rejoignez plus de 127 artisans vérifiés et touchez des milliers de clients locaux et internationaux. Valorisez votre savoir-faire et développez votre activité.
                        </p>
                        <Link :href="route('inscription.artisan')" class="btn-artika-gold py-4 px-10 text-base font-bold shadow-gold/20">
                            Créer mon espace artisan →
                        </Link>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                        <div class="text-center">
                            <div class="w-16 h-16 rounded-full bg-gold/20 border border-gold/30 flex items-center justify-center font-serif text-2xl text-gold mx-auto mb-4">1</div>
                            <h5 class="font-bold mb-2">Inscrivez-vous</h5>
                            <p class="text-xs text-white/40">Créez votre profil et décrivez votre atelier</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 rounded-full bg-gold/20 border border-gold/30 flex items-center justify-center font-serif text-2xl text-gold mx-auto mb-4">2</div>
                            <h5 class="font-bold mb-2">Vérification</h5>
                            <p class="text-xs text-white/40">L'équipe Artika valide votre identité</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 rounded-full bg-gold/20 border border-gold/30 flex items-center justify-center font-serif text-2xl text-gold mx-auto mb-4">3</div>
                            <h5 class="font-bold mb-2">Vendez !</h5>
                            <p class="text-xs text-white/40">Publiez vos créations et recevez des commandes</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer Simple -->
        <footer class="bg-white border-t border-earth/10 py-12">
            <div class="container mx-auto px-6 max-w-7xl">
                <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                    <Link :href="route('accueil')" class="font-serif text-2xl font-bold text-terr tracking-tighter">
                        Artik<span class="text-gold">a</span>
                    </Link>
                    <div class="flex gap-8 text-sm text-muted_artika">
                        <Link href="#" class="hover:text-terr transition-colors">CGU</Link>
                        <Link href="#" class="hover:text-terr transition-colors">Confidentialité</Link>
                        <Link href="#" class="hover:text-terr transition-colors">Contact</Link>
                    </div>
                    <div class="text-xs text-muted_artika">
                        © 2026 Artika. Tous droits réservés.
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
