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
        <FormProject
          v-model:form="formData"
          :loading="loading"
          :error="error"
          @submit="handleSubmit"
        />
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

const formData = ref({
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
    await createProject(formData.value)
    router.push('/project/list')
  } catch (err) {
    error.value = err.message || 'Failed to create project'
  } finally {
    loading.value = false
  }
}
</script>
