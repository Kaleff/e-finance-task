<template>
  <div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a]">
    <!-- Header -->
    <header class="bg-white dark:bg-[#0a0a0a] shadow-sm border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex justify-between items-center">
          <div>
            <div class="flex items-center gap-3">
              <h1 class="text-2xl font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                {{ task?.title || 'Loading...' }}
              </h1>
              <span v-if="task" :class="getStatusColor(task.status)">
                {{ getStatusLabel(task.status) }}
              </span>
              <span v-if="task" :class="getPriorityColor(task.priority)">
                {{ getPriorityLabel(task.priority) }}
              </span>
            </div>
            <p v-if="task?.description" class="mt-1 text-sm text-[#1b1b18]/70 dark:text-[#EDEDEC]/70">
              {{ task.description }}
            </p>
          </div>
          <div class="flex gap-3">
            <NuxtLink :to="task?.project_id ? `/project/${task.project_id}` : '/project/list'">
              <CommonBaseButton variant="secondary" size="sm">
                Back to Project
              </CommonBaseButton>
            </NuxtLink>
          </div>
        </div>

        <!-- Task Stats -->
        <div v-if="task" class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm p-4">
            <div class="text-sm text-[#1b1b18]/60 dark:text-[#EDEDEC]/60">Project ID</div>
            <div class="text-xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mt-1">
              {{ task.project_id }}
            </div>
          </div>

          <div class="bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm p-4">
            <div class="text-sm text-[#1b1b18]/60 dark:text-[#EDEDEC]/60">Estimated Hours</div>
            <div class="text-xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mt-1">
              {{ task.estimated_hours || 'N/A' }}
            </div>
          </div>

          <div class="bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm p-4">
            <div class="text-sm text-[#1b1b18]/60 dark:text-[#EDEDEC]/60">Comments</div>
            <div class="text-xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mt-1">
              {{ commentsPagination.total || 0 }}
            </div>
          </div>

          <div class="bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm p-4">
            <div class="text-sm text-[#1b1b18]/60 dark:text-[#EDEDEC]/60">Created</div>
            <div class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mt-1">
              {{ task.created_at ? formatDate(task.created_at) : 'N/A' }}
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

      <!-- Task Details -->
      <div v-else-if="task" class="space-y-8">
        <!-- Comments Section -->
        <div class="bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm p-6">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Comments</h2>
            <CommonBaseButton size="sm" @click="showAddComment = !showAddComment">
              {{ showAddComment ? 'Cancel' : 'Add Comment' }}
            </CommonBaseButton>
          </div>

          <!-- Add Comment Form -->
          <div v-if="showAddComment" class="mb-6 p-4 bg-[#FDFDFC] dark:bg-[#1b1b18]/20 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm">
            <textarea
              v-model="newComment"
              rows="3"
              class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-[#f53003] dark:focus:ring-[#FF4433]"
              placeholder="Write your comment..."
            ></textarea>
            <div class="mt-3 flex gap-2">
              <CommonBaseButton size="sm" @click="handleAddComment" :disabled="!newComment.trim() || addingComment">
                {{ addingComment ? 'Adding...' : 'Submit Comment' }}
              </CommonBaseButton>
              <CommonBaseButton variant="secondary" size="sm" @click="showAddComment = false">
                Cancel
              </CommonBaseButton>
            </div>
          </div>

          <!-- Empty State -->
          <div v-if="!comments.length" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-[#1b1b18]/40 dark:text-[#EDEDEC]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">No comments yet</h3>
            <p class="mt-1 text-sm text-[#1b1b18]/70 dark:text-[#EDEDEC]/70">Be the first to comment on this task.</p>
          </div>

          <!-- Comments List -->
          <div v-else class="space-y-4">
            <div
              v-for="comment in comments"
              :key="comment.id"
              class="p-4 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm"
            >
              <div class="flex justify-between items-start mb-2">
                <div>
                  <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                    User #{{ comment.user_id }}
                  </p>
                  <p class="text-xs text-[#1b1b18]/60 dark:text-[#EDEDEC]/60">
                    {{ formatDate(comment.created_at) }}
                  </p>
                </div>
              </div>
              <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ comment.comment }}</p>
            </div>
          </div>

          <!-- Comments Pagination -->
          <div v-if="commentsPagination.last_page > 1" class="mt-6 flex flex-col items-center gap-4">
            <div class="flex gap-2 items-center">
              <CommonBaseButton
                variant="secondary"
                size="sm"
                :disabled="commentsPagination.current_page === 1"
                @click="loadCommentsPage(commentsPagination.current_page - 1)"
              >
                Previous
              </CommonBaseButton>

              <div class="flex gap-1">
                <button
                  v-for="(page, index) in visibleCommentPages"
                  :key="`page-${index}`"
                  @click="page !== '...' ? loadCommentsPage(page) : null"
                  :disabled="page === '...'"
                  :class="[
                    'px-3 py-1 text-sm rounded-sm transition-colors',
                    page === '...'
                      ? 'bg-transparent text-[#1b1b18]/40 dark:text-[#EDEDEC]/40 cursor-default'
                      : page === commentsPagination.current_page
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
                :disabled="commentsPagination.current_page === commentsPagination.last_page"
                @click="loadCommentsPage(commentsPagination.current_page + 1)"
              >
                Next
              </CommonBaseButton>
            </div>

            <div class="text-sm text-[#1b1b18]/70 dark:text-[#EDEDEC]/70">
              Showing {{ ((commentsPagination.current_page - 1) * commentsPagination.per_page) + 1 }} to {{ Math.min(commentsPagination.current_page * commentsPagination.per_page, commentsPagination.total) }} of {{ commentsPagination.total }} comments
            </div>
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
const uiStore = useUiStore()

