import { ref } from 'vue'

interface User {
  id: number
  username: string
  email: string
  first_name: string
  last_name: string
  user_type: string
}

const user = ref<User | null>(null)

export function useAuth() {
  const login = async (userData: User) => {
    user.value = userData
    localStorage.setItem('user', JSON.stringify(userData))
  }

  const logout = async () => {
    user.value = null
    localStorage.removeItem('user')
  }

  const initAuth = () => {
    const savedUser = localStorage.getItem('user')
    if (savedUser) {
      user.value = JSON.parse(savedUser)
    }
  }

  return {
    user,
    login,
    logout,
    initAuth
  }
} 