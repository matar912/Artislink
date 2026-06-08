<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const imagePreview = ref<string | null>(null);

const form = useForm({
    nom: '',
    prix: '',
    stock: '',
    categorie_produit: '',
    description: '',
    image_principale: null as File | null,
    est_disponible: true,
});

const onFileChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        form.image_principale = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(route('artisan.produits.creer'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Ajouter une création — Artika" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('artisan.produits.liste')" class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-muted_artika hover:text-terr transition-colors shadow-sm border border-earth/10">
                    ←
                </Link>
                <div>
                    <h2 class="font-serif text-3xl text-deep tracking-tight">Nouvelle création</h2>
                    <p class="text-muted_artika text-sm mt-1">Publiez un nouveau produit dans votre boutique.</p>
                </div>
            </div>
        </template>

        <div class="max-w-4xl">
            <div class="bg-white rounded-artika shadow-artika p-8 md:p-12">
                <form @submit.prevent="submit" class="space-y-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
                        
                        <!-- Image Upload Section -->
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-4">Photo principale du produit</label>
                            <div class="flex flex-col md:flex-row items-center gap-8">
                                <div class="w-48 h-48 rounded-artika bg-sand/30 flex items-center justify-center text-4xl overflow-hidden border-2 border-dashed border-earth/20 relative group">
                                    <img v-if="imagePreview" :src="imagePreview" class="w-full h-full object-cover" />
                                    <span v-else>🏺</span>
                                    <input
                                        type="file"
                                        @change="onFileChange"
                                        class="absolute inset-0 opacity-0 cursor-pointer"
                                        accept="image/*"
                                    />
                                </div>
                                <div class="flex-grow">
                                    <p class="text-sm font-bold text-deep mb-2">Choisissez une photo inspirante</p>
                                    <p class="text-xs text-muted_artika leading-relaxed mb-4">Une belle photo augmente vos chances de vente de 80%. Format conseillé : Carré (1080x1080px).</p>
                                    <label class="btn-artika-outline py-2 px-4 cursor-pointer inline-block">
                                        Parcourir mes fichiers
                                        <input type="file" @change="onFileChange" class="hidden" accept="image/*" />
                                    </label>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="form.errors.image_principale" />
                        </div>

                        <!-- Nom -->
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Nom de la création</label>
                            <input
                                v-model="form.nom"
                                type="text"
                                class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors"
                                placeholder="Ex: Vase Terracotta Traditionnel"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.nom" />
                        </div>

                        <!-- Prix -->
                        <div>
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Prix (FCFA)</label>
                            <input
                                v-model="form.prix"
                                type="number"
                                class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors"
                                placeholder="0"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.prix" />
                        </div>

                        <!-- Stock -->
                        <div>
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Stock disponible</label>
                            <input
                                v-model="form.stock"
                                type="number"
                                class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors"
                                placeholder="Laisser vide si illimité"
                            />
                            <InputError class="mt-2" :message="form.errors.stock" />
                        </div>

                        <!-- Catégorie -->
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Catégorie</label>
                            <select
                                v-model="form.categorie_produit"
                                class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors appearance-none"
                            >
                                <option value="">Sélectionner une catégorie</option>
                                <option value="Poterie">Poterie</option>
                                <option value="Textile">Textile</option>
                                <option value="Bijoux">Bijoux</option>
                                <option value="Sculpture">Sculpture</option>
                                <option value="Maroquinerie">Maroquinerie</option>
                                <option value="Autre">Autre</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.categorie_produit" />
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Description / Histoire de l'objet</label>
                            <textarea
                                v-model="form.description"
                                class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors"
                                rows="5"
                                placeholder="Racontez comment cet objet a été fabriqué, les matériaux utilisés..."
                            ></textarea>
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>

                        <!-- Disponibilité -->
                        <div class="md:col-span-2 flex items-center gap-3 p-4 bg-sand/30 rounded-xl border border-earth/10">
                            <input
                                id="est_disponible"
                                type="checkbox"
                                v-model="form.est_disponible"
                                class="w-5 h-5 rounded border-earth/20 text-terr focus:ring-terr"
                            />
                            <label for="est_disponible" class="text-sm font-bold text-deep">
                                Publier immédiatement (Visible en boutique)
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-8 border-t border-earth/10">
                        <Link :href="route('artisan.produits.liste')" class="text-xs font-bold text-muted_artika uppercase tracking-widest hover:text-terr transition-colors">
                            Annuler
                        </Link>
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="btn-artika-gold py-4 px-10 text-base disabled:opacity-50"
                        >
                            {{ form.processing ? 'Publication...' : 'Publier ma création' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
