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
            // Palette "L'Artigiano Gastronomico"
            colors: {
                borgogna: '#721c24',
                crema: '#fbf9f5',
                'legno-scuro': '#3f2f2b',
                terracotta: '#a64d32',
                'oro-antico': '#c5a059',
                
                // Mappatura semantica diretta
                primary: '#721c24',
                'primary-foreground': '#ffffff',
                
                secondary: '#c5a059',
                'secondary-foreground': '#2d2926',
                
                background: '#fbf9f5',
                
                surface: '#ffffff',
                'surface-variant': '#f5f3ef',
                
                outline: '#dbdad6',
                'outline-variant': 'rgba(219, 218, 214, 0.3)',
            },
            fontFamily: {
                'display': ['"Jost"', 'serif'],
                'body': ['"Inter"', 'sans-serif'],
            },
            spacing: {
                'margin-desktop': '5vw',
                'gutter': '2rem',
                'unit': '1rem',
            },
            maxWidth: {
                'container-max-width': '1440px',
            },
            borderRadius: {
                'brand': '4px',
            }
        },
    },

    plugins: [forms],
};