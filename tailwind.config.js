/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    './resources/views/filament/**/*.blade.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

// import preset from './vendor/filament/support/tailwind.config.preset'

// export default {
//     presets: [preset],
//     content: [
//         './resources/views/**/*.blade.php',
//         './app/Filament/**/*.php',
//         './resources/views/filament/**/*.blade.php',
//         './vendor/filament/**/*.blade.php',
//     ],
// }

