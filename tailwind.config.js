/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'selector',
    content: ["./resources/**/*.blade.php", "./resources/**/*.js"],
    theme: {
        extend: {
            fontFamily: {
                ubuntu: ["Ubuntu", "sans-serif"],
                popi: ["Poppins", "sans-serif"],
            },
        },
    },
    plugins: [],
};