const task = ref(null)
const comments = ref([])
const commentsPagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 10,
  total: 0
})
const loading = ref(false)
const error = ref(null)
const showAddComment = ref(false)
const newComment = ref('')
const addingComment = ref(false)

// Load task data
onMounted(async () => {
  await loadTask()
})

const loadTask = async (commentsPage = 1) => {
  loading.value = true
  error.value = null

  try {
    const params = {
      page: commentsPage,
      per_page: 10
    }

    const response = await api.get(`/tasks/${route.params.id}`, params)

    task.value = {
      id: response.id,
      title: response.title,
      description: response.description,
      project_id: response.project_id,
      status: response.status,
      assigned_to: response.assigned_to,
      priority: response.priority,
      estimated_hours: response.estimated_hours,
      created_at: response.created_at,
      updated_at: response.updated_at
    }

    comments.value = response.comments || []
    commentsPagination.value = response.comments_pagination || commentsPagination.value
  } catch (err) {
    error.value = err.message || 'Failed to load task'
  } finally {
    loading.value = false
  }
}

const loadCommentsPage = async (page) => {
  await loadTask(page)
}

const handleAddComment = async () => {
  if (!newComment.value.trim()) return

  addingComment.value = true

  try {
    await api.post(`/tasks/${route.params.id}/comments`, {
      comment: newComment.value
    })

    uiStore.addNotification({
      type: 'success',
      message: 'Comment added successfully!'
    })

    newComment.value = ''
    showAddComment.value = false

    // Reload task to get updated comments
    await loadTask(1)
  } catch (err) {
    uiStore.addNotification({
      type: 'error',
      message: err.message || 'Failed to add comment'
    })
  } finally {
    addingComment.value = false
  }
}

// Computed properties
const visibleCommentPages = computed(() => {
  if (!commentsPagination.value.last_page) return []

  const current = commentsPagination.value.current_page
  const last = commentsPagination.value.last_page
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
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const getStatusColor = (status) => {
  const colors = {
    todo: 'px-2 py-1 text-xs rounded-sm bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300',
    in_progress: 'px-2 py-1 text-xs rounded-sm bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
    done: 'px-2 py-1 text-xs rounded-sm bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400'
  }
  return colors[status] || colors.todo
}

const getStatusLabel = (status) => {
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

const getPriorityLabel = (priority) => {
  if (!priority) return 'Medium'
  return priority.charAt(0).toUpperCase() + priority.slice(1)
}
</script>
