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
        <FormTask
          v-model:form="form"
          :loading="loading"
          :error="error"
          :project-id="projectId"
          :project-name="projectName"
          :users="users"
          :loading-users="loadingUsers"
          @submit="handleSubmit"
          @load-users="loadUsers"
        />
      </div>
    </main>
  </div>
</template>

<script setup>
definePageMeta({
  middleware: 'auth'
})

const { createTask } = useTasks()
const { fetchUsers } = useUsers()
const router = useRouter()
const route = useRoute()
const uiStore = useUiStore()

const form = ref({
  project_id: '',
  title: '',
  description: '',
  status: 'todo',
  priority: 'medium',
  estimated_hours: null,
  assigned_to: null
})

const loading = ref(false)
const error = ref(null)
const users = ref([])
const loadingUsers = ref(false)
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

const loadUsers = async () => {
  if (users.value.length > 0) return // Already loaded
  
  loadingUsers.value = true
  try {
    const response = await fetchUsers()
    users.value = response || []
  } catch (err) {
    uiStore.addNotification({
      type: 'error',
      message: 'Failed to load users'
    })
  } finally {
    loadingUsers.value = false
  }
}

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
