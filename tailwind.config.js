import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
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
                // Fortress Lenders Brand Colors (from brand guidelines)
                'fortress': {
                    'primary': '#0f766e',      // Dark Teal Green - Trust, Stability
                    'primary-dark': '#134e4a', // Darker Teal
                    'primary-light': '#14b8a6', // Lighter Teal
                    'secondary': '#f59e0b',     // Orange-Yellow - Premium, Energy
                    'secondary-light': '#fbbf24', // Light Orange-Yellow
                    'accent': '#10b981',        // Emerald Green - Growth
                    'dark': '#0f172a',          // Slate Dark
                    'light': '#f8fafc',         // Slate Light
                }
            },
        },
    },

    plugins: [forms],
};
