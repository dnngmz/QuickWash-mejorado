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
                wine: {
                    DEFAULT: '#722F37',
                    50: '#F4E9EA',
                    100: '#E4C8CB',
                    200: '#C89399',
                    300: '#AC5F67',
                    400: '#902A35',
                    500: '#722F37',
                    600: '#5A252C',
                    700: '#421B20',
                    800: '#2A1115',
                    900: '#130809',
                }
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
