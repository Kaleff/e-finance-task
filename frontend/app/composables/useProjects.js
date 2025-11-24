/**
 * Composable for project operations
 * Handles CRUD operations and project-related business logic
 */
export const useProjects = () => {
  const api = useApi()
  const projects = ref([])
  const currentProject = ref(null)
  const pagination = ref({})
  const loading = ref(false)

  /**
   * Fetch all projects with optional filters
   * @param {object} filters - Filter parameters (status, owner_id, etc.)
   * @param {object} paginationParams - Pagination parameters (page, per_page)
   * @returns {Promise} - Projects data
   */
  const fetchProjects = async (filters = {}, paginationParams = {}) => {
    try {
      loading.value = true
      
      const params = {
        ...filters,
        page: paginationParams.page || 1,
        per_page: paginationParams.per_page || 10,
      }

      const response = await api.get('/projects', params)
      
      projects.value = response.data || response
      pagination.value = {
        currentPage: response.current_page,
        lastPage: response.last_page,
        total: response.total,
        perPage: response.per_page,
      }

      return response
    } catch (error) {
      console.error('Failed to fetch projects:', error)
      throw error
    } finally {
      loading.value = false
    }
  }

  /**
   * Fetch a single project by ID with related tasks
   * @param {number} id - Project ID
   * @returns {Promise} - Project data
   */
  const fetchProject = async (id) => {
    try {
      loading.value = true
      const project = await api.get(`/projects/${id}`)
      currentProject.value = project
      return project
    } catch (error) {
      console.error('Failed to fetch project:', error)
      throw error
    } finally {
      loading.value = false
    }
  }

  /**
   * Create a new project
   * @param {object} projectData - Project data
   * @returns {Promise} - Created project
   */
  const createProject = async (projectData) => {
    try {
      loading.value = true
      const project = await api.post('/projects', projectData)
      return project
    } catch (error) {
      console.error('Failed to create project:', error)
      throw error
    } finally {
      loading.value = false
    }
  }

  /**
   * Update an existing project
   * @param {number} id - Project ID
   * @param {object} projectData - Updated project data
   * @returns {Promise} - Updated project
   */
  const updateProject = async (id, projectData) => {
    try {
      loading.value = true
      const project = await api.put(`/projects/${id}`, projectData)
      return project
    } catch (error) {
      console.error('Failed to update project:', error)
      throw error
    } finally {
      loading.value = false
    }
  }

  /**
   * Delete a project
   * @param {number} id - Project ID
   * @returns {Promise}
   */
  const deleteProject = async (id) => {
    try {
      loading.value = true
      await api.delete(`/projects/${id}`)
    } catch (error) {
      console.error('Failed to delete project:', error)
      throw error
    } finally {
      loading.value = false
    }
  }

  /**
   * Get project statistics
   * @returns {object} - Project statistics
   */
  const getProjectStats = computed(() => {
    const project = currentProject.value
    
    if (!project || !project.tasks) {
      return {
        totalTasks: 0,
        completedTasks: 0,
        inProgressTasks: 0,
        completionRate: 0,
        totalEstimatedHours: 0,
        totalActualHours: 0,
      }
    }

    const tasks = project.tasks
    const totalTasks = tasks.length
    const completedTasks = tasks.filter(t => t.status === 'completed').length
    const inProgressTasks = tasks.filter(t => t.status === 'in_progress').length
    
    return {
      totalTasks,
      completedTasks,
      inProgressTasks,
      completionRate: totalTasks > 0 ? (completedTasks / totalTasks) * 100 : 0,
      totalEstimatedHours: tasks.reduce((sum, t) => sum + (t.estimated_hours || 0), 0),
      totalActualHours: tasks.reduce((sum, t) => sum + (t.actual_hours || 0), 0),
    }
  })

  /**
   * Check if project is overdue
   * @param {object} project - Project object
   * @returns {boolean}
   */
  const isOverdue = (project) => {
    if (!project.deadline) return false
    
    const deadline = new Date(project.deadline)
    const now = new Date()
    
    return now > deadline && project.status !== 'completed'
  }

  /**
   * Get project status color
   * @param {string} status - Project status
   * @returns {string} - Color class
   */
  const getStatusColor = (status) => {
    const colors = {
      'pending': 'gray',
      'in_progress': 'blue',
      'completed': 'green',
      'on_hold': 'yellow',
      'cancelled': 'red',
    }
    
    return colors[status] || 'gray'
  }

  /**
   * Filter projects by status
   * @param {string} status - Status to filter by
   * @returns {array} - Filtered projects
   */
  const filterByStatus = (status) => {
    return projects.value.filter(p => p.status === status)
  }

  return {
    // Data
    projects,
    currentProject,
    pagination,
    loading,
    
    // Methods
    fetchProjects,
    fetchProject,
    createProject,
    updateProject,
    deleteProject,
    
    // Helpers
    getProjectStats,
    isOverdue,
    getStatusColor,
    filterByStatus,
  }
}
