<template>
  <div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a]">
    <!-- Header -->
    <header class="bg-white dark:bg-[#0a0a0a] shadow-sm border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Create Task</h1>
          <p v-if="projectName" class="mt-1 text-sm text-[#1b1b18]/70 dark:text-[#EDEDEC]/70">
            for project: <span class="font-medium">{{ projectName }}</span>
          </p>
        </div>
        <NuxtLink :to="projectId ? `/project/${projectId}` : '/project/list'">
          <CommonBaseButton variant="secondary" size="sm">
            Back to Project
          </CommonBaseButton>
        </NuxtLink>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="bg-white dark:bg-[#0a0a0a] rounded-sm shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
        <form @submit.prevent="handleSubmit">
          <!-- Project Info (Read-only) -->
          <div class="mb-6">
            <label class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
              Project
            </label>
            <div class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#1b1b18]/20 text-[#1b1b18] dark:text-[#EDEDEC]">
              {{ projectName }}
            </div>
            <input type="hidden" name="project_id" :value="projectId" />
          </div>

          <!-- Task Title -->
          <div class="mb-6">
            <CommonBaseInput
              v-model="form.title"
              label="Task Title"
              placeholder="Enter task title"
              required
            />
          </div>

          <!-- Description -->
          <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
              Description
            </label>
            <textarea
              id="description"
              v-model="form.description"
              rows="4"
              class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-[#f53003] dark:focus:ring-[#FF4433]"
              placeholder="Enter task description"
            ></textarea>
          </div>

          <!-- Status and Priority -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <CommonBaseSelect
                v-model="form.status"
                label="Status"
              >
                <option value="todo">To Do</option>
                <option value="in_progress">In Progress</option>
                <option value="done">Done</option>
              </CommonBaseSelect>
            </div>

            <div>
              <CommonBaseSelect
                v-model="form.priority"
                label="Priority"
              >
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
              </CommonBaseSelect>
            </div>
          </div>

          <!-- Estimated Hours -->
          <div class="mb-6">
            <CommonBaseInput
              v-model.number="form.estimated_hours"
              type="number"
              label="Estimated Hours"
              placeholder="0"
            />
          </div>

          <!-- Error Message -->
          <div v-if="error" class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-sm">
            <p class="text-sm text-red-600 dark:text-red-400">{{ error }}</p>
          </div>

          <!-- Submit Button -->
          <div class="flex gap-3">
            <CommonBaseButton
              type="submit"
              :disabled="loading"
              class="flex-1"
            >
              {{ loading ? 'Creating...' : 'Create Task' }}
            </CommonBaseButton>
            <NuxtLink :to="projectId ? `/project/${projectId}` : '/project/list'" class="flex-1">
              <CommonBaseButton variant="secondary" class="w-full">
                Cancel
              </CommonBaseButton>
            </NuxtLink>
          </div>
        </form>
      </div>
    </main>
  </div>
</template>

<script setup>
definePageMeta({
  middleware: 'auth'
})

const { createTask } = useTasks()
const router = useRouter()
const route = useRoute()
const uiStore = useUiStore()

const form = ref({
  project_id: '',
  title: '',
  description: '',
  status: 'todo',
  priority: 'medium',
  estimated_hours: null
})

const loading = ref(false)
const error = ref(null)
const projectName = ref('')
const projectId = ref(null)

// Access router push state
onMounted(() => {
  // Option 1: Access from history.state (for state passed via router.push)
  const state = history.state
  if (state?.projectId && state?.projectName) {
    projectId.value = state.projectId
    projectName.value = state.projectName
    form.value.project_id = state.projectId
  }
  
  // Option 2: Fallback to query params (for query string parameters)
  else if (route.query.project_id && route.query.project_name) {
    projectId.value = parseInt(route.query.project_id)
    projectName.value = route.query.project_name
    form.value.project_id = projectId.value
  }
  
  // Redirect if no project info provided
  else {
    error.value = 'Tasks must be created from a project page. Redirecting...'
    setTimeout(() => {
      router.push('/project/list')
    }, 2000)
  }
})

const handleSubmit = async () => {
  loading.value = true
  error.value = null

  try {
    await createTask(form.value)
    
    // Show success notification
    uiStore.addNotification({
      type: 'success',
      message: 'Task created successfully!'
    })
    
    // Redirect to project page
    router.push(`/project/${projectId.value}`)
  } catch (err) {
    error.value = err.message || 'Failed to create task'
    
    // Show error notification
    uiStore.addNotification({
      type: 'error',
      message: err.message || 'Failed to create task'
    })
  } finally {
    loading.value = false
  }
}
</script>
