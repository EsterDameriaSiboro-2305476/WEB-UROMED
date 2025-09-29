/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/views/dashboard.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        'inter': ['Inter', 'sans-serif'],
      },
      colors: {
        uromed: {
          primary: '#437118',     // Hijau tua
          secondary: '#AFD06E',   // Hijau terang
          background: '#F5F3D8',  // Krem terang
          accent: '#87AECE',      // Biru lembut
          darkblue: '#1D2A62',    // Biru gelap
        },
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
