import { ref, watch, onMounted } from 'vue'

const isDark = ref(true) // Default to dark theme
const theme = ref('dark')

export function useTheme() {
  const toggleTheme = () => {
    isDark.value = !isDark.value
    theme.value = isDark.value ? 'dark' : 'light'
    applyTheme()
  }

  const setTheme = (newTheme) => {
    theme.value = newTheme
    isDark.value = newTheme === 'dark'
    applyTheme()
  }

  const applyTheme = () => {
    const html = document.documentElement
    
    if (isDark.value) {
      html.classList.add('dark')
      html.setAttribute('data-theme', 'dark')
    } else {
      html.classList.remove('dark')
      html.setAttribute('data-theme', 'light')
    }
    
    // Save to localStorage
    localStorage.setItem('theme', theme.value)
  }

  const initTheme = () => {
    // Check localStorage first
    const savedTheme = localStorage.getItem('theme')
    if (savedTheme) {
      setTheme(savedTheme)
    } else {
      // Check system preference
      const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
      setTheme(prefersDark ? 'dark' : 'light')
    }
  }

  // Watch for system theme changes
  const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)')
  mediaQuery.addEventListener('change', (e) => {
    if (!localStorage.getItem('theme')) {
      setTheme(e.matches ? 'dark' : 'light')
    }
  })

  onMounted(() => {
    initTheme()
  })

  return {
    isDark,
    theme,
    toggleTheme,
    setTheme,
    applyTheme
  }
} 