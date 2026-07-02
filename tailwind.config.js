/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        paper: '#F7F7F4',
        ink: '#17181C',
        rule: '#DEDBD3',
        brand: {
          primary: 'var(--color-primary)',
          secondary: 'var(--color-secondary)',
          headerBg: 'var(--color-header-bg)',
          headerText: 'var(--color-header-text)',
          footerBg: 'var(--color-footer-bg)',
          footerText: 'var(--color-footer-text)',
        },
      },
      fontFamily: {
        display: ['"Fraunces"', 'Georgia', 'serif'],
        body: ['"Inter"', 'sans-serif'],
        mono: ['"IBM Plex Mono"', 'monospace'],
      },
    },
  },
  plugins: [],
};
