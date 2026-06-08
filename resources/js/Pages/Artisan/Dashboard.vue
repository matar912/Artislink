<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps<{
    artisan: any;
    commandes: any[];
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

const getStatusClass = (status: string) => {
    switch (status) {
        case 'confirmee': return 'bg-green_artika/10 text-green_artika';
        case 'expediee': return 'bg-gold/15 text-gold';
        case 'livree': return 'bg-green_artika/20 text-green_artika';
        case 'annulee': return 'bg-red-100 text-red-700';
        default: return 'bg-sand text-muted_artika';
    }
};
</script>

<template>
    <Head title="Tableau de Bord — Artika" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="font-serif text-3xl text-deep">Bonjour, {{ artisan.user.prenom }} 👋</h2>
                    <p class="text-muted_artika text-sm mt-1">Voici l'état de votre boutique aujourd'hui.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden md:flex items-center gap-2 px-4 py-2 bg-green_artika/10 rounded-lg border border-green_artika/20">
                        <span class="badge-artika-verified">✓ Vérifié</span>
                        <span class="text-xs font-bold text-green_artika uppercase tracking-wider">Boutique Active</span>
                    </div>
                    <Link :href="route('artisan.produits.formulaire-creation')" class="btn-artika-gold py-2.5 px-5">
                        + Nouveau produit
                    </Link>
                </div>
            </div>
        </template>

        <div class="space-y-10">
            <!-- KPI Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-artika shadow-artika border-l-4 border-gold">
                    <p class="font-serif text-3xl font-bold text-deep">{{ artisan.note_moyenne || '0.0' }}</p>
                    <p class="text-[10px] font-bold text-muted_artika uppercase tracking-widest mt-1">Note Moyenne</p>
                </div>

                <div class="bg-white p-6 rounded-artika shadow-artika border-l-4 border-green_artika">
                    <p class="font-serif text-3xl font-bold text-deep">{{ commandes.length }}</p>
                    <p class="text-[10px] font-bold text-muted_artika uppercase tracking-widest mt-1">Commandes</p>
                </div>

                <div class="bg-white p-6 rounded-artika shadow-artika border-l-4 border-earth">
                    <p class="font-serif text-3xl font-bold text-deep">
                        {{ artisan.nombre_avis || 0 }}
                    </p>
                    <p class="text-[10px] font-bold text-muted_artika uppercase tracking-widest mt-1">Avis Clients</p>
                </div>

                <div class="bg-white p-6 rounded-artika shadow-artika border-l-4 border-terr">
                    <p class="font-serif text-3xl font-bold text-deep">14</p>
                    <p class="text-[10px] font-bold text-muted_artika uppercase tracking-widest mt-1">Produits Actifs</p>
                </div>
            </div>

            <!-- Orders Table Section -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-serif text-2xl text-deep">Commandes récentes</h3>
                    <Link :href="route('artisan.commandes.liste')" class="text-xs font-bold text-earth uppercase tracking-widest hover:underline">Voir tout →</Link>
                </div>

                <div class="bg-white rounded-artika shadow-artika overflow-hidden">
                    <div v-if="commandes.length === 0" class="p-20 text-center">
                        <span class="text-5xl block mb-4">🛒</span>
                        <p class="text-muted_artika font-medium">Vous n'avez pas encore reçu de commande.</p>
                    </div>
                    <div v-else class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-sand/50 text-[10px] font-bold text-muted_artika uppercase tracking-widest">
                                    <th class="px-6 py-4">Client</th>
                                    <th class="px-6 py-4">Produits</th>
                                    <th class="px-6 py-4">Montant</th>
                                    <th class="px-6 py-4 text-center">Statut</th>
                                    <th class="px-6 py-4 text-right">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-earth/10">
                                <tr v-for="commande in commandes" :key="commande.id" class="hover:bg-sand/20 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-earth/10 flex items-center justify-center font-bold text-earth text-xs">
                                                {{ commande.visiteur.user.prenom[0] }}
                                            </div>
                                            <span class="text-sm font-bold text-deep">{{ commande.visiteur.user.name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs text-muted_artika">
                                            {{ commande.produits.length }} article(s)
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-bold text-terr">{{ Number(commande.montant_total).toLocaleString() }} <span class="text-[10px] font-normal uppercase">FCFA</span></span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span :class="getStatusClass(commande.statut)" class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                            {{ commande.statut }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-xs text-muted_artika font-medium">{{ new Date(commande.created_at).toLocaleDateString() }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Actions / Support -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-deep rounded-artika p-8 text-white relative overflow-hidden group">
                    <div class="relative z-10">
                        <h4 class="font-serif text-2xl mb-2">Besoin d'aide ?</h4>
                        <p class="text-white/50 text-sm mb-6 max-w-xs">Consultez notre guide de l'artisan pour booster vos ventes sur Artika.</p>
                        <a href="#" class="inline-flex items-center gap-2 text-gold font-bold text-xs uppercase tracking-widest group-hover:translate-x-1 transition-transform">
                            Lire le guide →
                        </a>
                    </div>
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-gold/5 rounded-full blur-3xl group-hover:bg-gold/10 transition-colors"></div>
                </div>

                <div class="bg-white rounded-artika p-8 border border-earth/10">
                    <h4 class="font-serif text-2xl text-deep mb-4">Statut de l'atelier</h4>
                    <div class="flex items-center justify-between p-4 bg-sand/30 rounded-xl">
                        <div>
                            <p class="text-sm font-bold text-deep">Visibilité publique</p>
                            <p class="text-xs text-muted_artika">Votre profil est actuellement en ligne</p>
                        </div>
                        <div class="relative inline-flex items-center cursor-pointer">
                            <div class="w-11 h-6 bg-green_artika rounded-full"></div>
                            <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform translate-x-5"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
