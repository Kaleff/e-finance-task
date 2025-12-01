<template>
  <div
    class="bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm p-4 min-h-[300px]"
    @drop="event => onDrop(event, status)"
    @dragover.prevent
  >
    <h2 class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-4">{{ title }}</h2>
    <div v-if="tasks.length === 0" class="text-sm text-[#1b1b18]/60 dark:text-[#EDEDEC]/60">No tasks</div>
    <div v-else>
      <div
        v-for="task in tasks"
        :key="task.id"
        class="bg-[#FDFDFC] dark:bg-[#1b1b18]/10 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm p-3 mb-3 cursor-move"
        draggable="true"
        @dragstart="onDragStart($event, task)"
      >
        <NuxtLink
          :to="`/task/${task.id}`"
          class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:underline"
        >
          {{ task.title }}
        </NuxtLink>
        <div class="text-xs text-[#1b1b18]/60 dark:text-[#EDEDEC]/60">{{ task.description }}</div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  tasks: Array,
  status: String
})
const emit = defineEmits(['drop-task'])

function onDrop(event, status) {
  const task = JSON.parse(event.dataTransfer.getData('task'))

  if (!task) return
  emit('drop-task', { task, newStatus: status })
}
function onDragStart(event, task) {
  event.dataTransfer.setData('task', JSON.stringify(task))
}
</script>
