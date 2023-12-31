/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    '*.php',
    './src/**/*.ts',
    './src/*.vue',
    "./src/**/*.{vue,js,ts,jsx,tsx,php}",
    './templates/**/*.html',
    './parts/**/*.html',
    './widgets/*.php',
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    screens: {
      'sm': '360px',
      // => @media (min-width: 640px) { ... }

      'md': '768px',
      // => @media (min-width: 768px) { ... }

      'lg': '1024px',
      // => @media (min-width: 1024px) { ... }

      'xl': '1280px',
      // => @media (min-width: 1280px) { ... }

      '2xl': '1536px',
      // => @media (min-width: 1536px) { ... }
    },
    extend: {},
  },
  plugins: [
    
  ],
}