<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps<{
    commandes: any[];
    auth: any;
}>();

onMounted(() => {
    if (window.Echo) {
        window.Echo.private(`user.${props.auth.user.id}`)
            .listen('CommandeStatutMisAJour', (e: any) => {
                router.reload({ only: ['commandes'] });
            });
    }
});

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
    <Head title="Mon Espace Personnel" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 py-2">
                <div>
                    <h2 class="font-serif text-3xl font-bold text-deep leading-tight">
                        Mon Espace <span class="text-gold italic">Personnel</span>
                    </h2>
                    <p class="text-muted_artika text-sm mt-1">Gérez vos commandes et retrouvez vos artisans favoris.</p>
                </div>
                <Link :href="route('artisans.liste')" class="btn-artika-gold py-3 px-6 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                    Découvrir des Artisans
                </Link>
            </div>
        </template>

        <div class="py-12 bg-cream min-h-[calc(100vh-64px)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                    
                    <!-- Colonne Gauche: Commandes -->
                    <div class="lg:col-span-2 space-y-8">
                        <div class="bg-white rounded-[2rem] shadow-artika border border-earth/10 overflow-hidden">
                            <div class="p-8 border-b border-earth/5 flex justify-between items-center bg-sand/20">
                                <h3 class="font-serif text-xl text-deep font-bold">
                                    Mes <span class="text-gold italic">Commandes</span>
                                </h3>
                                <Link :href="route('visiteur.commandes.liste')" class="text-earth text-xs font-bold uppercase tracking-widest hover:text-terr transition">Historique Complet</Link>
                            </div>

                            <div class="p-0">
                                <div v-if="commandes.length === 0" class="flex flex-col items-center justify-center py-24 text-center">
                                    <div class="h-20 w-20 bg-sand/30 rounded-full flex items-center justify-center mb-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gold/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </div>
                                    <h4 class="font-serif text-xl font-bold text-deep mb-2">Aucune commande en cours</h4>
                                    <p class="text-muted_artika max-w-xs mx-auto mb-8 text-sm">C'est le moment idéal pour découvrir le talent de nos artisans locaux !</p>
                                    <Link :href="route('artisans.liste')" class="btn-artika-gold py-3 px-8">
                                        Commencer mon shopping
                                    </Link>
                                </div>

                                <div v-else class="divide-y divide-earth/5">
                                    <div v-for="commande in commandes" :key="commande.id" class="p-8 hover:bg-sand/10 transition-colors group">
                                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                            <div class="flex items-center gap-6">
                                                <div class="h-16 w-16 bg-cream border border-earth/10 rounded-2xl flex items-center justify-center text-gold shadow-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="flex items-center gap-3 mb-1">
                                                        <span class="font-bold text-deep text-lg">Commande #{{ commande.id }}</span>
                                                        <span :class="getStatusClass(commande.statut)" class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border">
                                                            {{ getStatusLabel(commande.statut) }}
                                                        </span>
                                                    </div>
                                                    <p class="text-xs text-muted_artika">Le {{ new Date(commande.created_at).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' }) }}</p>
                                                </div>
                                            </div>
                                            <div class="flex flex-row md:flex-col justify-between items-center md:items-end gap-2">
                                                <div class="font-serif text-2xl font-bold text-terr">
                                                    {{ formatPrix(commande.montant_total) }} <span class="font-sans text-[10px] text-muted_artika font-normal">FCFA</span>
                                                </div>
                                                <Link :href="route('visiteur.commandes.liste')" class="text-[10px] font-bold text-earth border border-earth/30 px-4 py-1.5 rounded-lg hover:bg-earth hover:text-white transition-all uppercase tracking-wider">
                                                    Détails
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne Droite: Sidebar -->
                    <div class="space-y-8">
                        <!-- Profil Client Sidebar -->
                        <div v-if="auth && auth.user" class="bg-white rounded-[2rem] p-8 border border-earth/10 shadow-artika">
                            <div class="flex items-center gap-5">
                                <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-gold to-terr flex items-center justify-center text-white text-3xl font-serif shadow-lg overflow-hidden border-2 border-white">
                                    <img v-if="auth.user.avatar_url && !auth.user.avatar_url.includes('ui-avatars.com')" :src="auth.user.avatar_url" class="w-full h-full object-cover">
                                    <span v-else>{{ auth.user.prenom ? auth.user.prenom[0] : 'C' }}</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gold uppercase tracking-[2px] mb-1">Client Privilégié</p>
                                    <h4 class="text-xl font-serif font-bold text-deep leading-tight">{{ auth.user.prenom }} {{ auth.user.nom }}</h4>
                                    <p class="text-xs text-muted_artika mt-1">{{ auth.user.email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card Invitation -->
                        <div class="bg-deep rounded-[2rem] p-8 text-white relative overflow-hidden shadow-2xl border border-gold/20">
                            <div class="relative z-10">
                                <span class="px-3 py-1 bg-gold/20 text-gold rounded-full text-[10px] font-bold uppercase tracking-widest mb-4 inline-block border border-gold/30">✦ Artika Club</span>
                                <h3 class="font-serif text-2xl font-bold leading-tight mb-4">Soutenez le talent <span class="text-gold italic">local</span></h3>
                                <p class="text-white/60 text-sm mb-8 leading-relaxed">Chaque achat chez un artisan contribue à préserver un savoir-faire unique au Sénégal.</p>
                                <Link :href="route('artisans.liste')" class="btn-artika-gold w-full py-4 text-center">
                                    Explorer maintenant
                                </Link>
                            </div>
                            <!-- Décoration fond -->
                            <div class="absolute -top-10 -right-10 w-48 h-48 bg-gold/10 rounded-full blur-3xl"></div>
                            <div class="absolute -bottom-10 -left-10 w-48 h-48 bg-terr/10 rounded-full blur-3xl"></div>
                        </div>

                        <!-- Accès Rapides -->
                        <div class="bg-white rounded-[2rem] p-8 border border-earth/10 shadow-artika">
                            <h4 class="font-serif text-lg text-deep font-bold mb-6">Accès <span class="text-gold italic">Rapide</span></h4>
                            <div class="space-y-4">
                                <Link :href="route('visiteur.favoris.liste')" class="flex items-center gap-4 p-4 rounded-2xl bg-sand/20 hover:bg-sand/40 transition group border border-transparent hover:border-earth/10">
                                    <div class="h-10 w-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-gold group-hover:scale-110 transition border border-earth/5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <span class="font-bold text-muted_artika text-sm group-hover:text-deep transition">Mes artisans favoris</span>
                                </Link>
                                <Link :href="route('visiteur.messages.liste')" class="flex items-center gap-4 p-4 rounded-2xl bg-sand/20 hover:bg-sand/40 transition group border border-transparent hover:border-earth/10">
                                    <div class="h-10 w-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-terr group-hover:scale-110 transition border border-earth/5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                                            <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                                        </svg>
                                    </div>
                                    <span class="font-bold text-muted_artika text-sm group-hover:text-deep transition">Mes conversations</span>
                                </Link>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

