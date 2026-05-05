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
                }
            },
        },
    },

    plugins: [forms],
};
