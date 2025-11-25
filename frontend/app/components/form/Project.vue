<template>
  <form @submit.prevent="handleSubmit">
    <!-- Hidden Project ID for Edit Mode -->
    <input v-if="props.isEdit" type="hidden" :value="props.projectId" >

    <!-- Project Name -->
    <div class="mb-6">
      <CommonBaseInput
        :model-value="props.form.name"
        label="Project Name"
        placeholder="Enter project name"
        required
        @update:model-value="updateField('name', $event)"
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
        placeholder="Enter project description"
        @input="updateField('description', $event.target.value)"
      />
    </div>

    <!-- Status -->
    <div class="mb-6">
      <CommonBaseSelect
        :model-value="props.form.status"
        label="Status"
        @update:model-value="updateField('status', $event)"
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
        :model-value="props.form.deadline"
        type="date"
        label="Deadline"
        @update:model-value="updateField('deadline', $event)"
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
        {{ props.loading ? (props.isEdit ? 'Updating...' : 'Creating...') : (props.isEdit ? 'Update Project' : 'Create Project') }}
      </CommonBaseButton>
      <NuxtLink :to="props.isEdit && props.projectId ? `/project/${props.projectId}` : '/'" class="flex-1">
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
  isEdit: {
    type: Boolean,
    default: false
  },
  projectId: {
    type: [String, Number],
    default: null
  }
})

const emit = defineEmits(['update:form', 'submit'])

// Helper to update individual fields
const updateField = (field, value) => {
  emit('update:form', { ...props.form, [field]: value })
}

const handleSubmit = () => {
  // Add project ID to form data for edit mode
  const formData = props.isEdit && props.projectId 
    ? { ...props.form, id: props.projectId }
    : props.form
  
  emit('submit', formData)
}
</script>
