/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
    ],

    theme: {
        extend: {
            colors: {
                'black': "#060606",
                'gray-mid': "#f5f6f9",
                'cyan': "#47cdff",
            },
            fontFamily: {
                "hanken-grotesk": ["Hanken Grotesk", "sans-serif"]
            },
            fontSize: {
                "2xs": "10px" //10px = 0.625rem
            }
        },
    },

    plugins: [],
}

