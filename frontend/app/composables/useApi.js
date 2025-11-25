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
      const response = await $fetch(`${baseURL}${endpoint}`, {
        method: 'GET',
        params,
        headers: getHeaders(),
      })
      return response
    } catch (error) {
      const errorMessage = error.data?.message || error.message || 'Failed to retrieve resource'
      throw new Error(errorMessage)
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

  return {
    get,
    post,
    put,
    patch,
    delete: del,
    baseURL,
  }
}
