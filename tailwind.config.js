import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['JetBrains Mono', ...defaultTheme.fontFamily.sans],
                display: ['Bebas Neue', ...defaultTheme.fontFamily.sans],
                grotesk: ['Neue Haas Grotesk', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50: '#f5f7ff',
                    100: '#ebf0fe',
                    200: '#ced9fd',
                    300: '#b1c2fb',
                    400: '#2255FF',
                    500: '#2255FF',
                    600: '#1f4ce2',
                    700: '#1a44c8',
                    800: '#153aa3',
                    900: '#112d7c',
                },
                dark: {
                    bg: '#0A0A0A',
                    surface: '#111111',
                    card: '#111111',
                    border: 'rgba(255, 255, 255, 0.08)',
                },
                neutral: {
                    '0a': '#0A0A0A',
                    '1e': '#1E1E1E',
                    '33': '#333333',
                    '55': '#555555',
                    '7a': '#7A7A7A',
                    'd9': '#D9D9D9',
                    'f0': '#F0EDE6',
                },
                accent: {
                    cobalt: '#2255FF',
                    bestseller: '#F5A623',
                    success: '#1DB954',
                    danger: '#FF3B30',
                }
            },
            spacing: {
                'safe-4': 'max(1rem, env(safe-area-inset-left))',
                'safe-8': 'max(2rem, env(safe-area-inset-left))',
            },
            gridTemplateColumns: {
                'catalog-sm': 'repeat(1, minmax(0, 1fr))',
                'catalog-md': 'repeat(2, minmax(0, 1fr))',
                'catalog-lg': 'repeat(3, minmax(0, 1fr))',
            }
        },
    },

    plugins: [forms],
};
