/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/**/*.{js,jsx,ts,tsx}",
    "./components/**/*.{js,jsx,ts,tsx}",
    "./src/**/*.{js,jsx,ts,tsx}",
  ],
  presets: [require('nativewind/preset')],
  theme: {
    extend: {
      colors: {
        primary: '#2563EB',
        secondary: '#0891b2',
        danger: '#EF4444',
        success: '#16a34a',
        warning: '#ea580c',
      },
    },
  },
  plugins: [],
};