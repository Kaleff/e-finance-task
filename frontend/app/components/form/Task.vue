<template>
<form @submit.prevent="handleSubmit">
          <!-- Project Info (Read-only) -->
          <div class="mb-6">
            <label class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
              Project
            </label>
            <div class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-[#FDFDFC] dark:bg-[#1b1b18]/20 text-[#1b1b18] dark:text-[#EDEDEC]">
              {{ props.projectName }}
            </div>
            <input type="hidden" name="project_id" :value="props.projectId" >
          </div>

          <!-- Task Title -->
          <div class="mb-6">
            <CommonBaseInput
              :model-value="props.form.title"
              label="Task Title"
              placeholder="Enter task title"
              required
              @update:model-value="updateField('title', $event)"
            />
          </div>

          <!-- Description -->
          <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
              Description
            </label>
            <textarea
              id="description"
              :value="props.form.description"
              rows="4"
              class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-[#f53003] dark:focus:ring-[#FF4433]"
              placeholder="Enter task description"
              @input="updateField('description', $event.target.value)"
            />
          </div>

          <!-- Status and Priority -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <CommonBaseSelect
                :model-value="props.form.status"
                label="Status"
                @update:model-value="updateField('status', $event)"
              >
                <option value="todo">To Do</option>
                <option value="in_progress">In Progress</option>
                <option value="done">Done</option>
              </CommonBaseSelect>
            </div>

            <div>
              <CommonBaseSelect
                :model-value="props.form.priority"
                label="Priority"
                @update:model-value="updateField('priority', $event)"
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
              :model-value="props.form.estimated_hours"
              type="number"
              label="Estimated Hours"
              placeholder="0"
              @update:model-value="updateField('estimated_hours', $event)"
            />
          </div>

          <!-- Assignee -->
          <div class="mb-6">
            <label class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
              Assignee
            </label>
            <CommonBaseSelect
              :model-value="props.form.assigned_to"
              :disabled="props.loadingUsers"
              @update:model-value="updateField('assigned_to', $event)"
              @focus="handleLoadUsers"
            >
              <option :value="null">Unassigned</option>
              <option v-for="user in props.users" :key="user.id" :value="user.id">
                {{ user.name }} ({{ user.email }})
              </option>
            </CommonBaseSelect>
          </div>

          <!-- Error Message -->
          <div v-if="props.error" class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-sm">
            <p class="text-sm text-red-600 dark:text-red-400">{{ props.error }}</p>
          </div>

          <!-- Submit Button -->
          <div class="flex gap-3">
            <CommonBaseButton
              type="submit"
              :disabled="props.loading"
              class="flex-1"
            >
              {{ props.loading ? (props.isEdit ? 'Updating...' : 'Creating...') : (props.isEdit ? 'Update Task' : 'Create Task') }}
            </CommonBaseButton>
            <NuxtLink :to="props.projectId ? `/project/${props.projectId}` : '/project/list'" class="flex-1">
              <CommonBaseButton variant="secondary" class="w-full">
                Cancel
              </CommonBaseButton>
            </NuxtLink>
          </div>
        </form>
        </template>

<script setup>
const props = defineProps({
  form: {
    type: Object,
    required: true
  },
  loading: {
    type: Boolean,
    default: false
  },
  error: {
    type: String,
    default: null
  },
  projectId: {
    type: Number,
    default: null
  },
  projectName: {
    type: String,
    default: ''
  },
  isEdit: {
    type: Boolean,
    default: false
  },
  taskId: {
    type: [String, Number],
    default: null
  },
  users: {
    type: Array,
    default: () => []
  },
  loadingUsers: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:form', 'submit', 'load-users'])

// Helper to update individual fields
const updateField = (field, value) => {
  emit('update:form', { ...props.form, [field]: value })
}

const handleSubmit = () => {
  // Add task ID to form data for edit mode
  const formData = props.isEdit && props.taskId
    ? { ...props.form, id: props.taskId }
    : props.form

  emit('submit', formData)
}

const handleLoadUsers = () => {
  emit('load-users')
}
</script>