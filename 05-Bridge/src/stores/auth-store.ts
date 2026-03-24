import { create } from 'zustand'
import { devtools } from 'zustand/middleware'
import type { LoginCredentials, User, RegisterCredentials } from '@/types/user'
import { authService } from '@/services/auth-service'

interface AuthState {
  user: User | null
  isAuthenticated: boolean
  loading: boolean
  error: string | null
}

interface AuthActions {
  login: (credentials: LoginCredentials) => Promise<{
    success: boolean
    message?: string
    errors?: Record<string, string[]>
  }>
  register: (credentials: RegisterCredentials) => Promise<{
    success: boolean
    message?: string
    errors?: Record<string, string[]>
  }>
  logout: () => Promise<void>
  clearError: () => void
  setUser: (user: User | null) => void
  fetchUser: () => Promise<void>
}

export type AuthStore = AuthState & AuthActions

export const useAuthStore = create<AuthStore>()(
  devtools(
    (set) => ({
      // Initial state
      user: null,
      isAuthenticated: false,
      loading: false,
      error: null,

      // Fetch current user from Laravel
      fetchUser: async () => {
        set({ loading: true })
        try {
          const user = await authService.getCurrentUser()
          set({
            user,
            isAuthenticated: true,
            loading: false,
            error: null,
          })
          console.log('✅ User fetched:', {
            email: user.email,
          })
        } catch (error: any) {
          console.log('ℹ️ No authenticated user')
          set({
            user: null,
            isAuthenticated: false,
            loading: false,
            error: null,
          })
        }
      },

      // Login action
      login: async (credentials: LoginCredentials) => {
        set({ loading: true, error: null })
        try {
          const response = await authService.login(credentials)

          const user = response.user
          console.log('✅ User authenticated:', user)

          set({
            user,
            isAuthenticated: true,
            loading: false,
            error: null,
          })
          return { success: true }
        } catch (error: any) {
          console.error('❌ Login failed:', error.message)
          set({
            user: null,
            isAuthenticated: false,
            loading: false,
            error: error.message,
          })

          return {
            success: false,
            message: error.message,
            errors: error.errors,
          }
        }
      },

      // Register action
      register: async (credentials: RegisterCredentials) => {
        set({ loading: true, error: null })
        try {
          const response = await authService.register(credentials)

          if (response.success && response.user) {
            console.log('✅ User registered:', response.user)
            // Ne pas connecter automatiquement, l'utilisateur doit vérifier son email
            set({
              loading: false,
              error: null,
            })
          }

          return {
            success: response.success,
            message: response.message,
            errors: response.errors,
          }
        } catch (error: any) {
          console.error('❌ Registration failed:', error.message)
          set({
            loading: false,
            error: error.message,
          })

          return {
            success: false,
            message: error.message,
            errors: error.errors,
          }
        }
      },

      // Logout action
      logout: async () => {
        set({ loading: true })
        try {
          await authService.logout()
          set({
            user: null,
            isAuthenticated: false,
            loading: false,
            error: null,
          })
          console.log('✅ User logged out')
        } catch (error: any) {
          console.error('❌ Logout failed:', error.message)
          // Force logout on client side even if API fails
          set({
            user: null,
            isAuthenticated: false,
            loading: false,
            error: null,
          })
        }
      },

      // Clear error
      clearError: () => {
        set({ error: null })
      },

      // Set user manually (for SSR or token refresh)
      setUser: (user: User | null) => {
        set({
          user,
          isAuthenticated: !!user,
        })
      },
    }),
    { name: 'auth-store' },
  ),
)
