<template>
  <div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a]">
    <!-- Header -->
    <header class="bg-white dark:bg-[#0a0a0a] shadow-sm border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex justify-between items-center">
          <div>
            <div class="flex items-center gap-3">
              <h1 class="text-2xl font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                {{ project?.name || 'Loading...' }}
              </h1>
              <span v-if="project" :class="getStatusColor(project.status)">
                {{ getStatusLabel(project.status) }}
              </span>
            </div>
            <p v-if="project?.description" class="mt-1 text-sm text-[#1b1b18]/70 dark:text-[#EDEDEC]/70">
              {{ project.description }}
            </p>
          </div>
          <div class="flex gap-3">
            <NuxtLink to="/project/list">
              <CommonBaseButton variant="secondary" size="sm">
                Back to Projects
              </CommonBaseButton>
            </NuxtLink>
            <CommonBaseButton size="sm" @click="handleCreateTask">
              Create Task
            </CommonBaseButton>
          </div>
        </div>

        <!-- Project Stats -->
        <div v-if="project" class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm p-4">
            <div class="text-sm text-[#1b1b18]/60 dark:text-[#EDEDEC]/60">Total Tasks</div>
            <div class="text-2xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mt-1">
              {{ tasksPagination.total || 0 }}
            </div>
          </div>
          
          <div class="bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm p-4">
            <div class="text-sm text-[#1b1b18]/60 dark:text-[#EDEDEC]/60">Completed</div>
            <div class="text-2xl font-medium text-green-600 dark:text-green-400 mt-1">
              {{ completedTasksCount }}
            </div>
          </div>
          
          <div class="bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm p-4">
            <div class="text-sm text-[#1b1b18]/60 dark:text-[#EDEDEC]/60">In Progress</div>
            <div class="text-2xl font-medium text-blue-600 dark:text-blue-400 mt-1">
              {{ inProgressTasksCount }}
            </div>
          </div>
          
          <div class="bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm p-4">
            <div class="text-sm text-[#1b1b18]/60 dark:text-[#EDEDEC]/60">Deadline</div>
            <div class="text-lg font-medium mt-1" :class="isProjectOverdue ? 'text-red-600 dark:text-red-400' : 'text-[#1b1b18] dark:text-[#EDEDEC]'">
              {{ project.deadline ? formatDate(project.deadline) : 'No deadline' }}
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#f53003] dark:border-[#FF4433]"></div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-sm p-6">
        <p class="text-red-600 dark:text-red-400">{{ error }}</p>
      </div>

      <!-- Tasks List -->
      <div v-else-if="project">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-xl font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Tasks</h2>
          
          <!-- Status Filter -->
          <div class="flex gap-2">
            <CommonBaseSelect
              v-model="statusFilter"
              @change="handleFilterChange"
            >
              <option value="">All Status</option>
              <option value="todo">To Do</option>
              <option value="in_progress">In Progress</option>
              <option value="done">Done</option>
            </CommonBaseSelect>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="!tasks.length" class="text-center py-12 bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm">
          <svg class="mx-auto h-12 w-12 text-[#1b1b18]/40 dark:text-[#EDEDEC]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
          </svg>
          <h3 class="mt-2 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">No tasks found</h3>
          <p class="mt-1 text-sm text-[#1b1b18]/70 dark:text-[#EDEDEC]/70">Get started by creating a new task.</p>
        </div>

        <!-- Tasks Table -->
        <div v-else class="bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm overflow-hidden">
          <table class="min-w-full divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
            <thead class="bg-[#FDFDFC] dark:bg-[#0a0a0a]">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-[#1b1b18]/60 dark:text-[#EDEDEC]/60 uppercase tracking-wider">
                  Task
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-[#1b1b18]/60 dark:text-[#EDEDEC]/60 uppercase tracking-wider">
                  Status
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-[#1b1b18]/60 dark:text-[#EDEDEC]/60 uppercase tracking-wider">
                  Priority
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-[#1b1b18]/60 dark:text-[#EDEDEC]/60 uppercase tracking-wider">
                  Estimated
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-[#1b1b18]/60 dark:text-[#EDEDEC]/60 uppercase tracking-wider">
                  Comments
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-[#1b1b18]/60 dark:text-[#EDEDEC]/60 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
              <tr
                v-for="task in tasks"
                :key="task.id"
                class="hover:bg-[#FDFDFC] dark:hover:bg-[#1b1b18]/20 transition-colors cursor-pointer"
                @click="navigateToTask(task.id)"
              >
                <td class="px-6 py-4">
                  <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                    {{ task.title }}
                  </div>
                  <div v-if="task.description" class="text-sm text-[#1b1b18]/60 dark:text-[#EDEDEC]/60 line-clamp-1">
                    {{ task.description }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getTaskStatusColor(task.status)">
                    {{ getTaskStatusLabel(task.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getPriorityColor(task.priority)">
                    {{ task.priority ? task.priority.charAt(0).toUpperCase() + task.priority.slice(1) : 'Medium' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#1b1b18]/60 dark:text-[#EDEDEC]/60">
                  {{ task.estimated_hours ? `${task.estimated_hours}h` : '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center gap-1 text-sm text-[#1b1b18]/60 dark:text-[#EDEDEC]/60">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <span>{{ task.comments_count || 0 }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <button
                    @click.stop="navigateToTask(task.id)"
                    class="text-[#f53003] dark:text-[#FF4433] hover:underline"
                  >
                    View
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="tasksPagination.last_page > 1" class="mt-8 flex flex-col items-center gap-4">
          <div class="flex gap-2 items-center">
            <CommonBaseButton
              variant="secondary"
              size="sm"
              :disabled="tasksPagination.current_page === 1"
              @click="loadPage(tasksPagination.current_page - 1)"
            >
              Previous
            </CommonBaseButton>
            
            <!-- Page Numbers -->
            <div class="flex gap-1">
              <button
                v-for="(page, index) in visiblePages"
                :key="`page-${index}`"
                @click="page !== '...' ? loadPage(page) : null"
                :disabled="page === '...'"
                :class="[
                  'px-3 py-1 text-sm rounded-sm transition-colors',
                  page === '...'
                    ? 'bg-transparent text-[#1b1b18]/40 dark:text-[#EDEDEC]/40 cursor-default'
                    : page === tasksPagination.current_page
                      ? 'bg-[#f53003] dark:bg-[#FF4433] text-white'
                      : 'bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] hover:border-[#f53003] dark:hover:border-[#FF4433]'
                ]"
              >
                {{ page }}
              </button>
            </div>
            
            <CommonBaseButton
              variant="secondary"
              size="sm"
              :disabled="tasksPagination.current_page === tasksPagination.last_page"
              @click="loadPage(tasksPagination.current_page + 1)"
            >
              Next
            </CommonBaseButton>
          </div>
          
          <div class="text-sm text-[#1b1b18]/70 dark:text-[#EDEDEC]/70">
            Showing {{ tasksPagination.from }} to {{ tasksPagination.to }} of {{ tasksPagination.total }} tasks
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
definePageMeta({
  middleware: 'auth'
})

const route = useRoute()
const router = useRouter()
const api = useApi()

const project = ref(null)
const tasks = ref([])
const tasksPagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 100,
  total: 0,
  from: 1,
  to: 0
})
const loading = ref(false)
const error = ref(null)
const statusFilter = ref('')

// Load project data
onMounted(async () => {
  await loadProject()
})

const loadProject = async () => {
  loading.value = true
  error.value = null
  
  try {
    // Fetch project details with initial tasks (first page)
    const params = {
      page: 1,
      per_page: 20
    }
    
    const response = await api.get(`/projects/${route.params.id}`, params)
    
    project.value = {
      id: response.id,
      name: response.name,
      description: response.description,
      status: response.status,
      owner_id: response.owner_id,
      deadline: response.deadline,
      created_at: response.created_at,
      updated_at: response.updated_at
    }
    
    // Load initial tasks from project response
    tasks.value = response.tasks || []
    tasksPagination.value = response.tasks_pagination || {
      current_page: 1,
      last_page: 1,
      per_page: 20,
      total: 0,
      from: 1,
      to: 0
    }
  } catch (err) {
    error.value = err.message || 'Failed to load project'
  } finally {
    loading.value = false
  }
}

const loadTasks = async (page = 1) => {
  loading.value = true
  error.value = null
  
  try {
    const params = {
      project_id: route.params.id,
      page,
      per_page: 20
    }
    
    // Add status filter if selected
    if (statusFilter.value) {
      params.status = statusFilter.value
    }
    
    const response = await api.get('/tasks', params)
    
    tasks.value = response.data || []
    tasksPagination.value = {
      current_page: response.current_page || 1,
      last_page: response.last_page || 1,
      per_page: response.per_page || 20,
      total: response.total || 0,
      from: response.from || 0,
      to: response.to || 0
    }
  } catch (err) {
    console.error('Failed to load tasks:', err)
    error.value = err.message || 'Failed to load tasks'
  } finally {
    loading.value = false
  }
}

const loadPage = async (page) => {
  await loadTasks(page)
}

const handleFilterChange = () => {
  // Reset to page 1 when filter changes and load from API
  loadTasks(1)
}

const handleCreateTask = () => {
  router.push({path: `/task/create`, state: { projectId: project.value.id, projectName: project.value.name }})
}

const navigateToTask = (taskId) => {
  router.push(`/task/${taskId}`)
}

// Computed properties
const completedTasksCount = computed(() => {
  // If no filter applied, show from total
  if (!statusFilter.value) {
    return tasks.value.filter(t => t.status === 'done').length
  }
  return tasks.value.filter(t => t.status === 'done').length
})

const inProgressTasksCount = computed(() => {
  // If no filter applied, show from total
  if (!statusFilter.value) {
    return tasks.value.filter(t => t.status === 'in_progress').length
  }
  return tasks.value.filter(t => t.status === 'in_progress').length
})

const isProjectOverdue = computed(() => {
  if (!project.value?.deadline) return false
  return new Date(project.value.deadline) < new Date() && project.value.status !== 'completed'
})

const visiblePages = computed(() => {
  if (!tasksPagination.value.last_page) return []
  
  const current = tasksPagination.value.current_page
  const last = tasksPagination.value.last_page
  const delta = 2
  const pages = []
  
  pages.push(1)
  
  const rangeStart = Math.max(2, current - delta)
  const rangeEnd = Math.min(last - 1, current + delta)
  
  if (rangeStart > 2) {
    pages.push('...')
  }
  
  for (let i = rangeStart; i <= rangeEnd; i++) {
    pages.push(i)
  }
  
  if (rangeEnd < last - 1) {
    pages.push('...')
  }
  
  if (last > 1) {
    pages.push(last)
  }
  
  return pages
})

// Helper functions
const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const getStatusColor = (status) => {
  const colors = {
    planned: 'px-2 py-1 text-xs rounded-sm bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300',
    in_progress: 'px-2 py-1 text-xs rounded-sm bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
    completed: 'px-2 py-1 text-xs rounded-sm bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
    archived: 'px-2 py-1 text-xs rounded-sm bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400'
  }
  return colors[status] || colors.planned
}

const getStatusLabel = (status) => {
  const labels = {
    planned: 'Planned',
    in_progress: 'In Progress',
    completed: 'Completed',
    archived: 'Archived'
  }
  return labels[status] || status
}

const getTaskStatusColor = (status) => {
  const colors = {
    todo: 'px-2 py-1 text-xs rounded-sm bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300',
    in_progress: 'px-2 py-1 text-xs rounded-sm bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
    done: 'px-2 py-1 text-xs rounded-sm bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400'
  }
  return colors[status] || colors.todo
}

const getTaskStatusLabel = (status) => {
  const labels = {
    todo: 'To Do',
    in_progress: 'In Progress',
    done: 'Done'
  }
  return labels[status] || status
}

const getPriorityColor = (priority) => {
  const colors = {
    low: 'px-2 py-1 text-xs rounded-sm bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
    medium: 'px-2 py-1 text-xs rounded-sm bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
    high: 'px-2 py-1 text-xs rounded-sm bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400'
  }
  return colors[priority] || colors.medium
}
</script>
