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
            colors: {
                primary: {
                    DEFAULT: '#1B5E20',
                    light: '#4CAF50',
                    dark: '#0D3D0F',
                },
                background: '#F6FBF6',
                surface: '#FFFFFF',
                'text-primary': '#1A1A1A',
                'text-secondary': '#6B7280',
                'border-light': '#E5E7EB',
            },
            fontFamily: {
                sans: ['Inter', 'Work Sans', 'Noto Sans Gujarati', ...defaultTheme.fontFamily.sans],
                gujarati: ['Noto Sans Gujarati', 'sans-serif'],
            },
            borderRadius: {
                'sm': '4px',
                'DEFAULT': '8px',
                'md': '10px',
                'lg': '12px',
                'xl': '16px',
                '2xl': '24px',
            },
            boxShadow: {
                'subtle': '0 2px 10px rgba(0, 0, 0, 0.05)',
                'premium': '0 10px 30px rgba(27, 94, 32, 0.05)',
            }
        },
    },

    plugins: [forms],
};
