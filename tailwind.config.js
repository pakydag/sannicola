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
                'legno-scuro': '#2d2926',
                terracotta: '#a64d32',
                'oro-antico': '#c5a059',
                
                // Mappatura semantica
                primary: {
                    DEFAULT: '#721c24',
                    foreground: '#ffffff',
                },
                secondary: {
                    DEFAULT: '#c5a059',
                    foreground: '#2d2926',
                },
                background: '#fbf9f5',
                surface: {
                    DEFAULT: '#ffffff',
                    variant: '#f5f3ef',
                },
                outline: {
                    DEFAULT: '#dbdad6',
                    variant: 'rgba(219, 218, 214, 0.3)',
                }
            },
            fontFamily: {
                'display': ['"Playfair Display"', 'serif'],
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
        }, // Chiusura corretta di extend
    },

    plugins: [forms],
};