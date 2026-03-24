import { api } from '@/lib/api'
import type {
  LoginCredentials,
  LoginResponse,
  User,
  RegisterCredentials,
  RegisterResponse,
} from '@/types/user'

class AuthService {
  /**
   * Get CSRF cookie from Sanctum before making authenticated requests
   */
  async getCsrfCookie(): Promise<void> {
    console.log('🔐 Getting CSRF cookie...')
    const csrfToken = await api.get('/sanctum/csrf-cookie')
    console.log('csrfToken', csrfToken)
  }

  /**
   * Login user with email and password
   */
  async login(credentials: LoginCredentials): Promise<LoginResponse> {
    console.log('🔑 Attempting login for:', credentials.email)

    // Get CSRF cookie first
    await this.getCsrfCookie()

    const response = await api.post<LoginResponse>('/api/login', credentials)
    console.log('✅ Login successful:', {
      user: response.data.user.email,
    })

    return response.data
  }

  /**
   * Register a new user
   */
  async register(credentials: RegisterCredentials): Promise<RegisterResponse> {
    console.log('📝 Attempting registration for:', credentials.email)

    try {
      // Get CSRF cookie first
      await this.getCsrfCookie()

      const response = await api.post<RegisterResponse>(
        '/api/register',
        credentials,
      )
      console.log('✅ Registration successful:', {
        user: response.data.user?.email,
      })

      return {
        success: true,
        message: response.data.message || 'Inscription réussie!',
        user: response.data.user,
      }
    } catch (error: any) {
      console.error('❌ Registration failed:', error)
      return {
        success: false,
        message:
          error.response?.data?.message || "Erreur lors de l'inscription",
        errors: error.response?.data?.errors,
      }
    }
  }

  /**
   * Logout current user
   */
  async logout(): Promise<void> {
    console.log('🚪 Logging out...')
    await api.post('/api/logout')
    console.log('✅ Logout successful')
  }

  /**
   * Get current authenticated user
   */
  async getCurrentUser(): Promise<User> {
    const response = await api.get<{ user: User }>('/api/user')
    return response.data.user
  }

  /**
   * Verify user email with the verification link
   */
  async verifyEmail(
    id: string,
    hash: string,
    expires: string,
    signature: string,
  ): Promise<{ success: boolean; message: string }> {
    console.log('📧 Verifying email...')
    console.log(
      'Verification URL:',
      `/api/email/verify/${id}/${hash}?expires=${expires}&signature=${signature}`,
    )

    try {
      const response = await api.get(
        `/api/email/verify/${id}/${hash}?expires=${expires}&signature=${signature}`,
      )

      console.log('✅ Email verification response:', response.data)
      return {
        success: true,
        message: response.data.message || 'Email vérifié avec succès!',
      }
    } catch (error: any) {
      console.error('❌ Email verification failed:', error)
      console.error('Error details:', {
        status: error.response?.status,
        data: error.response?.data,
        url: error.config?.url,
      })

      if (error.response?.status === 404) {
        return {
          success: false,
          message:
            'Route de vérification non trouvée. Vérifiez la configuration du backend.',
        }
      }

      return {
        success: false,
        message:
          error.response?.data?.message ||
          'La vérification a échoué. Le lien est peut-être expiré.',
      }
    }
  }

  /**
   * Generate SSO token for current user (for marketing site)
   */
  async generateSsoToken(): Promise<{ token: string; sso_url: string }> {
    console.log('🔑 Generating SSO token...')
    const response = await api.post<{
      success: boolean
      token: string
      sso_url: string
    }>('/api/sso/generate-token')
    console.log('✅ SSO token generated')
    return {
      token: response.data.token,
      sso_url: response.data.sso_url,
    }
  }

  /**
   * Resend email verification link
   */
  async resendVerificationEmail(): Promise<{
    success: boolean
    message: string
  }> {
    console.log('📧 Resending verification email...')

    try {
      const response = await api.post('/api/email/verification-notification')

      console.log('✅ Verification email sent:', response.data)
      return {
        success: true,
        message: response.data.message || 'Email de vérification envoyé!',
      }
    } catch (error: any) {
      console.error('❌ Failed to send verification email:', error)
      return {
        success: false,
        message:
          error.response?.data?.message ||
          "Échec de l'envoi de l'email de vérification.",
      }
    }
  }
}

export const authService = new AuthService()
