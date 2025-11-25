import { defineStore } from 'pinia'

/**
 * UI Store
 * Manages UI state including loading states, errors, and notifications
 */
export const useUiStore = defineStore('ui', {
  state: () => ({
    loading: false,
    error: null,
    notifications: [],
    sidebarOpen: true,
    modalOpen: false,
    modalComponent: null,
    modalProps: {},
  }),

  getters: {
    /**
     * Check if app is loading
     * @param {object} state - Store state
     * @returns {boolean} - Loading state
     */
    isLoading: (state) => state.loading,

    /**
     * Get current error
     * @param {object} state - Store state
     * @returns {string|null} - Error message
     */
    currentError: (state) => state.error,

    /**
     * Get all notifications
     * @param {object} state - Store state
     * @returns {array} - Array of notifications
     */
    allNotifications: (state) => state.notifications,

    /**
     * Check if sidebar is open
     * @param {object} state - Store state
     * @returns {boolean} - Sidebar state
     */
    isSidebarOpen: (state) => state.sidebarOpen,

    /**
     * Check if modal is open
     * @param {object} state - Store state
     * @returns {boolean} - Modal state
     */
    isModalOpen: (state) => state.modalOpen,
  },

  actions: {
    /**
     * Set loading state
     * @param {boolean} value - Loading state
     */
    setLoading(value) {
      this.loading = value
    },

    /**
     * Set error
     * @param {string|null} error - Error message
     */
    setError(error) {
      this.error = error
      if (error) {
        this.addNotification({
          type: 'error',
          message: error,
        })
      }
    },

    /**
     * Clear error
     */
    clearError() {
      this.error = null
    },

    /**
     * Add notification
     * @param {object} notification - Notification object { type, message, duration }
     */
    addNotification(notification) {
      const id = Date.now() + Math.random()
      const newNotification = {
        id,
        type: notification.type || 'info', // success, error, warning, info
        message: notification.message,
        duration: notification.duration || 5000,
      }

      this.notifications.push(newNotification)

      // Auto remove notification after duration
      if (newNotification.duration > 0) {
        setTimeout(() => {
          this.removeNotification(id)
        }, newNotification.duration)
      }
    },

    /**
     * Remove notification
     * @param {number} id - Notification ID
     */
    removeNotification(id) {
      const index = this.notifications.findIndex(n => n.id === id)
      if (index > -1) {
        this.notifications.splice(index, 1)
      }
    },

    /**
     * Clear all notifications
     */
    clearNotifications() {
      this.notifications = []
    },

    /**
     * Toggle sidebar
     */
    toggleSidebar() {
      this.sidebarOpen = !this.sidebarOpen
    },

    /**
     * Set sidebar state
     * @param {boolean} value - Sidebar state
     */
    setSidebar(value) {
      this.sidebarOpen = value
    },

    /**
     * Open modal
     * @param {string} component - Modal component name
     * @param {object} props - Modal props
     */
    openModal(component, props = {}) {
      this.modalComponent = component
      this.modalProps = props
      this.modalOpen = true
    },

    /**
     * Close modal
     */
    closeModal() {
      this.modalOpen = false
      this.modalComponent = null
      this.modalProps = {}
    },

    /**
     * Show success message
     * @param {string} message - Success message
     */
    showSuccess(message) {
      this.addNotification({
        type: 'success',
        message,
      })
    },

    /**
     * Show error message
     * @param {string} message - Error message
     */
    showError(message) {
      this.addNotification({
        type: 'error',
        message,
      })
    },

    /**
     * Show warning message
     * @param {string} message - Warning message
     */
    showWarning(message) {
      this.addNotification({
        type: 'warning',
        message,
      })
    },

    /**
     * Show info message
     * @param {string} message - Info message
     */
    showInfo(message) {
      this.addNotification({
        type: 'info',
        message,
      })
    },
  },
})
