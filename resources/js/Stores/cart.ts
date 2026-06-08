import { reactive, watch } from 'vue';

interface CartItem {
    id: number;
    nom: string;
    prix: number;
    image_principale: string | null;
    quantite: number;
    artisan_nom: string;
}

interface CartState {
    items: CartItem[];
}

const CART_STORAGE_KEY = 'artika_cart';

// Charger le panier depuis le localStorage
const savedCart = localStorage.getItem(CART_STORAGE_KEY);
const initialState: CartState = savedCart ? JSON.parse(savedCart) : { items: [] };

export const cart = reactive<CartState>(initialState);

// Sauvegarder automatiquement dans le localStorage
watch(cart, (newState) => {
    localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(newState));
}, { deep: true });

export const addToCart = (produit: any) => {
    const existingItem = cart.items.find(item => item.id === produit.id);
    
    if (existingItem) {
        existingItem.quantite++;
    } else {
        cart.items.push({
            id: produit.id,
            nom: produit.nom,
            prix: produit.prix,
            image_principale: produit.image_principale,
            quantite: 1,
            artisan_nom: produit.artisan?.user ? `${produit.artisan.user.prenom} ${produit.artisan.user.nom}` : 'Artisan local'
        });
    }
};

export const removeFromCart = (produitId: number) => {
    const index = cart.items.findIndex(item => item.id === produitId);
    if (index !== -1) {
        cart.items.splice(index, 1);
    }
};

export const updateQuantity = (produitId: number, quantite: number) => {
    const item = cart.items.find(item => item.id === produitId);
    if (item) {
        item.quantite = Math.max(1, quantite);
    }
};

export const clearCart = () => {
    cart.items = [];
};

export const cartTotal = () => {
    return cart.items.reduce((total, item) => total + (item.prix * item.quantite), 0);
};

export const cartCount = () => {
    return cart.items.reduce((total, item) => total + item.quantite, 0);
};
