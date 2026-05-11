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
            fontSize: {
                'xs': ['12px', { lineHeight: '16px', letterSpacing: '-0.5%' }],
                'sm': ['14px', { lineHeight: '20px', letterSpacing: '-0.3%' }],
                'base': ['16px', { lineHeight: '24px', letterSpacing: '-0.2%' }],
                'lg': ['18px', { lineHeight: '28px', letterSpacing: '-0.1%' }],
                'xl': ['20px', { lineHeight: '28px', letterSpacing: '0%' }],
                '2xl': ['24px', { lineHeight: '32px', letterSpacing: '0%' }],
                '3xl': ['30px', { lineHeight: '36px', letterSpacing: '-0.5%' }],
                '4xl': ['36px', { lineHeight: '44px', letterSpacing: '-0.5%' }],
                '5xl': ['48px', { lineHeight: '52px', letterSpacing: '-1%' }],
            },
            spacing: {
                'xs': '4px',
                'sm': '8px',
                'md': '12px',
                'lg': '16px',
                'xl': '24px',
                '2xl': '32px',
                '3xl': '48px',
                '4xl': '64px',
            },
            animation: {
                'shimmer': 'shimmer 2s infinite',
                'pulse-soft': 'pulse-soft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            },
            keyframes: {
                'shimmer': {
                    '0%': { backgroundPosition: '-1000px 0' },
                    '100%': { backgroundPosition: '1000px 0' },
                },
                'pulse-soft': {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '.7' },
                },
            },
            colors: {
                // Primary brand color (Blue)
                brand: {
                    50: '#f0f4ff',
                    100: '#e0e9ff',
                    200: '#c7d5ff',
                    300: '#a4b5ff',
                    400: '#7c8dff',
                    500: '#6366f1',
                    600: '#4f46e5',
                    700: '#4338ca',
                    800: '#3730a3',
                    900: '#312e81',
                },
                // Primary action (Deeper blue)
                primary: {
                    50: '#f0f4ff',
                    100: '#dde5ff',
                    200: '#c7d2ff',
                    300: '#a8b5ff',
                    400: '#7c8dff',
                    500: '#5a67d8',
                    600: '#4c51bf',
                    700: '#3f46b8',
                    800: '#1f40ff',
                    900: '#1a35c9',
                },
                // Secondary (Indigo)
                secondary: {
                    50: '#f5f3ff',
                    100: '#ede9fe',
                    200: '#ddd6fe',
                    300: '#c4b5fd',
                    400: '#a78bfa',
                    500: '#8b5cf6',
                    600: '#7c3aed',
                    700: '#6d28d9',
                    800: '#5b21b6',
                    900: '#4c1d95',
                },
                // Accent (Pink)
                accent: {
                    50: '#fdf2f8',
                    100: '#fce7f3',
                    200: '#fbcfe8',
                    300: '#f8b4d4',
                    400: '#ec4899',
                    500: '#ec4899',
                    600: '#db2777',
                    700: '#be185d',
                    800: '#9d174d',
                    900: '#831843',
                },
                // Semantic colors
                success: {
                    50: '#f0fdf4',
                    600: '#16a34a',
                    700: '#15803d',
                },
                warning: {
                    50: '#fffbeb',
                    600: '#d97706',
                    700: '#b45309',
                },
                danger: {
                    50: '#fef2f2',
                    600: '#dc2626',
                    700: '#b91c1c',
                },
                dark: {
                    bg: '#0a0a0a',
                    surface: '#111111',
                    card: '#111111',
                    border: 'rgba(255, 255, 255, 0.08)',
                }
            },
            boxShadow: {
                'sm': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                'base': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
                'md': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                'lg': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                'xl': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                'hover': '0 20px 25px -5px rgba(31, 64, 255, 0.15)',
                'focus': '0 0 0 3px rgba(99, 102, 241, 0.1), 0 0 0 1.5px #6366f1',
            },
            borderRadius: {
                'none': '0',
                'sm': '6px',
                'base': '8px',
                'md': '12px',
                'lg': '16px',
                'xl': '20px',
                'full': '9999px',
            },
            transitionDuration: {
                'fast': '150ms',
                'base': '200ms',
                'slow': '300ms',
            },
            transitionTimingFunction: {
                'smooth': 'cubic-bezier(0.4, 0, 0.2, 1)',
                'bounce': 'cubic-bezier(0.34, 1.56, 0.64, 1)',
            },
        },
        screens: {
            'xs': '360px',
            'sm': '640px',
            'md': '768px',
            'lg': '1024px',
            'xl': '1280px',
            '2xl': '1536px',
        },
    },

    plugins: [forms],
};
