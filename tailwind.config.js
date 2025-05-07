/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./includes/pages/**/*.{html,php}",
    "./includes/classes/**/*.php",
    "./includes/handlers/**/*.php",
    "./includes/handlers/Ajax/**/*.php",
    "./includes/js/**/*.js",
    "./includes/assets/csss/**/*.css",
    "./main.css",
    "./*.php",
    "./*.html",
  ],
  theme: {
    extend: {
      fontFamily: {
        spmix: ["Spotify-Mix-Extrabold", "sans-serif"],
        cir: ["CircularSpotifyTxT-Black", "sans-serif"],
        cirbo: ["CircularSpotifyTxT-Bold", "sans-serif"],
        cirbook: ["CircularSpotifyTxT-Book", "sans-serif"],
        cirli: ["CircularSpotifyTxT-Light", "sans-serif"],
        cirmd: ["CircularSpotifyTxT-Med", "sans-serif"],
      },
      colors: {
        transparent: "transparent",
        onyx: "#1F1F1F",
        cosblue: "#002AEC",
        spaceorange: "#FF421B",
        green: "#1db945",
        neon: "#b9fe33",
        bordering: "#2a2a2a",
      },
    },
  },
  plugins: [],
};

// npx tailwindcss -i ./input.css -o ./tailwind.css --watch
