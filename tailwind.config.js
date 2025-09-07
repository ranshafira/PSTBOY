// tailwind.config.js

import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Palet warna yang diperbarui
                primary: {
                    // Biru gelap sebagai warna utama
                    50: "#e0f2fe",
                    100: "#bae6fd",
                    200: "#7dd3fc",
                    300: "#38bdf8",
                    400: "#0ea5e9",
                    500: "#0E2954", // Warna utama yang lebih gelap
                    600: "#0284c7",
                    700: "#0369a1",
                    800: "#075985",
                    900: "#0c4a6e",
                    950: "#082f49",
                },
                accent: {
                    // Oranye sebagai warna aksen
                    50: "#fff7ed",
                    100: "#ffedd5",
                    200: "#fed7aa",
                    300: "#fdba74",
                    400: "#fb923c",
                    500: "#FF7F3E", // Warna aksen oranye
                    600: "#ea580c",
                    700: "#c2410c",
                    800: "#9a3412",
                    900: "#7c2d12",
                    950: "#431407",
                },
                danger: "#ef4444", // Merah untuk error/danger
                success: "#22c55e", // Hijau untuk sukses
                warning: "#facc15", // Kuning untuk peringatan
            },
        },
    },

    plugins: [forms],
};
