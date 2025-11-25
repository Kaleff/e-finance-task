/**
 * Composable for task operations
 * Handles CRUD operations and task-related business logic
 */
export const useTasks = () => {
  const api = useApi()
  const tasks = ref([])
  const currentTask = ref(null)
  const pagination = ref({})
  const loading = ref(false)

  /**
   * Fetch all tasks with optional filters
   * @param {object} filters - Filter parameters (status, priority, assigned_to, project_id)
   * @param {object} paginationParams - Pagination parameters
   * @returns {Promise} - Tasks data
   */
  const fetchTasks = async (filters = {}, paginationParams = {}) => {
    try {
      loading.value = true
      
      const params = {
        ...filters,
        page: paginationParams.page || 1,
        per_page: paginationParams.per_page || 20,
      }

      const response = await api.get('/tasks/index', params)
      
      tasks.value = response.data || response
      pagination.value = {
        currentPage: response.current_page,
        lastPage: response.last_page,
        total: response.total,
        perPage: response.per_page,
      }

      return response
    } catch (error) {
      console.error('Failed to fetch tasks:', error)
      throw error
    } finally {
      loading.value = false
    }
  }

  /**
   * Fetch a single task by ID with comments
   * @param {number} id - Task ID
   * @returns {Promise} - Task data
   */
  const fetchTask = async (id) => {
    try {
      loading.value = true
      const task = await api.get(`/tasks/${id}`)
      currentTask.value = task
      return task
    } catch (error) {
      console.error('Failed to fetch task:', error)
      throw error
    } finally {
      loading.value = false
    }
  }

  /**
   * Create a new task
   * @param {object} taskData - Task data
   * @returns {Promise} - Created task
   */
  const createTask = async (taskData) => {
    try {
      loading.value = true
      const task = await api.post('/tasks/', taskData)
      return task
    } catch (error) {
      console.error('Failed to create task:', error)
      throw error
    } finally {
      loading.value = false
    }
  }

  /**
   * Update an existing task
   * @param {number} id - Task ID
   * @param {object} taskData - Updated task data
   * @returns {Promise} - Updated task
   */
  const updateTask = async (id, taskData) => {
    try {
      loading.value = true
      const task = await api.put(`/tasks/${id}`, taskData)
      return task
    } catch (error) {
      console.error('Failed to update task:', error)
      throw error
    } finally {
      loading.value = false
    }
  }

  /**
   * Update task status only
   * @param {number} id - Task ID
   * @param {string} status - New status
   * @returns {Promise} - Updated task
   */
  const updateTaskStatus = async (id, status) => {
    try {
      const task = await api.patch(`/tasks/${id}/status`, { status })
      return task
    } catch (error) {
      console.error('Failed to update task status:', error)
      throw error
    }
  }

  /**
   * Delete a task
   * @param {number} id - Task ID
   * @returns {Promise}
   */
  const deleteTask = async (id) => {
    try {
      loading.value = true
      await api.delete(`/tasks/${id}`)
    } catch (error) {
      console.error('Failed to delete task:', error)
      throw error
    } finally {
      loading.value = false
    }
  }

  /**
   * Fetch comments for a task
   * @param {number} taskId - Task ID
   * @returns {Promise} - Comments array
   */
  const fetchComments = async (taskId) => {
    try {
      const comments = await api.get(`/tasks/${taskId}/comments`)
      return comments
    } catch (error) {
      console.error('Failed to fetch comments:', error)
      throw error
    }
  }

  /**
   * Add a comment to a task
   * @param {number} taskId - Task ID
   * @param {string} comment - Comment text
   * @returns {Promise} - Created comment
   */
  const addComment = async (taskId, comment) => {
    try {
      const newComment = await api.post(`/tasks/${taskId}/comments`, { comment })
      return newComment
    } catch (error) {
      console.error('Failed to add comment:', error)
      throw error
    }
  }

  /**
   * Group tasks by status for Kanban board
   * @returns {object} - Tasks grouped by status
   */
  const tasksByStatus = computed(() => {
    return {
      todo: tasks.value.filter(t => t.status === 'todo'),
      in_progress: tasks.value.filter(t => t.status === 'in_progress'),
      done: tasks.value.filter(t => t.status === 'done'),
    }
  })

  /**
   * Get task priority color
   * @param {string} priority - Task priority
   * @returns {string} - Color class
   */
  const getPriorityColor = (priority) => {
    const colors = {
      'low': 'green',
      'medium': 'yellow',
      'high': 'orange',
      'urgent': 'red',
    }
    
    return colors[priority] || 'gray'
  }

  /**
   * Get task status color
   * @param {string} status - Task status
   * @returns {string} - Color class
   */
  const getStatusColor = (status) => {
    const colors = {
      'todo': 'gray',
      'in_progress': 'blue',
      'review': 'purple',
      'completed': 'green',
      'blocked': 'red',
    }
    
    return colors[status] || 'gray'
  }

  /**
   * Check if task is overdue
   * @param {object} task - Task object
   * @returns {boolean}
   */
  const isOverdue = (task) => {
    if (!task.deadline) return false
    
    const deadline = new Date(task.deadline)
    const now = new Date()
    
    return now > deadline && task.status !== 'completed'
  }

  /**
   * Calculate task progress percentage
   * @param {object} task - Task object
   * @returns {number} - Progress percentage (0-100)
   */
  const getProgress = (task) => {
    if (task.status === 'completed') return 100
    if (task.status === 'todo') return 0
    if (task.status === 'in_progress') return 50
    if (task.status === 'review') return 80
    return 0
  }

  return {
    // Data
    tasks,
    currentTask,
    pagination,
    loading,
    tasksByStatus,
    
    // Methods
    fetchTasks,
    fetchTask,
    createTask,
    updateTask,
    updateTaskStatus,
    deleteTask,
    fetchComments,
    addComment,
    
    // Helpers
    getPriorityColor,
    getStatusColor,
    isOverdue,
    getProgress,
  }
}
