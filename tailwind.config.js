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
                //sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                sans: ['Jost', ...defaultTheme.fontFamily.sans],
                display: ['"Marcellus SC"', 'serif'],
            },
            colors: {
                primary: '#c4aa7e',
                secondary: '#82834c',
            },
        },
    },

    plugins: [forms],
};
