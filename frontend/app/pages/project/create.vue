<template>
  <div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a]">
    <!-- Header -->
    <header class="bg-white dark:bg-[#0a0a0a] shadow-sm border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Create Project</h1>
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
          <!-- Project Name -->
          <div class="mb-6">
            <CommonBaseInput
              v-model="formData.name"
              label="Project Name"
              placeholder="Enter project name"
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
              v-model="formData.description"
              rows="4"
              class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-[#f53003] dark:focus:ring-[#FF4433]"
              placeholder="Enter project description"
            ></textarea>
          </div>

          <!-- Status -->
          <div class="mb-6">
            <CommonBaseSelect
              v-model="formData.status"
              label="Status"
            >
              <option value="planned">Planned</option>
              <option value="in_progress">In Progress</option>
              <option value="completed">Completed</option>
              <option value="archived">Archived</option>
            </CommonBaseSelect>
          </div>

          <!-- Deadline -->
          <div class="mb-6">
            <CommonBaseInput
              v-model="formData.deadline"
              type="date"
              label="Deadline"
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
              {{ loading ? 'Creating...' : 'Create Project' }}
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

const { createProject } = useProjects()
const router = useRouter()

const formData = reactive({
  name: '',
  description: '',
  status: 'planned',
  deadline: ''
})

const loading = ref(false)
const error = ref(null)

const handleSubmit = async () => {
  loading.value = true
  error.value = null

  try {
    await createProject(formData)
    router.push('/project/list')
  } catch (err) {
    error.value = err.message || 'Failed to create project'
  } finally {
    loading.value = false
  }
}
</script>
