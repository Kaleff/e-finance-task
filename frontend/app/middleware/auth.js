/**
 * Auth middleware
 * Redirects unauthenticated users to login page
 */
export default defineNuxtRouteMiddleware(() => {
  const authStore = useAuthStore()
  
  // If user is not authenticated, redirect to login
  if (!authStore.isAuthenticated) {
    return navigateTo('/login')
  }
})
