/**
 * Composable for managing filters
 * Provides reusable filtering logic with debouncing
 */
export const useFilters = (initialFilters = {}) => {
  const filters = ref({ ...initialFilters })
  const debouncedFilters = ref({ ...initialFilters })

  /**
   * Update a single filter
   * @param {string} key - Filter key
   * @param {any} value - Filter value
   */
  const updateFilter = (key, value) => {
    filters.value[key] = value
  }

  /**
   * Update multiple filters at once
   * @param {object} newFilters - Object with filter key-value pairs
   */
  const updateFilters = (newFilters) => {
    filters.value = {
      ...filters.value,
      ...newFilters,
    }
  }

  /**
   * Reset filters to initial state
   */
  const resetFilters = () => {
    filters.value = { ...initialFilters }
    debouncedFilters.value = { ...initialFilters }
  }

  /**
   * Reset a single filter
   * @param {string} key - Filter key to reset
   */
  const resetFilter = (key) => {
    if (key in initialFilters) {
      filters.value[key] = initialFilters[key]
    } else {
      delete filters.value[key]
    }
  }

  /**
   * Get active filters (non-empty values)
   * @returns {object} - Active filters
   */
  const activeFilters = computed(() => {
    return Object.entries(filters.value).reduce((acc, [key, value]) => {
      // Include filter if value is not null, undefined, or empty string
      if (value !== null && value !== undefined && value !== '') {
        acc[key] = value
      }
      return acc
    }, {})
  })

  /**
   * Count of active filters
   * @returns {number} - Number of active filters
   */
  const activeFilterCount = computed(() => {
    return Object.keys(activeFilters.value).length
  })

  /**
   * Check if filters have changed from initial state
   * @returns {boolean} - Whether filters have changed
   */
  const hasActiveFilters = computed(() => {
    return activeFilterCount.value > 0
  })

  /**
   * Debounced version of filters for search/API calls
   * Updates after 300ms of no changes
   */
  const debounceTimeout = ref(null)
  
  watch(
    filters,
    (newFilters) => {
      if (debounceTimeout.value) {
        clearTimeout(debounceTimeout.value)
      }

      debounceTimeout.value = setTimeout(() => {
        debouncedFilters.value = { ...newFilters }
      }, 300)
    },
    { deep: true }
  )

  /**
   * Get query string from active filters
   * @returns {string} - URL query string
   */
  const toQueryString = computed(() => {
    const params = new URLSearchParams()
    
    Object.entries(activeFilters.value).forEach(([key, value]) => {
      if (Array.isArray(value)) {
        value.forEach(v => params.append(`${key}[]`, v))
      } else {
        params.append(key, value)
      }
    })
    
    return params.toString()
  })

  /**
   * Apply filters to a dataset (client-side filtering)
   * @param {array} data - Data to filter
   * @param {object} filterConfig - Configuration for how to apply filters
   * @returns {array} - Filtered data
   */
  const applyFilters = (data, filterConfig = {}) => {
    let filteredData = [...data]

    Object.entries(activeFilters.value).forEach(([key, value]) => {
      const config = filterConfig[key]
      
      if (config && typeof config === 'function') {
        // Custom filter function
        filteredData = filteredData.filter(item => config(item, value))
      } else {
        // Default filtering: exact match or includes
        filteredData = filteredData.filter(item => {
          const itemValue = item[key]
          
          if (Array.isArray(value)) {
            return value.includes(itemValue)
          }
          
          if (typeof value === 'string') {
            return String(itemValue).toLowerCase().includes(value.toLowerCase())
          }
          
          return itemValue === value
        })
      }
    })

    return filteredData
  }

  /**
   * Sync filters with URL query parameters
   */
  const syncWithRoute = () => {
    const route = useRoute()
    const router = useRouter()

    // Read filters from route on mount
    onMounted(() => {
      const queryFilters = { ...route.query }
      if (Object.keys(queryFilters).length > 0) {
        updateFilters(queryFilters)
      }
    })

    // Update route when filters change
    watch(
      debouncedFilters,
      () => {
        const query = { ...activeFilters.value }
        router.push({ query })
      },
      { deep: true }
    )
  }

  // Cleanup
  onUnmounted(() => {
    if (debounceTimeout.value) {
      clearTimeout(debounceTimeout.value)
    }
  })

  return {
    filters,
    debouncedFilters,
    activeFilters,
    activeFilterCount,
    hasActiveFilters,
    toQueryString,
    updateFilter,
    updateFilters,
    resetFilter,
    resetFilters,
    applyFilters,
    syncWithRoute,
  }
}
