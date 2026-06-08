<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const page = usePage();
const flash = computed(() => page.props.flash as { succes?: string; erreur?: string });

const showSucces = ref(false);
const showErreur = ref(false);

watch(() => flash.value.succes, (newVal) => {
    if (newVal) {
        showSucces.value = true;
        setTimeout(() => {
            showSucces.value = false;
        }, 6000);
    } else {
        showSucces.value = false;
    }
}, { immediate: true });

watch(() => flash.value.erreur, (newVal) => {
    if (newVal) {
        showErreur.value = true;
        setTimeout(() => {
            showErreur.value = false;
        }, 8000);
    } else {
        showErreur.value = false;
    }
}, { immediate: true });
</script>

<template>
    <div class="fixed bottom-8 right-8 z-[100] space-y-4 max-w-sm w-full pointer-events-none">
        <!-- Message de succès (Style Artika Or/Terre) -->
        <Transition
            enter-active-class="transform ease-out duration-500 transition"
            enter-from-class="translate-y-10 opacity-0 scale-95"
            enter-to-class="translate-y-0 opacity-100 scale-100"
            leave-active-class="transition ease-in duration-300"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div v-if="showSucces && flash.succes" 
                class="pointer-events-auto bg-white border-2 border-gold/30 p-1 shadow-[0_10px_30px_rgba(212,160,23,0.15)] rounded-2xl overflow-hidden"
            >
                <div class="bg-gradient-to-r from-gold/10 to-transparent p-4 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-gold flex items-center justify-center text-deep shrink-0 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="flex-1 pt-0.5">
                        <h4 class="text-xs font-bold text-gold uppercase tracking-[2px] mb-1">Succès</h4>
                        <p class="text-sm font-medium text-deep leading-relaxed">
                            {{ flash.succes }}
                        </p>
                    </div>
                    <button @click="showSucces = false" class="text-muted_artika hover:text-terr p-1 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <!-- Progress bar -->
                <div class="h-1 bg-gold/20 w-full">
                    <div class="h-full bg-gold animate-[progress_6s_linear_forwards]"></div>
                </div>
            </div>
        </Transition>

        <!-- Message d'erreur (Style Artika Terre/Rouge) -->
        <Transition
            enter-active-class="transform ease-out duration-500 transition"
            enter-from-class="translate-y-10 opacity-0 scale-95"
            enter-to-class="translate-y-0 opacity-100 scale-100"
            leave-active-class="transition ease-in duration-300"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div v-if="showErreur && flash.erreur" 
                class="pointer-events-auto bg-white border-2 border-terr/30 p-1 shadow-[0_10px_30px_rgba(139,69,19,0.15)] rounded-2xl overflow-hidden"
            >
                <div class="bg-gradient-to-r from-terr/5 to-transparent p-4 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-terr flex items-center justify-center text-white shrink-0 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex-1 pt-0.5">
                        <h4 class="text-xs font-bold text-terr uppercase tracking-[2px] mb-1">Attention</h4>
                        <p class="text-sm font-medium text-deep leading-relaxed">
                            {{ flash.erreur }}
                        </p>
                    </div>
                    <button @click="showErreur = false" class="text-muted_artika hover:text-terr p-1 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <!-- Progress bar -->
                <div class="h-1 bg-terr/10 w-full">
                    <div class="h-full bg-terr animate-[progress_8s_linear_forwards]"></div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
@keyframes progress {
    from { width: 100%; }
    to { width: 0%; }
}
</style>
