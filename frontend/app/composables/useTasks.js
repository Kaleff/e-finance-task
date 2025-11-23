/**
 * Composable for task operations
 * Handles CRUD operations and task-related business logic
 */
export const useTasks = () => {
  const api = useApi()
  const tasksStore = useTasksStore()
  const uiStore = useUiStore()

  /**
   * Fetch all tasks with optional filters
   * @param {object} filters - Filter parameters (status, priority, assigned_to, project_id)
   * @param {object} pagination - Pagination parameters
   * @returns {Promise} - Tasks data
   */
  const fetchTasks = async (filters = {}, pagination = {}) => {
    try {
      uiStore.setLoading(true)
      
      const params = {
        ...filters,
        page: pagination.page || 1,
        per_page: pagination.per_page || 20,
      }

      const response = await api.get('/tasks', params)
      
      tasksStore.setTasks(response.data)
      tasksStore.setPagination({
        currentPage: response.current_page,
        lastPage: response.last_page,
        total: response.total,
        perPage: response.per_page,
      })

      return response
    } catch (error) {
      uiStore.addNotification({
        type: 'error',
        message: 'Failed to fetch tasks',
      })
      throw error
    } finally {
      uiStore.setLoading(false)
    }
  }

  /**
   * Fetch a single task by ID with comments
   * @param {number} id - Task ID
   * @returns {Promise} - Task data
   */
  const fetchTask = async (id) => {
    try {
      uiStore.setLoading(true)
      
      const task = await api.get(`/tasks/${id}`)
      tasksStore.setCurrentTask(task)
      
      return task
    } catch (error) {
      uiStore.addNotification({
        type: 'error',
        message: 'Failed to fetch task details',
      })
      throw error
    } finally {
      uiStore.setLoading(false)
    }
  }

  /**
   * Create a new task
   * @param {object} taskData - Task data
   * @returns {Promise} - Created task
   */
  const createTask = async (taskData) => {
    try {
      uiStore.setLoading(true)
      
      const task = await api.post('/tasks', taskData)
      
      // Optimistic update
      tasksStore.addTask(task)
      
      uiStore.addNotification({
        type: 'success',
        message: 'Task created successfully',
      })
      
      return task
    } catch (error) {
      uiStore.addNotification({
        type: 'error',
        message: error.message || 'Failed to create task',
      })
      throw error
    } finally {
      uiStore.setLoading(false)
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
      uiStore.setLoading(true)
      
      const task = await api.put(`/tasks/${id}`, taskData)
      
      // Optimistic update
      tasksStore.updateTask(id, task)
      
      uiStore.addNotification({
        type: 'success',
        message: 'Task updated successfully',
      })
      
      return task
    } catch (error) {
      uiStore.addNotification({
        type: 'error',
        message: error.message || 'Failed to update task',
      })
      throw error
    } finally {
      uiStore.setLoading(false)
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
      
      // Optimistic update
      tasksStore.updateTask(id, task)
      
      uiStore.addNotification({
        type: 'success',
        message: 'Task status updated',
      })
      
      return task
    } catch (error) {
      uiStore.addNotification({
        type: 'error',
        message: error.message || 'Failed to update task status',
      })
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
      uiStore.setLoading(true)
      
      await api.delete(`/tasks/${id}`)
      
      // Optimistic update
      tasksStore.removeTask(id)
      
      uiStore.addNotification({
        type: 'success',
        message: 'Task deleted successfully',
      })
    } catch (error) {
      uiStore.addNotification({
        type: 'error',
        message: error.message || 'Failed to delete task',
      })
      throw error
    } finally {
      uiStore.setLoading(false)
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
      tasksStore.setComments(taskId, comments)
      return comments
    } catch (error) {
      uiStore.addNotification({
        type: 'error',
        message: 'Failed to fetch comments',
      })
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
      
      tasksStore.addComment(taskId, newComment)
      
      uiStore.addNotification({
        type: 'success',
        message: 'Comment added successfully',
      })
      
      return newComment
    } catch (error) {
      uiStore.addNotification({
        type: 'error',
        message: 'Failed to add comment',
      })
      throw error
    }
  }

  /**
   * Group tasks by status for Kanban board
   * @returns {object} - Tasks grouped by status
   */
  const tasksByStatus = computed(() => {
    const tasks = tasksStore.tasks
    
    return {
      todo: tasks.filter(t => t.status === 'todo'),
      in_progress: tasks.filter(t => t.status === 'in_progress'),
      review: tasks.filter(t => t.status === 'review'),
      completed: tasks.filter(t => t.status === 'completed'),
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
    tasks: computed(() => tasksStore.tasks),
    currentTask: computed(() => tasksStore.currentTask),
    pagination: computed(() => tasksStore.pagination),
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
