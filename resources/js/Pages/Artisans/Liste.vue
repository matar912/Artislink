<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    artisans: any[];
}>();

const page = usePage();
const auth = computed(() => page.props.auth as { user: any });
</script>

<template>
    <Head title="Nos Artisans — Artika" />

    <div class="min-h-screen bg-cream">
        <!-- Navigation Simple -->
        <nav class="bg-white border-b border-earth/10 px-6 py-4 flex items-center justify-between sticky top-0 z-50">
            <Link :href="route('accueil')" class="font-serif text-2xl font-bold text-terr tracking-tighter">Artika</Link>
            <div class="flex items-center gap-4">
                <Link :href="route('accueil')" class="text-sm font-medium text-muted_artika hover:text-terr">Accueil</Link>
                <Link v-if="auth.user" :href="auth.user.dashboard_route" class="btn-artika-outline py-1.5 px-4 text-xs">Mon Espace</Link>
                <Link v-else :href="route('login')" class="btn-artika-outline py-1.5 px-4 text-xs">Connexion</Link>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-6 py-12">
            <div class="mb-12">
                <h1 class="font-serif text-4xl text-deep mb-2">Nos Artisans</h1>
                <p class="text-muted_artika">Découvrez les talents qui font vivre l'artisanat sénégalais.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="artisan in artisans" :key="artisan.id" class="bg-white p-8 rounded-artika shadow-artika border border-earth/5 hover:shadow-xl transition-all">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-earth to-gold mx-auto mb-6 flex items-center justify-center text-4xl text-white font-serif overflow-hidden ring-4 ring-cream">
                        <img v-if="artisan.user.avatar_url && !artisan.user.avatar_url.includes('ui-avatars.com')" :src="artisan.user.avatar_url" class="w-full h-full object-cover">
                        <span v-else>{{ artisan.user.prenom[0] }}</span>
                    </div>
                    
                    <div class="text-center">
                        <h2 class="text-xl font-bold text-deep mb-1">{{ artisan.user.prenom }} {{ artisan.user.nom }}</h2>
                        <div class="flex items-center justify-center gap-2 mb-4">
                            <span class="px-2 py-0.5 bg-gold/10 text-gold text-[10px] font-bold uppercase rounded-full">{{ artisan.categorie }}</span>
                            <span class="text-xs text-muted_artika">📍 {{ artisan.ville }}</span>
                        </div>
                        <p class="text-sm text-muted_artika line-clamp-3 mb-8 leading-relaxed italic">
                            "{{ artisan.description || 'Artisan passionné partageant son savoir-faire.' }}"
                        </p>
                        
                        <Link :href="auth.user ? route('visiteur.boutique', { categorie: artisan.categorie }) : route('login')" class="block w-full py-3 bg-terr text-white rounded-xl font-bold text-sm hover:bg-deep transition-colors">
                            Voir ses créations
                        </Link>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
