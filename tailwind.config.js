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
                primary: '#D1D5DB', // Gray 300 (Even Lighter Navbar)
                secondary: '#9CA3AF', // Gray 400
                background: '#F3F4F6', // Gray 100
                card: '#FFFFFF', // White
                main: '#000000', // Black (Titles)
                muted: '#636a76ff', // Gray 700 (Darker Muted for visibility)
                border: '#E5E7EB', // Gray 200
                like: '#EF4444', // Red 500
            },
        },
    },

    plugins: [forms],
};
