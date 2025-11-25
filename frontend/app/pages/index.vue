<template>
  <div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a]">
    <!-- Header -->
    <header class="bg-white dark:bg-[#0a0a0a] shadow-sm border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Dashboard</h1>
        <div class="flex items-center space-x-4">
          <span class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ userName }}</span>
          <CommonBaseButton
            size="sm"
            @click="handleLogout"
          >
            Logout
          </CommonBaseButton>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#f53003] dark:border-[#FF4433]"/>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-sm p-6">
        <p class="text-red-600 dark:text-red-400">{{ error }}</p>
      </div>

      <div v-else class="bg-white dark:bg-[#0a0a0a] rounded-sm shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
        <h2 class="text-xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Welcome to Your Dashboard!</h2>
        <p class="text-[#1b1b18]/70 dark:text-[#EDEDEC]/70 mb-4">
          You have successfully logged in to your account.
        </p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
          <!-- Projects Card -->
          <NuxtLink
            to="/project/list"
            class="bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm p-6 hover:border-[#f53003] dark:hover:border-[#FF4433] transition-colors cursor-pointer"
          >
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-[#f53003] dark:text-[#FF4433]">Projects</p>
                <p class="text-2xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mt-2">{{ stats.total_projects || 0 }}</p>
              </div>
              <svg class="w-12 h-12 text-[#1b1b18]/20 dark:text-[#EDEDEC]/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
              </svg>
            </div>
          </NuxtLink>

          <DashboardCard>
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-[#f53003] dark:text-[#FF4433]">Tasks</p>
                <p class="text-2xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mt-2">{{ stats.total_tasks || 0 }}</p>
                <p class="text-xs text-[#1b1b18]/60 dark:text-[#EDEDEC]/60 mt-1">{{ stats.completed_tasks || 0 }} completed</p>
              </div>
              <svg class="w-12 h-12 text-[#1b1b18]/20 dark:text-[#EDEDEC]/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
              </svg>
            </div>
          </DashboardCard>

          <!-- Statistics Card -->
          <DashboardCard>
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-[#f53003] dark:text-[#FF4433]">Completion Rate</p>
                <p class="text-2xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mt-2">{{ stats.completion_percentage || 0 }}%</p>
              </div>
              <svg class="w-12 h-12 text-[#1b1b18]/20 dark:text-[#EDEDEC]/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
              </svg>
            </div>
          </DashboardCard>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8">
          <h3 class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Quick Actions</h3>
          <div class="flex flex-wrap gap-3">
            <NuxtLink to="/project/create">
              <CommonBaseButton variant="secondary">
                Create Project
              </CommonBaseButton>
            </NuxtLink>
            <NuxtLink to="/project/list">
              <CommonBaseButton variant="secondary">
                View Projects
              </CommonBaseButton>
            </NuxtLink>
          </div>
        </div>

        <!-- Project Overview Section -->
        <div class="mt-8">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Project Overview</h3>
            <CommonBaseButton
              size="sm"
              variant="secondary"
              :disabled="loadingOverview"
              @click="fetchProjectOverview"
            >
              {{ projectOverview.length > 0 ? 'Refresh' : 'Load Overview' }}
            </CommonBaseButton>
          </div>

          <!-- Loading State -->
          <div v-if="loadingOverview" class="flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#f53003] dark:border-[#FF4433]"/>
          </div>

          <!-- Error State -->
          <div v-else-if="overviewError" class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-sm p-4">
            <p class="text-red-600 dark:text-red-400 text-sm">{{ overviewError }}</p>
          </div>

          <!-- Overview Table -->
          <div v-else-if="projectOverview.length > 0" class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm overflow-hidden">
            <table class="min-w-full divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
              <thead class="bg-[#FDFDFC] dark:bg-[#0a0a0a]">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-[#1b1b18]/60 dark:text-[#EDEDEC]/60 uppercase tracking-wider">
                    Project
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-[#1b1b18]/60 dark:text-[#EDEDEC]/60 uppercase tracking-wider">
                    Owner
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-[#1b1b18]/60 dark:text-[#EDEDEC]/60 uppercase tracking-wider">
                    Tasks
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-[#1b1b18]/60 dark:text-[#EDEDEC]/60 uppercase tracking-wider">
                    Completion
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-[#1b1b18]/60 dark:text-[#EDEDEC]/60 uppercase tracking-wider">
                    Team
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-[#1b1b18]/60 dark:text-[#EDEDEC]/60 uppercase tracking-wider">
                    Comments
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-[#0a0a0a] divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                <tr
                  v-for="(project, index) in projectOverview"
                  :key="index"
                  class="hover:bg-[#FDFDFC] dark:hover:bg-[#1b1b18]/20 transition-colors"
                >
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                      {{ project.project_name }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-[#1b1b18]/70 dark:text-[#EDEDEC]/70">
                      {{ project.owner_name }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                      {{ project.completed_tasks }} / {{ project.total_tasks }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-2">
                      <div class="w-full max-w-[100px] bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div
                          class="h-2 rounded-full transition-all"
                          :class="getCompletionColor(project.completion_rate)"
                          :style="{ width: `${project.completion_rate}%` }"
                        />
                      </div>
                      <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                        {{ project.completion_rate }}%
                      </span>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-[#1b1b18]/70 dark:text-[#EDEDEC]/70">
                      {{ project.team_members }} members
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-1 text-sm text-[#1b1b18]/60 dark:text-[#EDEDEC]/60">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                      </svg>
                      <span>{{ project.comment_count }}</span>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Empty State -->
          <div v-else class="text-center py-8 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm">
            <svg class="mx-auto h-10 w-10 text-[#1b1b18]/40 dark:text-[#EDEDEC]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="mt-2 text-sm text-[#1b1b18]/70 dark:text-[#EDEDEC]/70">
              Click "Load Overview" to see project analytics
            </p>
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

const { logout, currentUser } = useAuth()
const router = useRouter()
const { fetchStats: getStats, fetchProjectOverview: getProjectOverview } = useStats()

const stats = ref({
  total_projects: 0,
  total_tasks: 0,
  completed_tasks: 0,
  completion_percentage: 0
})
const projectOverview = ref([])
const loading = ref(false)
const error = ref(null)
const loadingOverview = ref(false)
const overviewError = ref(null)

const userName = computed(() => currentUser.value?.name || 'User')

// Fetch stats on mount
onMounted(async () => {
  await fetchStats()
})

const fetchStats = async () => {
  loading.value = true
  error.value = null
  
  try {
    const response = await getStats()
    stats.value = response
  } catch (err) {
    error.value = err.message || 'Failed to load statistics'
  } finally {
    loading.value = false
  }
}

const fetchProjectOverview = async () => {
  loadingOverview.value = true
  overviewError.value = null
  
  try {
    const response = await getProjectOverview()
    projectOverview.value = response
  } catch (err) {
    overviewError.value = err.message || 'Failed to load project overview'
  } finally {
    loadingOverview.value = false
  }
}

const getCompletionColor = (rate) => {
  if (rate >= 75) return 'bg-green-500 dark:bg-green-600'
  if (rate >= 50) return 'bg-blue-500 dark:bg-blue-600'
  if (rate >= 25) return 'bg-yellow-500 dark:bg-yellow-600'
  return 'bg-red-500 dark:bg-red-600'
}

const handleLogout = async () => {
  await logout()
  router.push('/login')
}
</script>
