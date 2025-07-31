import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    green: '#7AC943', // vert logo
                    yellow: '#FFD600', // jaune logo
                    blue: '#00AEEF', // bleu logo
                    pink: '#EC008C', // rose/fuchsia logo
                },
                'brand-green': '#7ed957',
                'brand-green-dark': '#3a6b2e',
                'brand-yellow': '#ffe45e',
                'brand-yellow-dark': '#bfae2e',
                'brand-orange': '#ff9f43',
                'brand-orange-dark': '#b85d1c',
                'brand-pink': '#ff5eae',
                'brand-pink-dark': '#a02e6c',
                'brand-blue': '#3b82f6',
                'brand-blue-dark': '#1e3a8a',
                'brand-gray': '#f5f6fa',
                'brand-gray-dark': '#23272f',
                'brand-shadow': '#e0e0e0',
                'brand-shadow-dark': '#181a20',
            },
            boxShadow: {
                'neu': '8px 8px 16px #e0e0e0, -8px -8px 16px #ffffff',
                'neu-inset': 'inset 8px 8px 16px #e0e0e0, inset -8px -8px 16px #ffffff',
            },
            borderRadius: {
                'neu': '1.5rem',
            },
        },
    },

    plugins: [forms],
};
