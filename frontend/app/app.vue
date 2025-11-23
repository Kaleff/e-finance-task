<template>
  <div>
    <NuxtPage />
    <Notification />
  </div>
</template>

<script setup>
const { initAuth } = useAuth()

// Initialize auth on app mount
onMounted(async () => {
  await initAuth()
})
</script>
