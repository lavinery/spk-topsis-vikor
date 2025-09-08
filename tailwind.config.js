import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './app/Livewire/**/*.php',
    ],

    theme: {
        extend: {
            colors: {
                brand: { 
                    DEFAULT: '#4f46e5'          // SATU aksen (indigo 600)
                },
                neutral: {
                    bg:   '#ffffff',                       // page bg
                    card: '#ffffff',                       // card bg
                    line: '#e5e7eb',                       // borders
                    text: '#111827',                       // primary text
                    sub:  '#6b7280'                        // secondary text
                },
                ok:     { 
                    DEFAULT:'#059669' 
                },           // dipakai hanya utk "DIBUKA"
                danger: { 
                    DEFAULT:'#dc2626' 
                }            // dipakai hanya utk "DITUTUP"
            },
            boxShadow: { 
                soft: '0 4px 20px rgba(0,0,0,.04)' 
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
