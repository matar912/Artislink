<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    user: any;
    artisan: any;
}>();

// Formulaire pour les infos de base
const infoForm = useForm({
    prenom: props.user.prenom,
    nom: props.user.nom,
    telephone: props.user.telephone ?? '',
    categorie: props.artisan.categorie,
    ville: props.artisan.ville ?? '',
    description: props.artisan.description ?? '',
});

// Formulaire pour l'ajout de photo
const photoForm = useForm({
    photo: null as File | null,
    legende: '',
});

const photoPreview = ref<string | null>(null);

const onPhotoChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        photoForm.photo = file;
        photoPreview.value = URL.createObjectURL(file);
    }
};

const updateInfo = () => {
    // Utilisation de put pour la mise à jour des données textuelles
    infoForm.put(route('artisan.profil.sauvegarder'), {
        preserveScroll: true,
        onSuccess: () => {},
    });
};

const uploadPhoto = () => {
    photoForm.post(route('artisan.photos.ajouter'), {
        preserveScroll: true,
        onSuccess: () => {
            photoForm.reset();
            photoPreview.value = null;
        },
    });
};

const deletePhoto = (id: number) => {
    if (confirm('Voulez-vous retirer cette photo de votre galerie ?')) {
        router.delete(route('artisan.photos.supprimer', id), {
            preserveScroll: true
        });
    }
};
</script>

<template>
    <Head title="Mon Profil — Artika" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-serif text-3xl text-deep tracking-tight">Mon Profil Artisan</h2>
            <p class="text-muted_artika text-sm mt-1">Gérez vos informations personnelles et votre galerie.</p>
        </template>

        <div class="space-y-12">
            <!-- Section 1 : Informations de base -->
            <div class="bg-white rounded-artika shadow-artika p-8 md:p-12">
                <h3 class="font-serif text-xl text-deep mb-8 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-sand flex items-center justify-center text-sm">01</span>
                    Informations Générales
                </h3>

                <form @submit.prevent="updateInfo" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Prénom -->
                        <div>
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Prénom</label>
                            <input v-model="infoForm.prenom" type="text" class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors" required />
                            <InputError :message="infoForm.errors.prenom" class="mt-2" />
                        </div>
                        <!-- Nom -->
                        <div>
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Nom</label>
                            <input v-model="infoForm.nom" type="text" class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors" required />
                            <InputError :message="infoForm.errors.nom" class="mt-2" />
                        </div>
                        <!-- Téléphone -->
                        <div>
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Téléphone</label>
                            <input v-model="infoForm.telephone" type="tel" class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors" />
                            <InputError :message="infoForm.errors.telephone" class="mt-2" />
                        </div>
                        <!-- Catégorie -->
                        <div>
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Métier / Catégorie</label>
                            <select v-model="infoForm.categorie" class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors appearance-none" required>
                                <option value="Menuiserie">Menuiserie</option>
                                <option value="Poterie">Poterie</option>
                                <option value="Bijouterie">Bijouterie</option>
                                <option value="Couture">Couture</option>
                                <option value="Gastronomie">Gastronomie</option>
                                <option value="Autre">Autre</option>
                            </select>
                            <InputError :message="infoForm.errors.categorie" class="mt-2" />
                        </div>
                        <!-- Ville -->
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">Ville</label>
                            <input v-model="infoForm.ville" type="text" class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors" placeholder="Ex: Dakar, Saint-Louis..." />
                            <InputError :message="infoForm.errors.ville" class="mt-2" />
                        </div>
                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-muted_artika uppercase tracking-widest mb-2">À propos de vous / Votre atelier</label>
                            <textarea v-model="infoForm.description" rows="4" class="w-full bg-white border-2 border-earth/20 rounded-xl px-4 py-3 text-sm focus:border-earth outline-none transition-colors" placeholder="Racontez votre parcours, vos inspirations..."></textarea>
                            <InputError :message="infoForm.errors.description" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" :disabled="infoForm.processing" class="btn-artika-gold py-3 px-8 text-sm disabled:opacity-50">
                            {{ infoForm.processing ? 'Enregistrement...' : 'Enregistrer les modifications' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Section 2 : Galerie Photos -->
            <div class="bg-white rounded-artika shadow-artika p-8 md:p-12">
                <h3 class="font-serif text-xl text-deep mb-8 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-sand flex items-center justify-center text-sm">02</span>
                    Galerie de l'Atelier
                </h3>

                <!-- Liste des photos existantes -->
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-12">
                    <div v-for="photo in artisan.photos" :key="photo.id" class="group relative aspect-square rounded-xl overflow-hidden bg-sand/30 border-2 border-transparent hover:border-earth/20 transition-all">
                        <img :src="'/storage/' + photo.url" class="w-full h-full object-cover" />

                        <!-- Actions au survol -->
                        <div class="absolute inset-0 bg-deep/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2">
                            <button @click="deletePhoto(photo.id)" class="text-[10px] font-bold text-red-400 uppercase tracking-widest hover:text-red-300">Retirer</button>
                        </div>
                    </div>

                    <!-- Bouton d'ajout -->
                    <label v-if="!photoPreview" class="aspect-square rounded-xl border-2 border-dashed border-earth/20 flex flex-col items-center justify-center cursor-pointer hover:bg-sand/20 transition-colors">
                        <span class="text-2xl text-earth mb-1">+</span>
                        <span class="text-[10px] font-bold text-muted_artika uppercase tracking-widest">Ajouter</span>
                        <input type="file" @change="onPhotoChange" class="hidden" accept="image/*" />
                    </label>

                    <!-- Preview de la nouvelle photo -->
                    <div v-if="photoPreview" class="aspect-square rounded-xl overflow-hidden border-2 border-gold relative">
                        <img :src="photoPreview" class="w-full h-full object-cover opacity-50" />
                        <div class="absolute inset-0 flex items-center justify-center">
                            <button @click="uploadPhoto" :disabled="photoForm.processing" class="bg-gold text-deep px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest shadow-lg">
                                {{ photoForm.processing ? '...' : 'Valider' }}
                            </button>
                        </div>
                        <button @click="photoPreview = null" class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white rounded-full text-xs flex items-center justify-center">×</button>
                    </div>
                </div>

                <div class="p-4 bg-sand/10 rounded-xl border border-earth/10">
                    <p class="text-xs text-muted_artika leading-relaxed">
                        Partagez l'ambiance de votre atelier. Ces photos apparaissent sur votre profil public pour rassurer les clients sur l'authenticité de votre travail.
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
