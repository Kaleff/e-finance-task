/**
 * Composable for authentication
 * Handles user login, logout, and authentication state
 */
export const useAuth = () => {
  const authStore = useAuthStore()
  const api = useApi()
  const router = useRouter()

  /**
   * Login user
   * @param {object} credentials - User credentials (email, password)
   * @returns {Promise} - Authentication response
   */
  const login = async (credentials) => {
    try {
      const response = await api.post('/login', credentials)
      
      if (response.access_token) {
        authStore.setToken(response.access_token)
        authStore.setUser(response.user)
        return response
      }
    } catch (error) {
      throw error
    }
  }

  /**
   * Register new user
   * @param {object} userData - User registration data
   * @returns {Promise} - Registration response
   */
  const register = async (userData) => {
    try {
      const response = await api.post('/register', userData)
      
      if (response.access_token) {
        authStore.setToken(response.access_token)
        authStore.setUser(response.user)
        return response
      }
    } catch (error) {
      throw error
    }
  }

  /**
   * Logout user
   */
  const logout = async () => {
    try {
      await api.post('/logout')
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      authStore.clearAuth()
      router.push('/login')
    }
  }

  /**
   * Fetch current user data
   * @returns {Promise} - User data
   */
  const fetchUser = async () => {
    try {
      const user = await api.get('/user')
      authStore.setUser(user)
      return user
    } catch (error) {
      // If fetching user fails, clear auth
      logout()
      throw error
    }
  }

  /**
   * Check if user is authenticated
   * @returns {boolean} - Authentication status
   */
  const isAuthenticated = computed(() => {
    return authStore.isAuthenticated
  })

  /**
   * Get current user
   * @returns {object|null} - Current user data
   */
  const currentUser = computed(() => {
    return authStore.user
  })

  /**
   * Check if user has specific role
   * @param {string} role - Role to check
   * @returns {boolean} - Whether user has the role
   */
  const hasRole = (role) => {
    return authStore.user?.role === role
  }

  /**
   * Initialize auth from stored token
   */
  const initAuth = async () => {
    console.log('Initializing auth...')
    if (authStore.token) {
      try {
        await fetchUser()
      } catch (error) {
        console.error('Failed to initialize auth:', error)
        logout()
      }
    }
  }

  return {
    login,
    register,
    logout,
    fetchUser,
    initAuth,
    isAuthenticated,
    currentUser,
    hasRole,
  }
}
