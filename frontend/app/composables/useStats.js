/**
 * Composable for stats-related API calls
 * Provides methods to fetch statistics and project overview data
 */
export const useStats = () => {
  const api = useApi()

  /**
   * Fetch general statistics
   * @returns {Promise<Object>} - Statistics data (total_projects, total_tasks, completed_tasks, completion_percentage)
   */
  const fetchStats = async () => {
    try {
      return await api.get('/stats')
    } catch (error) {
      console.error('Failed to fetch stats:', error)
      throw error
    }
  }

  /**
   * Fetch project overview with detailed metrics
   * @returns {Promise<Array>} - Array of project overview objects with metrics
   */
  const fetchProjectOverview = async () => {
    try {
      return await api.get('/stats/project-overview')
    } catch (error) {
      console.error('Failed to fetch project overview:', error)
      throw error
    }
  }

  return {
    fetchStats,
    fetchProjectOverview,
  }
}
