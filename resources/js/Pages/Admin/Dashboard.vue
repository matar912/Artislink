<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    stats: {
        total_utilisateurs: number;
        total_artisans: number;
        total_produits: number;
        total_commandes: number;
        derniers_utilisateurs: Array<any>;
        utilisateurs_en_attente?: Array<any>;
    }
}>();
</script>

<template>
    <Head title="Administration — Artika" />

    <AuthenticatedLayout>
        <template #header>
            <div class="mb-2">
                <h2 class="font-serif text-3xl text-deep tracking-tight">Tableau de bord Admin</h2>
                <p class="text-muted_artika text-sm mt-1">Gestion globale de la plateforme Artika.</p>
            </div>
        </template>

        <div class="space-y-10">
            <!-- KPI Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-artika shadow-artika border-l-4 border-gold">
                    <p class="font-serif text-3xl font-bold text-deep">{{ stats.total_artisans }}</p>
                    <p class="text-[10px] font-bold text-muted_artika uppercase tracking-widest mt-1">Artisans vérifiés</p>
                </div>

                <div class="bg-white p-6 rounded-artika shadow-artika border-l-4 border-earth">
                    <p class="font-serif text-3xl font-bold text-deep">{{ stats.total_produits }}</p>
                    <p class="text-[10px] font-bold text-muted_artika uppercase tracking-widest mt-1">Produits publiés</p>
                </div>

                <div class="bg-white p-6 rounded-artika shadow-artika border-l-4 border-green_artika">
                    <p class="font-serif text-3xl font-bold text-deep">{{ stats.total_commandes }}</p>
                    <p class="text-[10px] font-bold text-muted_artika uppercase tracking-widest mt-1">Commandes totales</p>
                </div>

                <div class="bg-white p-6 rounded-artika shadow-artika border-l-4 border-terr">
                    <p class="font-serif text-3xl font-bold text-deep">{{ stats.total_utilisateurs }}</p>
                    <p class="text-[10px] font-bold text-muted_artika uppercase tracking-widest mt-1">Utilisateurs inscrits</p>
                </div>
            </div>

            <!-- Users Section -->
            <div class="bg-white rounded-artika shadow-artika overflow-hidden">
                <div class="p-6 border-b border-earth/10 flex justify-between items-center">
                    <h3 class="font-serif text-xl text-deep">Derniers utilisateurs inscrits</h3>
                    <Link :href="route('admin.utilisateurs.liste')" class="text-xs font-bold text-earth uppercase tracking-widest hover:underline">Voir tout →</Link>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-sand/50 text-[10px] font-bold text-muted_artika uppercase tracking-widest">
                                <th class="px-6 py-4">Utilisateur</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4 text-center">Rôle</th>
                                <th class="px-6 py-4 text-center">Statut</th>
                                <th class="px-6 py-4 text-right">Inscription</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-earth/10">
                            <tr v-for="user in stats.derniers_utilisateurs" :key="user.id" class="hover:bg-sand/20 transition-colors">
                                <td class="px-6 py-4 font-bold text-deep text-sm">
                                    {{ user.prenom }} {{ user.nom }}
                                </td>
                                <td class="px-6 py-4 text-sm text-muted_artika">
                                    {{ user.email }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span :class="[
                                        'px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider',
                                        user.role === 'admin' ? 'bg-deep text-gold' : (user.role === 'artisan' ? 'bg-gold/15 text-gold' : 'bg-earth/10 text-earth')
                                    ]">
                                        {{ user.role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span :class="user.est_actif ? 'text-green_artika' : 'text-red-500'" class="text-[10px] font-bold uppercase tracking-widest">
                                        {{ user.est_actif ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-xs text-muted_artika">
                                    {{ new Date(user.created_at).toLocaleDateString() }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
