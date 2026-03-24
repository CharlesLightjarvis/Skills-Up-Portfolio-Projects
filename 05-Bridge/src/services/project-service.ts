import { api } from '@/lib/api'

export type RoleName =
  | 'frontend_developer'
  | 'backend_developer'
  | 'fullstack_developer'
  | 'devops'
  | 'qa'
  | 'security_specialist'
  | 'project_manager'

export type RequiredRole = {
  name: RoleName
  count: number
}

export type Project = {
  id: string
  title: string
  description: string
  deadline: string
  status: 'recruiting' | 'in_progress' | 'completed' | 'abandoned'
  required_roles: RequiredRole[]
  links: {
    repository: string | null
    live: string | null
  }
  owner_roles: RoleName[]
  total_slots: number
  remaining_slots: number
  tech_stack: string[]
}

export type User = {
  id: string
  first_name: string
  last_name: string
  email: string
  bio: string | null
  social_links: {
    github: string | null
    portfolio: string | null
    linkedin: string | null
  }
  bridge_score: number
  commitment_score: number
  timezone: string
  stacks: string[]
  skills: string[]
  skill_level: 'beginner' | 'intermediate'
  status: 'active' | 'inactive'
  max_projects_allowed: number
  created_at: string
  updated_at: string
}

export type ProjectMember = {
  id: string
  user_id: string
  project_id: string
  roles: RoleName[]
  is_owner: boolean
  status: 'active' | 'inactive' | 'pending'
  joined_at: string
  user: User
  created_at: string
  updated_at: string
}

export type ProjectDetail = Project & {
  owner: User
  members: ProjectMember[]
  active_members_count: number
  can_accept_members: boolean
  applications_count: number
  pending_applications_count: number
  created_at: string
  updated_at: string
}

export type ProjectsResponse = {
  success: boolean
  message: string
  data: Project[]
}

class ProjectService {
  /**
   * Get all projects
   */
  async getProjects(): Promise<Project[]> {
    console.log('📋 Fetching projects...')

    try {
      const response = await api.get<ProjectsResponse>('/api/projects')
      console.log('✅ Projects fetched:', response.data.data.length)
      return response.data.data
    } catch (error: any) {
      console.error('❌ Failed to fetch projects:', error)
      throw error
    }
  }

  /**
   * Get a single project by ID with full details
   */
  async getProject(id: string): Promise<ProjectDetail> {
    console.log('📋 Fetching project:', id)

    try {
      const response = await api.get<{
        success: boolean
        data: ProjectDetail
      }>(`/api/projects/${id}`)
      console.log('✅ Project fetched:', response.data.data.title)
      return response.data.data
    } catch (error: any) {
      console.error('❌ Failed to fetch project:', error)
      throw error
    }
  }
}

export const projectService = new ProjectService()
