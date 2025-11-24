<template>
  <div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a]">
    <!-- Header -->
    <header class="bg-white dark:bg-[#0a0a0a] shadow-sm border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Create Task</h1>
        <NuxtLink to="/">
          <CommonBaseButton variant="secondary" size="sm">
            Back to Dashboard
          </CommonBaseButton>
        </NuxtLink>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="bg-white dark:bg-[#0a0a0a] rounded-sm shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
        <form @submit.prevent="handleSubmit">
          <!-- Project Selection -->
          <div class="mb-6">
            <CommonBaseSelect
              v-model="form.project_id"
              label="Project"
              placeholder="Select a project"
              required
            >
              <option v-for="project in projects" :key="project.id" :value="project.id">
                {{ project.name }}
              </option>
            </CommonBaseSelect>
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
            <NuxtLink to="/" class="flex-1">
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
const { fetchProjects, projects } = useProjects()
const router = useRouter()

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

// Fetch projects on mount
onMounted(async () => {
  try {
    await fetchProjects()
  } catch (err) {
    error.value = 'Failed to load projects'
  }
})

const handleSubmit = async () => {
  loading.value = true
  error.value = null

  try {
    await createTask(form.value)
    router.push('/tasks')
  } catch (err) {
    error.value = err.message || 'Failed to create task'
  } finally {
    loading.value = false
  }
}
</script>
