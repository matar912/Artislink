export const getCategoryPlaceholder = (category: string) => {
    const images: Record<string, string> = {
        'Poterie': 'https://images.unsplash.com/photo-1595111051515-56885368a5c3?w=500',
        'Couture': 'https://images.unsplash.com/photo-1544441893-675973e31985?w=500',
        'Bijouterie': 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=500',
        'Menuiserie': 'https://images.unsplash.com/photo-1533090161767-e6ffed986c88?w=500',
        'Peinture': 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=500',
        'Maroquinerie': 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=500',
        'Gastronomie': 'https://images.unsplash.com/photo-1559181567-c3190ca9959b?w=500',
    };
    return images[category] || 'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?w=500';
};

export const getProductImage = (produit: any) => {
    if (!produit || !produit.image_principale) return getCategoryPlaceholder(produit?.categorie_produit || '');
    
    return produit.image_principale.startsWith('http') 
        ? produit.image_principale 
        : '/storage/' + produit.image_principale;
};
