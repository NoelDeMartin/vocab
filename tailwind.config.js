const { theme: { spacing } } = require('tailwindcss/defaultConfig');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/**/*.blade.php',
    ],
    theme: {
        extend: {
            spacing: {
                edge: spacing['8'],
            },
                        typography: ({ theme }) => ({
                DEFAULT: {
                    css: {
                        'code::before': {
                            content: '""',
                        },
                        'code::after': {
                            content: '""',
                        },
                    },
                },
            }),
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
    ],
};
