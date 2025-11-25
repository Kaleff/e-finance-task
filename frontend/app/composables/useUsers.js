export const useUsers = () => {
  const api = useApi()

  const fetchUsers = async () => {
    try {
      const response = await api.get('/users')
      return response
    } catch (error) {
      console.error('Error fetching users:', error)
      throw error
    }
  }

  return {
    fetchUsers
  }
}
