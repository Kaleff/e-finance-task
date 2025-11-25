import { defineStore } from 'pinia'

/**
 * Authentication Store
 * Manages user authentication state, token, and user data
 */
export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null,
    isAuthenticated: false,
  }),

  getters: {
    /**
     * Get current user
     * @param {object} state - Store state
     * @returns {object|null} - Current user
     */
    currentUser: (state) => state.user,

    /**
     * Get authentication token
     * @param {object} state - Store state
     * @returns {string|null} - Auth token
     */
    authToken: (state) => state.token,

    /**
     * Check if user is authenticated
     * @param {object} state - Store state
     * @returns {boolean} - Authentication status
     */
    isLoggedIn: (state) => state.isAuthenticated && !!state.token,

    /**
     * Get user role
     * @param {object} state - Store state
     * @returns {string|null} - User role
     */
    userRole: (state) => state.user?.role || null,

    /**
     * Get user ID
     * @param {object} state - Store state
     * @returns {number|null} - User ID
     */
    userId: (state) => state.user?.id || null,

    /**
     * Get user name
     * @param {object} state - Store state
     * @returns {string} - User name
     */
    userName: (state) => state.user?.name || 'Guest',

    /**
     * Get user email
     * @param {object} state - Store state
     * @returns {string|null} - User email
     */
    userEmail: (state) => state.user?.email || null,
  },

  actions: {
    /**
     * Set user data
     * @param {object} user - User data
     */
    setUser(user) {
      this.user = user
      this.isAuthenticated = true
    },

    /**
     * Set authentication token
     * @param {string} token - Auth token
     */
    setToken(token) {
      this.token = token
      this.isAuthenticated = true
    },

    /**
     * Update user data
     * @param {object} userData - Partial user data to update
     */
    updateUser(userData) {
      if (this.user) {
        this.user = {
          ...this.user,
          ...userData,
        }
      }
    },

    /**
     * Clear authentication state (logout)
     */
    clearAuth() {
      this.user = null
      this.token = null
      this.isAuthenticated = false
    },

    /**
     * Check if user has specific role
     * @param {string} role - Role to check
     * @returns {boolean} - Whether user has the role
     */
    hasRole(role) {
      return this.user?.role === role
    },

    /**
     * Check if user has any of the specified roles
     * @param {array} roles - Array of roles to check
     * @returns {boolean} - Whether user has any of the roles
     */
    hasAnyRole(roles) {
      return roles.includes(this.user?.role)
    },

    /**
     * Check if user is admin
     * @returns {boolean} - Whether user is admin
     */
    isAdmin() {
      return this.user?.role === 'admin'
    },
  },

  persist: true,
})
