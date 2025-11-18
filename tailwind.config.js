export default {
  content: ['./resources/**/*.blade.php','./resources/**/*.js'],
  theme: {
    extend: {
      colors: {
        miel:   '#F3B000',  // acento principal
        borgo:  '#7B2321',  // color corporativo
        borgo2: '#5D1A19',  // borgo oscuro
        crema:  '#FFF7E6',  // fondo claro
        tinta:  '#0B0F14',  // texto oscuro
        hoja:   '#2D5A27',  // verde natural
        ambar:  '#D47A00',  // tono miel oscuro
      },
      fontFamily: {
        display: ['Poppins','ui-sans-serif','system-ui'],
        body:    ['Inter','ui-sans-serif','system-ui'],
      },
      boxShadow: { 
        suave: '0 10px 30px -10px rgba(0,0,0,.25)',
        card: '0 4px 12px rgba(0,0,0,0.08)',
      },
      animation: {
        'fade-in': 'fadeIn 0.5s ease-in-out',
        'slide-up': 'slideUp 0.6s ease-out',
      }
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
  ],
}