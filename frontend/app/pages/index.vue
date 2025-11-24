<template>
  <div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a]">
    <!-- Header -->
    <header class="bg-white dark:bg-[#0a0a0a] shadow-sm border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Dashboard</h1>
        <div class="flex items-center space-x-4">
          <span class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ userName }}</span>
          <CommonBaseButton
            @click="handleLogout"
            size="sm"
          >
            Logout
          </CommonBaseButton>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="bg-white dark:bg-[#0a0a0a] rounded-sm shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A] p-6">
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
                <p class="text-2xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mt-2">0</p>
              </div>
              <svg class="w-12 h-12 text-[#1b1b18]/20 dark:text-[#EDEDEC]/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
              </svg>
            </div>
          </NuxtLink>

          <!-- Tasks Card -->
          <NuxtLink
            to="/tasks"
            class="bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm p-6 hover:border-[#f53003] dark:hover:border-[#FF4433] transition-colors cursor-pointer"
          >
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-[#f53003] dark:text-[#FF4433]">Tasks</p>
                <p class="text-2xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mt-2">0</p>
              </div>
              <svg class="w-12 h-12 text-[#1b1b18]/20 dark:text-[#EDEDEC]/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
              </svg>
            </div>
          </NuxtLink>

          <!-- Statistics Card -->
          <div class="bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-[#f53003] dark:text-[#FF4433]">Completed</p>
                <p class="text-2xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mt-2">0%</p>
              </div>
              <svg class="w-12 h-12 text-[#1b1b18]/20 dark:text-[#EDEDEC]/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
              </svg>
            </div>
          </div>
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
            <NuxtLink to="/task/create">
              <CommonBaseButton variant="secondary">
                Create Task
              </CommonBaseButton>
            </NuxtLink>
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

const userName = computed(() => currentUser.value?.name || 'User')

const handleLogout = async () => {
  await logout()
  router.push('/login')
}
</script>
