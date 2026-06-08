<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Créer un compte — Artika" />

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-cream">
        <!-- Left Side: Brand Decor -->
        <div class="hidden lg:flex flex-col items-center justify-center bg-gradient-to-br from-deep via-[#3d1f00] to-[#5c2e00] p-12 relative overflow-hidden">
            <div class="absolute inset-0 opacity-[0.05]" style="background-image: repeating-linear-gradient(45deg, #d4a017 0, #d4a017 1px, transparent 0, transparent 30px); background-size: 30px 30px;"></div>
            
            <div class="relative z-10 text-center">
                <Link :href="route('accueil')" class="font-serif text-7xl text-gold tracking-tighter mb-4 block">
                    Artika
                </Link>
                <p class="text-white/60 text-xl font-medium leading-relaxed max-w-sm">
                    Rejoignez la communauté de l'artisanat sénégalais
                </p>
            </div>
            
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-gold/5 rounded-full blur-3xl"></div>
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-earth/5 rounded-full blur-3xl"></div>
        </div>

        <!-- Right Side: Choice -->
        <div class="flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md">
                <div class="mb-10 text-center lg:text-left">
                    <Link :href="route('accueil')" class="lg:hidden font-serif text-3xl font-bold text-terr tracking-tighter mb-8 block">
                        Artik<span class="text-gold">a</span>
                    </Link>
                    <h2 class="font-serif text-4xl text-deep mb-3">S'inscrire</h2>
                    <p class="text-muted_artika">Choisissez le type de compte qui vous correspond</p>
                </div>

                <div class="space-y-6">
                    <!-- Choice 1: Visitor -->
                    <Link 
                        :href="route('inscription.visiteur')"
                        class="group block p-6 bg-white border-2 border-earth/10 rounded-2xl hover:border-terr hover:shadow-xl transition-all duration-300"
                    >
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 rounded-xl bg-sand flex items-center justify-center text-3xl group-hover:scale-110 transition-transform">
                                🛍️
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-deep">Je suis un Client</h3>
                                <p class="text-sm text-muted_artika">Je souhaite découvrir et acheter des créations artisanales.</p>
                            </div>
                        </div>
                    </Link>

                    <!-- Choice 2: Artisan -->
                    <Link 
                        :href="route('inscription.artisan')"
                        class="group block p-6 bg-white border-2 border-earth/10 rounded-2xl hover:border-terr hover:shadow-xl transition-all duration-300"
                    >
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 rounded-xl bg-gold/10 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform">
                                🏺
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-deep">Je suis un Artisan</h3>
                                <p class="text-sm text-muted_artika">Je souhaite vendre mes produits et gérer mon atelier en ligne.</p>
                            </div>
                        </div>
                    </Link>
                </div>

                <div class="mt-12 text-center">
                    <p class="text-sm text-muted_artika font-medium mb-6">
                        Vous avez déjà un compte ? 
                        <Link :href="route('login')" class="text-terr font-bold hover:underline">Connectez-vous</Link>
                    </p>
                    
                    <Link :href="route('accueil')" class="flex items-center justify-center gap-2 text-xs font-bold text-muted_artika uppercase tracking-widest hover:text-terr transition-colors">
                        ← Retour à l'accueil
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
