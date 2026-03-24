import type { SkillLevelEnum, UserStatusEnum } from "../constants/enum"

export interface User {
  id: string
  first_name: string
  last_name: string
  email: string
  bio: string | null
  bridge_score: number
  commitment_score: number
  timezone: string
  stacks: string[]
  skills: string[]
  skill_level: SkillLevelEnum
  status: UserStatusEnum
  social_links: {
    github: string | null
    portfolio: string | null
    linkedin: string | null
  }
  max_projects_allowed: number
  created_at: string
  updated_at: string
}


export interface LoginCredentials {
  email: string
  password: string
}

export interface LoginResponse {
  two_factor: boolean
  user: User
}

export interface RegisterCredentials {
  first_name: string
  last_name: string
  email: string
  password: string
  password_confirmation: string
  social_links: {
    github: string
    linkedin?: string
    twitter?: string
    portfolio?: string
  }
}

export interface RegisterResponse {
  success: boolean
  message: string
  user?: User
  errors?: Record<string, string[]>
}
