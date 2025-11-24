/**
 * Composable for API calls
 * Provides a centralized way to make HTTP requests with error handling
 */
export const useApi = () => {
  const config = useRuntimeConfig()
  const baseURL = config.public.apiBase || 'http://localhost:8000/api'

  /**
   * Make a GET request
   * @param {string} endpoint - API endpoint
   * @param {object} params - Query parameters
   * @returns {Promise} - API response
   */
  const get = async (endpoint, params = {}) => {
    try {
      const { data, error } = await useFetch(`${baseURL}${endpoint}`, {
        method: 'GET',
        params,
        headers: getHeaders(),
      })

      if (error.value) {
        throw new Error(error.value.message || 'Failed to fetch data')
      }

      return data.value
    } catch (err) {
      handleError(err)
      throw err
    }
  }

  /**
   * Make a POST request
   * @param {string} endpoint - API endpoint
   * @param {object} body - Request body
   * @returns {Promise} - API response
   */
  const post = async (endpoint, body = {}) => {
    try {
      const response = await $fetch(`${baseURL}${endpoint}`, {
        method: 'POST',
        body,
        headers: getHeaders(),
      })
      return response
    } catch (error) {
      const errorMessage = error.data?.message || error.message || 'Failed to create resource'
      throw new Error(errorMessage)
    }
  }

  /**
   * Make a PUT request
   * @param {string} endpoint - API endpoint
   * @param {object} body - Request body
   * @returns {Promise} - API response
   */
  const put = async (endpoint, body = {}) => {
    try {
      const response = await $fetch(`${baseURL}${endpoint}`, {
        method: 'PUT',
        body,
        headers: getHeaders(),
      })
      return response
    } catch (error) {
      const errorMessage = error.data?.message || error.message || 'Failed to update resource'
      throw new Error(errorMessage)
    }
  }

  /**
   * Make a PATCH request
   * @param {string} endpoint - API endpoint
   * @param {object} body - Request body
   * @returns {Promise} - API response
   */
  const patch = async (endpoint, body = {}) => {
    try {
      const response = await $fetch(`${baseURL}${endpoint}`, {
        method: 'PATCH',
        body,
        headers: getHeaders(),
      })
      return response
    } catch (error) {
      const errorMessage = error.data?.message || error.message || 'Failed to update resource'
      throw new Error(errorMessage)
    }
  }

  /**
   * Make a DELETE request
   * @param {string} endpoint - API endpoint
   * @returns {Promise} - API response
   */
  const del = async (endpoint) => {
    try {
      const response = await $fetch(`${baseURL}${endpoint}`, {
        method: 'DELETE',
        headers: getHeaders(),
      })
      return response
    } catch (error) {
      const errorMessage = error.data?.message || error.message || 'Failed to delete resource'
      throw new Error(errorMessage)
    }
  }

  /**
   * Get headers for API requests
   * @returns {object} - Request headers
   */
  const getHeaders = () => {
    const headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    }

    // Add authorization token from auth store
    const authStore = useAuthStore()
    if (authStore.token) {
      headers['Authorization'] = `Bearer ${authStore.token}`
    }

    return headers
  }

  /**
   * Handle API errors
   * @param {Error} error - Error object
   */
  const handleError = (error) => {
    console.error('API Error:', error)
    
    // You can add more sophisticated error handling here
    // For example, redirect to login on 401, show notifications, etc.
    const uiStore = useUiStore()
    
    if (error.response?.status === 401) {
      uiStore.addNotification({
        type: 'error',
        message: 'Unauthorized. Please login again.',
      })
      navigateTo('/login')
    } else if (error.response?.status === 403) {
      uiStore.addNotification({
        type: 'error',
        message: 'You do not have permission to perform this action.',
      })
    } else if (error.response?.status === 404) {
      uiStore.addNotification({
        type: 'error',
        message: 'Resource not found.',
      })
    } else if (error.response?.status >= 500) {
      uiStore.addNotification({
        type: 'error',
        message: 'Server error. Please try again later.',
      })
    } else {
      uiStore.addNotification({
        type: 'error',
        message: error.message || 'An error occurred.',
      })
    }
  }

  return {
    get,
    post,
    put,
    patch,
    delete: del,
    baseURL,
  }
}
