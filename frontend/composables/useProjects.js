/**
 * Composable for project operations
 * Handles CRUD operations and project-related business logic
 */
export const useProjects = () => {
  const api = useApi()
  const projectsStore = useProjectsStore()
  const uiStore = useUiStore()

  /**
   * Fetch all projects with optional filters
   * @param {object} filters - Filter parameters (status, owner_id, etc.)
   * @param {object} pagination - Pagination parameters (page, per_page)
   * @returns {Promise} - Projects data
   */
  const fetchProjects = async (filters = {}, pagination = {}) => {
    try {
      uiStore.setLoading(true)
      
      const params = {
        ...filters,
        page: pagination.page || 1,
        per_page: pagination.per_page || 10,
      }

      const response = await api.get('/projects', params)
      
      projectsStore.setProjects(response.data)
      projectsStore.setPagination({
        currentPage: response.current_page,
        lastPage: response.last_page,
        total: response.total,
        perPage: response.per_page,
      })

      return response
    } catch (error) {
      uiStore.addNotification({
        type: 'error',
        message: 'Failed to fetch projects',
      })
      throw error
    } finally {
      uiStore.setLoading(false)
    }
  }

  /**
   * Fetch a single project by ID with related tasks
   * @param {number} id - Project ID
   * @returns {Promise} - Project data
   */
  const fetchProject = async (id) => {
    try {
      uiStore.setLoading(true)
      
      const project = await api.get(`/projects/${id}`)
      projectsStore.setCurrentProject(project)
      
      return project
    } catch (error) {
      uiStore.addNotification({
        type: 'error',
        message: 'Failed to fetch project details',
      })
      throw error
    } finally {
      uiStore.setLoading(false)
    }
  }

  /**
   * Create a new project
   * @param {object} projectData - Project data
   * @returns {Promise} - Created project
   */
  const createProject = async (projectData) => {
    try {
      uiStore.setLoading(true)
      
      const project = await api.post('/projects', projectData)
      
      // Optimistic update
      projectsStore.addProject(project)
      
      uiStore.addNotification({
        type: 'success',
        message: 'Project created successfully',
      })
      
      return project
    } catch (error) {
      uiStore.addNotification({
        type: 'error',
        message: error.message || 'Failed to create project',
      })
      throw error
    } finally {
      uiStore.setLoading(false)
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
      uiStore.setLoading(true)
      
      const project = await api.put(`/projects/${id}`, projectData)
      
      // Optimistic update
      projectsStore.updateProject(id, project)
      
      uiStore.addNotification({
        type: 'success',
        message: 'Project updated successfully',
      })
      
      return project
    } catch (error) {
      uiStore.addNotification({
        type: 'error',
        message: error.message || 'Failed to update project',
      })
      throw error
    } finally {
      uiStore.setLoading(false)
    }
  }

  /**
   * Delete a project
   * @param {number} id - Project ID
   * @returns {Promise}
   */
  const deleteProject = async (id) => {
    try {
      uiStore.setLoading(true)
      
      await api.delete(`/projects/${id}`)
      
      // Optimistic update
      projectsStore.removeProject(id)
      
      uiStore.addNotification({
        type: 'success',
        message: 'Project deleted successfully',
      })
    } catch (error) {
      uiStore.addNotification({
        type: 'error',
        message: error.message || 'Failed to delete project',
      })
      throw error
    } finally {
      uiStore.setLoading(false)
    }
  }

  /**
   * Get project statistics
   * @param {number} id - Project ID
   * @returns {object} - Project statistics
   */
  const getProjectStats = computed(() => {
    const project = projectsStore.currentProject
    
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
    return projectsStore.projects.filter(p => p.status === status)
  }

  return {
    // Data
    projects: computed(() => projectsStore.projects),
    currentProject: computed(() => projectsStore.currentProject),
    pagination: computed(() => projectsStore.pagination),
    
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
