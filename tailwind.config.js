import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['DM Sans', 'Figtree', ...defaultTheme.fontFamily.sans],
                serif: ['Playfair Display', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                sand: '#f5e6c8',
                earth: '#c17f3b',
                terr: '#8b4513',
                deep: '#1a0f00',
                green_artika: '#2d5a27', // Renamed to avoid conflict with default green
                gold: '#d4a017',
                cream: '#fdf6e9',
                muted_artika: '#7a6248',
            },
            borderRadius: {
                'artika': '14px',
            },
            boxShadow: {
                'artika': '0 4px 24px rgba(26,15,0,0.10)',
            }
        },
    },

    plugins: [forms],
};
