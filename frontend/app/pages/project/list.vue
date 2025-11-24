<template>
  <div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a]">
    <!-- Header -->
    <header class="bg-white dark:bg-[#0a0a0a] shadow-sm border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-2xl font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Projects</h1>
            <p class="mt-1 text-sm text-[#1b1b18]/70 dark:text-[#EDEDEC]/70">
              Manage all your projects
            </p>
          </div>
          <div class="flex gap-3">
            <NuxtLink to="/">
              <CommonBaseButton variant="secondary" size="sm">
                Back to Dashboard
              </CommonBaseButton>
            </NuxtLink>
            <NuxtLink to="/project/create">
              <CommonBaseButton size="sm">
                Create Project
              </CommonBaseButton>
            </NuxtLink>
          </div>
        </div>

        <!-- Filters -->
        <div class="mt-6 flex flex-wrap gap-4">
          <div>
            <select
              v-model="filters.status"
              class="px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] text-sm focus:outline-none focus:ring-2 focus:ring-[#f53003] dark:focus:ring-[#FF4433]"
              @change="handleFilterChange"
            >
              <option value="">All Status</option>
              <option value="planned">Planned</option>
              <option value="in_progress">In Progress</option>
              <option value="completed">Completed</option>
              <option value="archived">Archived</option>
            </select>
          </div>
          
          <div>
            <select
              v-model="filters.deadline_passed"
              class="px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] text-sm focus:outline-none focus:ring-2 focus:ring-[#f53003] dark:focus:ring-[#FF4433]"
              @change="handleFilterChange"
            >
              <option value="">All Deadlines</option>
              <option value="false">Upcoming</option>
              <option value="true">Overdue</option>
            </select>
          </div>

          <CommonBaseButton
            v-if="hasActiveFilters"
            variant="secondary"
            size="sm"
            @click="clearFilters"
          >
            Clear Filters
          </CommonBaseButton>
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

      <!-- Empty State -->
      <div v-else-if="!projects.length" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-[#1b1b18]/40 dark:text-[#EDEDEC]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">No projects found</h3>
        <p class="mt-1 text-sm text-[#1b1b18]/70 dark:text-[#EDEDEC]/70">Get started by creating a new project.</p>
        <div class="mt-6">
          <NuxtLink to="/project/create">
            <CommonBaseButton>
              Create Project
            </CommonBaseButton>
          </NuxtLink>
        </div>
      </div>

      <!-- Projects Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="project in projects"
          :key="project.id"
          class="bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm p-6 hover:border-[#f53003] dark:hover:border-[#FF4433] transition-colors cursor-pointer"
          @click="navigateToProject(project.id)"
        >
          <!-- Project Header -->
          <div class="flex items-start justify-between mb-4">
            <h3 class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC] line-clamp-1">
              {{ project.name }}
            </h3>
            <span :class="getStatusColor(project.status)">
              {{ getStatusLabel(project.status) }}
            </span>
          </div>

          <!-- Project Description -->
          <p class="text-sm text-[#1b1b18]/70 dark:text-[#EDEDEC]/70 line-clamp-2 mb-4">
            {{ project.description || 'No description provided' }}
          </p>

          <!-- Project Stats -->
          <div class="flex items-center gap-4 text-sm text-[#1b1b18]/60 dark:text-[#EDEDEC]/60 mb-4">
            <div class="flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
              </svg>
              <span>{{ project.tasks_count || 0 }} tasks</span>
            </div>
            <div v-if="project.deadline" class="flex items-center gap-1" :class="{ 'text-red-600 dark:text-red-400': isOverdue(project) }">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              <span>{{ formatDate(project.deadline) }}</span>
            </div>
          </div>

          <!-- Progress Bar -->
          <div v-if="project.tasks_count > 0" class="mt-4">
            <div class="flex justify-between text-xs text-[#1b1b18]/60 dark:text-[#EDEDEC]/60 mb-1">
              <span>Progress</span>
              <span>{{ getCompletionRate(project) }}%</span>
            </div>
            <div class="w-full bg-[#e3e3e0] dark:bg-[#3E3E3A] rounded-full h-2">
              <div
                class="bg-[#f53003] dark:bg-[#FF4433] h-2 rounded-full transition-all"
                :style="{ width: `${getCompletionRate(project)}%` }"
              ></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="pagination.total > 10" class="mt-8 flex justify-center">
        <div class="flex gap-2">
          <CommonBaseButton
            variant="secondary"
            size="sm"
            :disabled="pagination.currentPage === 1"
            @click="loadPage(pagination.currentPage - 1)"
          >
            Previous
          </CommonBaseButton>
          <span class="px-4 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
            Page {{ pagination.currentPage }} of {{ pagination.lastPage }}
          </span>
          <CommonBaseButton
            variant="secondary"
            size="sm"
            :disabled="pagination.currentPage === pagination.lastPage"
            @click="loadPage(pagination.currentPage + 1)"
          >
            Next
          </CommonBaseButton>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
definePageMeta({
  middleware: 'auth'
})

const { fetchProjects, projects, pagination, loading } = useProjects()
const router = useRouter()

const filters = ref({
  status: '',
  deadline_passed: ''
})

const error = ref(null)

const hasActiveFilters = computed(() => {
  return filters.value.status || filters.value.deadline_passed
})

// Load projects on mount
onMounted(async () => {
  await loadProjects()
})

const loadProjects = async () => {
  error.value = null
  try {
    const cleanFilters = {}
    if (filters.value.status) cleanFilters.status = filters.value.status
    if (filters.value.deadline_passed) cleanFilters.deadline_passed = filters.value.deadline_passed === 'true'
    
    await fetchProjects(cleanFilters, { page: 1, per_page: 10 })
  } catch (err) {
    error.value = err.message || 'Failed to load projects'
  }
}

const loadPage = async (page) => {
  error.value = null
  try {
    const cleanFilters = {}
    if (filters.value.status) cleanFilters.status = filters.value.status
    if (filters.value.deadline_passed) cleanFilters.deadline_passed = filters.value.deadline_passed === 'true'
    
    await fetchProjects(cleanFilters, { page, per_page: 10 })
  } catch (err) {
    error.value = err.message || 'Failed to load projects'
  }
}

const handleFilterChange = () => {
  loadProjects()
}

const clearFilters = () => {
  filters.value = {
    status: '',
    deadline_passed: ''
  }
  loadProjects()
}

const navigateToProject = (id) => {
  router.push(`/projects/${id}`)
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

const isOverdue = (project) => {
  if (!project.deadline) return false
  return new Date(project.deadline) < new Date() && project.status !== 'completed'
}

const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const getCompletionRate = (project) => {
  if (!project.tasks_count || project.completed_tasks_count === 0) return 0
  return Math.round((project.completed_tasks_count / project.tasks_count) * 100)
}
</script>
