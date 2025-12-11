import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // Deshabilitar modo dark
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        "./node_modules/flowbite/**/*.js" // Agregamos Flowbite
    ],
    theme: {
        extend: {
            colors: {
                primary: '#00204A',
                secondary: '#3D6A8A',
                accent: '#CBDCEB',
                light: '#F8F8F4',
                last: '#999999ff',
                button: '#00b158ff',
                hover: '#009249ff'
            },
        },
    },
    plugins: [
        require('flowbite/plugin') // Activamos el plugin
    ],
};