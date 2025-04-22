/** @type {import('tailwindcss').Config} */
export default {
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./app/Http/Livewire/**/*.php",
    ],
    theme: {
      extend: {
        colors: {
          'available': '#10B981',
          'dalam-pinjaman': '#F59E0B',
          'belum-dikembalikan': '#EF4444',
          'primary': '#3B82F6', // Blue accent color
        }
      },
    },
    plugins: [],
  }