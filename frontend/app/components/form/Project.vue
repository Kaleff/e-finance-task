<template>
  <form @submit.prevent="handleSubmit">
    <!-- Project Name -->
    <div class="mb-6">
      <CommonBaseInput
        :modelValue="props.form.name"
        @update:modelValue="updateField('name', $event)"
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
        :value="props.form.description"
        @input="updateField('description', $event.target.value)"
        rows="4"
        class="w-full px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 focus:ring-[#f53003] dark:focus:ring-[#FF4433]"
        placeholder="Enter project description"
      ></textarea>
    </div>

    <!-- Status -->
    <div class="mb-6">
      <CommonBaseSelect
        :modelValue="props.form.status"
        @update:modelValue="updateField('status', $event)"
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
        :modelValue="props.form.deadline"
        @update:modelValue="updateField('deadline', $event)"
        type="date"
        label="Deadline"
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
        {{ props.loading ? 'Creating...' : 'Create Project' }}
      </CommonBaseButton>
      <NuxtLink to="/" class="flex-1">
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
  }
})

const emit = defineEmits(['update:form', 'submit'])

// Helper to update individual fields
const updateField = (field, value) => {
  emit('update:form', { ...props.form, [field]: value })
}

const handleSubmit = () => {
  emit('submit')
}
</script>
