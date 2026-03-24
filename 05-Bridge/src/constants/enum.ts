export type ProjectStatusEnum =
  | 'recruiting'
  | 'in_progress'
  | 'completed'
  | 'abandoned'

export type UserStatusEnum = 'active' | 'inactive'

export type SkillLevelEnum = 'beginner' | 'intermediate' | 'advanced'

export type ApplicationStatusEnum =
  | 'pending'
  | 'accepted'
  | 'rejected'
  | 'cancelled'

export type RolesMap = Record<string, number>


export const TECH_STACK_OPTIONS = [
  { value: 'laravel', label: 'Laravel' },
  { value: 'vue', label: 'Vue.js' },
  { value: 'react', label: 'React' },
  { value: 'node', label: 'Node.js' },
  { value: 'python', label: 'Python' },
  { value: 'django', label: 'Django' },
  { value: 'flask', label: 'Flask' },
] as const

export const ROLE_OPTIONS = [
  { value: 'frontend_developer', label: 'Frontend Developer' },
  { value: 'backend_developer', label: 'Backend Developer' },
  { value: 'fullstack_developer', label: 'Fullstack Developer' },
  { value: 'project_manager', label: 'Project Manager' },
  { value: 'qa', label: 'QA' },
  { value: 'devops', label: 'DevOps' },
  { value: 'security_specialist', label: 'Security Specialist' },
] as const
