<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { getProductImage } from '@/utils';

const props = defineProps<{
    produits: Array<any>;
}>();

const formDelete = useForm({});
const editingStock = ref<number | null>(null);
const stockForm = useForm({
    stock: 0
});

const supprimerProduit = (id: number) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
        formDelete.delete(route('artisan.produits.supprimer', id));
    }
};

const editStock = (produit: any) => {
    editingStock.value = produit.id;
    stockForm.stock = produit.stock || 0;
};

const saveStock = (id: number) => {
    stockForm.patch(route('artisan.produits.stock', id), {
        onSuccess: () => {
            editingStock.value = null;
        }
    });
};
</script>

<template>
    <Head title="Mon Catalogue — Artika" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="font-serif text-3xl text-deep tracking-tight">Catalogue de mes produits</h2>
                    <p class="text-muted_artika text-sm mt-1">Gérez vos créations et leur visibilité.</p>
                </div>
                <Link :href="route('artisan.produits.formulaire-creation')" class="btn-artika-gold py-2.5 px-6">
                    + Ajouter un produit
                </Link>
            </div>
        </template>

        <div class="space-y-8">
            <div v-if="produits.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <div v-for="produit in produits" :key="produit.id" class="card-artika flex flex-col group h-full">
                    <!-- Image -->
                    <div class="h-56 bg-sand/30 relative overflow-hidden flex items-center justify-center text-4xl">
                        <img :src="getProductImage(produit)" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" :alt="produit.nom" />
                        
                        <div class="absolute top-3 right-3">
                            <span :class="produit.est_disponible ? 'bg-green_artika text-white' : 'bg-red-500 text-white'" class="text-[10px] px-2.5 py-1 rounded-full uppercase font-bold tracking-widest shadow-sm">
                                {{ produit.est_disponible ? 'Disponible' : 'Indisponible' }}
                            </span>
                        </div>
                    </div>

                    <!-- Infos -->
                    <div class="p-5 flex-grow flex flex-col">
                        <div class="flex justify-between items-start mb-2 gap-2">
                            <h3 class="font-bold text-deep leading-tight line-clamp-1">{{ produit.nom }}</h3>
                            <span class="text-terr font-serif font-bold whitespace-nowrap">{{ Number(produit.prix).toLocaleString() }} FCFA</span>
                        </div>
                        <p class="text-muted_artika text-xs mb-6 line-clamp-2 h-8 leading-relaxed">{{ produit.description || 'Aucune description fournie.' }}</p>
                        
                        <div class="mt-auto flex justify-between items-end pt-4 border-t border-earth/10">
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] font-bold text-muted_artika uppercase tracking-widest">Stock</span>
                                
                                <div v-if="editingStock === produit.id" class="flex items-center gap-2">
                                    <input 
                                        v-model="stockForm.stock" 
                                        type="number" 
                                        class="w-16 h-8 text-xs font-bold border-2 border-gold rounded-lg px-2 focus:ring-0 outline-none"
                                        @keyup.enter="saveStock(produit.id)"
                                    />
                                    <button @click="saveStock(produit.id)" class="text-green_artika text-lg">✓</button>
                                </div>
                                <div v-else @click="editStock(produit)" class="flex items-center gap-2 cursor-pointer hover:bg-gold/5 p-1 rounded transition-colors group/stock">
                                    <span :class="[
                                        'text-xs font-bold',
                                        produit.stock <= 5 ? 'text-red-500' : 'text-deep'
                                    ]">
                                        {{ produit.stock ?? 'Non défini' }}
                                    </span>
                                    <span class="text-[10px] text-muted_artika opacity-0 group-hover/stock:opacity-100 transition-opacity">Modifier</span>
                                </div>
                            </div>
                            
                            <div class="flex gap-2 mb-1">
                                <Link 
                                    :href="route('artisan.produits.formulaire-modification', produit.id)" 
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-earth/10 text-earth hover:bg-terr hover:text-white transition-all duration-300 shadow-sm"
                                    title="Modifier"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </Link>
                                <button 
                                    @click="supprimerProduit(produit.id)" 
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition-all duration-300 shadow-sm"
                                    title="Supprimer"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-24 bg-white rounded-artika shadow-artika">
                <div class="mb-6 flex justify-center">
                    <span class="text-6xl opacity-20">🏺</span>
                </div>
                <h3 class="text-xl font-serif text-deep mb-2">Votre catalogue est vide</h3>
                <p class="text-muted_artika text-sm mb-8">Commencez par ajouter votre première création pour la proposer à vos clients.</p>
                <Link :href="route('artisan.produits.formulaire-creation')" class="btn-artika-gold">
                    Ajouter mon premier produit
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
