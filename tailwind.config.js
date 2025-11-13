export default {
  content: ['./resources/**/*.blade.php','./resources/**/*.js'],
  theme: {
    extend: {
      colors: {
        miel:   '#F3B000',  // acento
        borgo:  '#7B2321',  // principal (logo APME)
        borgo2: '#5D1A19',
        crema:  '#FFF7E6',
        tinta:  '#0B0F14',
      },
      fontFamily: {
        display: ['Poppins','ui-sans-serif','system-ui'],
        body:    ['Inter','ui-sans-serif','system-ui'],
      },
      boxShadow: { suave: '0 10px 30px -10px rgba(0,0,0,.25)' }
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
  ],
}
