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
            <input type="hidden" name="project_id" :value="props.projectId" />
          </div>

          <!-- Task Title -->
          <div class="mb-6">
            <CommonBaseInput
              :modelValue="props.form.title"
              @update:modelValue="updateField('title', $event)"
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
              :value="props.form.description"
              @input="updateField('description', $event.target.value)"
              rows="4"
              class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-[#f53003] dark:focus:ring-[#FF4433]"
              placeholder="Enter task description"
            ></textarea>
          </div>

          <!-- Status and Priority -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <CommonBaseSelect
                :modelValue="props.form.status"
                @update:modelValue="updateField('status', $event)"
                label="Status"
              >
                <option value="todo">To Do</option>
                <option value="in_progress">In Progress</option>
                <option value="done">Done</option>
              </CommonBaseSelect>
            </div>

            <div>
              <CommonBaseSelect
                :modelValue="props.form.priority"
                @update:modelValue="updateField('priority', $event)"
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
              :modelValue="props.form.estimated_hours"
              @update:modelValue="updateField('estimated_hours', $event)"
              type="number"
              label="Estimated Hours"
              placeholder="0"
            />
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
  }
})

const emit = defineEmits(['update:form', 'submit'])

// Create computed property for two-way binding
const localForm = computed({
  get: () => props.form,
  set: (value) => emit('update:form', value)
})

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
</script>