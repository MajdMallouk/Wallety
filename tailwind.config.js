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
                accent: {
                    50: '#f0f0ff',
                    100: '#e0e0ff',
                    200: '#cbcbff',
                    300: '#a9a6ff',
                    400: '#7966ff',   // base accent
                    500: '#5b4cf9',
                    600: '#4a3be8',
                    700: '#3c2bd4',
                    800: '#3225b0',
                    900: '#2c238c',
                },
                base: {
                    bg: '#1f2937',
                    bgLight: '#fff',
                    dark: '#0f172a',
                },
                complementary: {
                    light: '#ffd966',  // Golden yellow (complementary)
                    base: '#ffcc33',
                    dark: '#e6b800'
                },
                neutrals: {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a'
                }
            },
        },
    },

    plugins: [forms],
};
