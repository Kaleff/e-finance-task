<template>
  <div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a]">
    <header class="bg-white dark:bg-[#0a0a0a] shadow-sm border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-medium text-[#1b1b18] dark:text-[#EDEDEC]">My Kanban Board</h1>
        <span class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ userName }}</span>
      </div>
    </header>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <NuxtLink to="/" class="inline-flex items-center gap-2 text-[#f53003] dark:text-[#FF4433] hover:underline text-base font-semibold">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.25L8.25 12l7.5-7.25" />
        </svg>
        <span>Back to Home</span>
      </NuxtLink>
    </div>
      <div v-if="dropLoading || loading" class="fixed inset-0 bg-transparent flex justify-center items-center z-50">
        <CommonBigSpinner />
      </div>
      <div v-else-if="error" class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-sm p-6">
        <p class="text-red-600 dark:text-red-400">{{ error }}</p>
      </div>
      <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <KanbanColumn
          v-for="status in statuses"
          :key="status.value"
          :title="status.label"
          :tasks="tasksByStatus[status.value]"
          :status="status.value"
          @drop-task="handleDropTask"
        />
      </div>
    </main>
  </div>
</template>

<script setup>

const { currentUser } = useAuth()
const api = useApi()

const userName = computed(() => currentUser.value?.name || 'User')
const loading = ref(false)
const dropLoading = ref(false)
const error = ref(null)
const tasks = ref({ todo: [], in_progress: [], done: [] })

const statuses = [
  { value: 'todo', label: 'To Do' },
  { value: 'in_progress', label: 'In Progress' },
  { value: 'done', label: 'Done' }
]

const tasksByStatus = computed(() => tasks.value)

onMounted(async () => {
  await fetchUserTasks()
})

const fetchUserTasks = async () => {
  loading.value = true
  error.value = null
  try {
    // Fetch tasks grouped by status from kanban endpoint
    const response = await api.get('/kanban')
    tasks.value = response || { todo: [], in_progress: [], done: [] }
  } catch (err) {
    error.value = err.message || 'Failed to load tasks'
  } finally {
    loading.value = false
  }
}

const handleDropTask = async ({ task, newStatus }) => {
  dropLoading.value = true
  // Optimistically update UI
  const oldStatus = task.status
  task.status = newStatus
  try {
    await api.patch(`/tasks/${task.id}/status`, { status: newStatus })
    // Optionally refetch tasks for consistency
    await fetchUserTasks()
  } catch (err) {
    // Revert on error
    task.status = oldStatus
    error.value = err.message || 'Failed to update task status'
  } finally {
    dropLoading.value = false
  }
}
</script>

