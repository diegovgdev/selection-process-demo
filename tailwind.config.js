import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/Enums/**/*.php',
    ],
    theme: {
        extend: {},
    },
    plugins: [forms],
};
