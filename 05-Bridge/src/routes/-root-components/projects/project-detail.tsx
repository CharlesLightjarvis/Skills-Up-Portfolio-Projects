import * as React from 'react'
import { useParams, Link } from '@tanstack/react-router'
import {
  ChevronRight,
  BadgeCheck,
  Clock,
  Globe,
  Calendar,
  CheckCircle2,
  Handshake,
  UserSearch,
  Timer,
  Users,
  ExternalLink,
  Code,
} from 'lucide-react'
import { FaGithub, FaLinkedin } from 'react-icons/fa'

import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import {
  projectService,
  type ProjectDetail as ProjectDetailType,
  type RoleName,
} from '@/services/project-service'

const STATUS_CONFIG: Record<
  ProjectDetailType['status'],
  { label: string; className: string }
> = {
  recruiting: {
    label: 'Recruiting',
    className: 'bg-blue-500/15 text-blue-600 dark:text-blue-400',
  },
  in_progress: {
    label: 'In Progress',
    className: 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400',
  },
  completed: {
    label: 'Completed',
    className: 'bg-gray-500/15 text-gray-600 dark:text-gray-400',
  },
  abandoned: {
    label: 'Abandoned',
    className: 'bg-red-500/15 text-red-600 dark:text-red-400',
  },
}

const ROLE_LABELS: Record<RoleName, string> = {
  frontend_developer: 'Frontend Developer',
  backend_developer: 'Backend Developer',
  fullstack_developer: 'Fullstack Developer',
  devops: 'DevOps Engineer',
  qa: 'QA Engineer',
  security_specialist: 'Security Specialist',
  project_manager: 'Project Manager',
}

const SKILL_LEVEL_LABELS = {
  beginner: 'Beginner',
  intermediate: 'Intermediate',
}

function CircularScore({ score }: { score: number }) {
  const circumference = 2 * Math.PI * 15.9155
  const offset = circumference - (score / 100) * circumference

  return (
    <div className="relative h-12 w-12">
      <svg className="h-full w-full -rotate-90" viewBox="0 0 36 36">
        <path
          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
          fill="none"
          stroke="currentColor"
          strokeWidth="3"
          className="text-muted"
        />
        <path
          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
          fill="none"
          stroke="currentColor"
          strokeWidth="3"
          strokeDasharray={`${circumference}`}
          strokeDashoffset={offset}
          strokeLinecap="round"
          className="text-primary"
        />
      </svg>
      <div className="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-[11px] font-bold text-foreground">
        {score}
      </div>
    </div>
  )
}

function StatusChip({ status }: { status: ProjectDetailType['status'] }) {
  return (
    <div
      className={`inline-flex h-8 items-center gap-2 rounded-lg border px-3 ${STATUS_CONFIG[status].className}`}
    >
      <span className="h-2 w-2 rounded-full bg-current" />
      <span className="text-xs font-bold uppercase tracking-wide">
        {STATUS_CONFIG[status].label}
      </span>
    </div>
  )
}

export function ProjectDetail() {
  const { projectId } = useParams({ from: '/_layout/projects/$projectId' })
  const [project, setProject] = React.useState<ProjectDetailType | null>(null)
  const [loading, setLoading] = React.useState(true)
  const [error, setError] = React.useState<string | null>(null)

  React.useEffect(() => {
    const fetchProject = async () => {
      try {
        setLoading(true)
        const data = await projectService.getProject(projectId)
        setProject(data)
        setError(null)
      } catch (err: any) {
        console.error('Failed to fetch project:', err)
        setError(err.message || 'Failed to load project')
      } finally {
        setLoading(false)
      }
    }

    fetchProject()
  }, [projectId])

  const formatDeadline = (deadline: string) => {
    const date = new Date(deadline)
    const now = new Date()
    const diffTime = date.getTime() - now.getTime()
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))

    if (diffDays < 0) return 'Expired'
    if (diffDays === 0) return 'Today'
    if (diffDays === 1) return 'Tomorrow'
    if (diffDays < 7) return `${diffDays} days`
    if (diffDays < 30) return `${Math.ceil(diffDays / 7)} weeks`
    return `${Math.ceil(diffDays / 30)} months`
  }

  const formatDate = (dateStr: string) => {
    const date = new Date(dateStr)
    return new Intl.DateTimeFormat('en-US', {
      month: 'short',
      day: 'numeric',
      year: 'numeric',
    }).format(date)
  }

  // Loading state
  if (loading) {
    return (
      <div className="flex min-h-screen items-center justify-center">
        <div className="text-center">
          <div className="mb-4 h-12 w-12 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
          <p className="text-muted-foreground">Loading project...</p>
        </div>
      </div>
    )
  }

  // Error state
  if (error || !project) {
    return (
      <div className="flex min-h-screen items-center justify-center">
        <Card className="mx-4 max-w-md">
          <CardContent className="p-6 text-center">
            <p className="mb-4 text-lg font-bold text-destructive">
              Error loading project
            </p>
            <p className="text-sm text-muted-foreground">
              {error || 'Project not found'}
            </p>
            <Link to="/projects">
              <Button className="mt-4" variant="outline">
                Back to Projects
              </Button>
            </Link>
          </CardContent>
        </Card>
      </div>
    )
  }

  const owner = project.owner
  const ownerMember = project.members.find((m) => m.is_owner)

  return (
    <div className="min-h-screen text-foreground">
      <main className="mx-auto w-full max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
        {/* Breadcrumbs */}
        <div className="pt-6">
          <div className="flex flex-wrap items-center gap-2 text-sm">
            <Link
              to="/projects"
              className="font-medium text-muted-foreground hover:text-primary"
            >
              Projects
            </Link>
            <ChevronRight className="h-4 w-4 text-muted-foreground" />
            <span className="font-medium">{project.title}</span>
          </div>
        </div>

        {/* Layout */}
        <div className="grid grid-cols-1 gap-8 py-6 lg:grid-cols-12">
          {/* Left content */}
          <div className="flex flex-col gap-8 lg:col-span-8">
            {/* Header */}
            <section className="space-y-4">
              <h1 className="text-3xl font-black tracking-tight sm:text-4xl md:text-5xl">
                {project.title}
              </h1>

              <div className="flex flex-wrap gap-3 pt-2">
                <StatusChip status={project.status} />

                <div className="inline-flex h-8 items-center gap-2 rounded-lg border bg-muted/30 px-3">
                  <Clock className="h-4 w-4 text-muted-foreground" />
                  <span className="text-sm font-medium text-muted-foreground">
                    Posted {formatDate(project.created_at)}
                  </span>
                </div>

                <div className="inline-flex h-8 items-center gap-2 rounded-lg border bg-muted/30 px-3">
                  <Users className="h-4 w-4 text-muted-foreground" />
                  <span className="text-sm font-medium text-muted-foreground">
                    {project.remaining_slots}/{project.total_slots} slots open
                  </span>
                </div>
              </div>
            </section>

            {/* Owner card */}
            <Card>
              <CardContent className="p-5">
                <div className="flex flex-col gap-5 sm:flex-row sm:items-center">
                  <Avatar className="h-16 w-16 ring-2 ring-muted">
                    <AvatarImage src={undefined} />
                    <AvatarFallback className="bg-primary/10 text-lg font-bold text-primary">
                      {owner.first_name[0]}
                      {owner.last_name[0]}
                    </AvatarFallback>
                  </Avatar>

                  <div className="flex-1">
                    <div className="flex items-center gap-2">
                      <div className="text-lg font-bold">
                        {owner.first_name} {owner.last_name}
                      </div>
                      {owner.status === 'active' && (
                        <Badge variant="secondary" className="gap-1">
                          <BadgeCheck className="h-4 w-4 text-primary" />
                          Active
                        </Badge>
                      )}
                    </div>
                    <div className="text-sm text-muted-foreground">
                      {ownerMember?.roles.map((r) => ROLE_LABELS[r]).join(', ')}
                      {' • '}
                      {SKILL_LEVEL_LABELS[owner.skill_level]}
                    </div>
                    {owner.bio && (
                      <p className="mt-2 text-sm text-muted-foreground">
                        {owner.bio}
                      </p>
                    )}
                    {/* Social Links */}
                    <div className="mt-3 flex gap-2">
                      {owner.social_links.github && (
                        <a
                          href={owner.social_links.github}
                          target="_blank"
                          rel="noopener noreferrer"
                          className="text-muted-foreground hover:text-primary"
                        >
                          <FaGithub className="h-4 w-4" />
                        </a>
                      )}
                      {owner.social_links.linkedin && (
                        <a
                          href={owner.social_links.linkedin}
                          target="_blank"
                          rel="noopener noreferrer"
                          className="text-muted-foreground hover:text-primary"
                        >
                          <FaLinkedin className="h-4 w-4" />
                        </a>
                      )}
                      {owner.social_links.portfolio && (
                        <a
                          href={owner.social_links.portfolio}
                          target="_blank"
                          rel="noopener noreferrer"
                          className="text-muted-foreground hover:text-primary"
                        >
                          <ExternalLink className="h-4 w-4" />
                        </a>
                      )}
                    </div>
                  </div>

                  <div className="flex items-center gap-3 rounded-lg border bg-muted/20 p-3">
                    <CircularScore score={owner.bridge_score} />
                    <div className="flex flex-col">
                      <span className="text-xs font-bold uppercase tracking-wide">
                        Bridge Score
                      </span>
                      <span className="text-xs font-medium text-muted-foreground">
                        Commitment: {owner.commitment_score}
                      </span>
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>

            {/* About */}
            <section className="space-y-3">
              <h3 className="text-xl font-bold">About the Project</h3>
              <div className="space-y-4 text-muted-foreground">
                <p className="whitespace-pre-wrap leading-relaxed">
                  {project.description}
                </p>
              </div>
            </section>

            {/* Roles & Tech */}
            <section className="grid grid-cols-1 gap-5 md:grid-cols-2">
              <Card className="border-l-4 border-l-red-500/70 bg-red-500/5">
                <CardHeader className="pb-2">
                  <div className="flex items-center gap-3">
                    <div className="rounded-lg bg-red-500/10 p-2 text-red-600 dark:text-red-400">
                      <UserSearch className="h-5 w-5" />
                    </div>
                    <h4 className="text-lg font-bold">Roles Needed</h4>
                  </div>
                </CardHeader>
                <CardContent className="pt-2">
                  <ul className="space-y-3">
                    {project.required_roles.map((role) => (
                      <li key={role.name} className="flex items-start gap-3">
                        <CheckCircle2 className="mt-0.5 h-5 w-5 text-muted-foreground" />
                        <span className="text-sm text-muted-foreground">
                          {ROLE_LABELS[role.name]} ({role.count}{' '}
                          {role.count === 1 ? 'position' : 'positions'})
                        </span>
                      </li>
                    ))}
                  </ul>
                </CardContent>
              </Card>

              <Card className="border-l-4 border-l-primary bg-primary/5">
                <CardHeader className="pb-2">
                  <div className="flex items-center gap-3">
                    <div className="rounded-lg bg-primary/10 p-2 text-primary">
                      <Handshake className="h-5 w-5" />
                    </div>
                    <h4 className="text-lg font-bold">Tech Stack</h4>
                  </div>
                </CardHeader>
                <CardContent className="pt-2">
                  <div className="flex flex-wrap gap-2">
                    {project.tech_stack.map((tech) => (
                      <Badge key={tech} variant="outline" className="gap-1">
                        <Code className="h-3 w-3" />
                        {tech}
                      </Badge>
                    ))}
                  </div>
                </CardContent>
              </Card>
            </section>

            {/* Team Members */}
            {project.members.length > 0 && (
              <Card>
                <CardHeader className="pb-2">
                  <h3 className="text-xl font-bold">Team Members</h3>
                  <p className="text-sm text-muted-foreground">
                    {project.active_members_count} active member
                    {project.active_members_count !== 1 ? 's' : ''}
                  </p>
                </CardHeader>
                <CardContent className="pt-2">
                  <div className="space-y-4">
                    {project.members.map((member) => (
                      <div
                        key={member.id}
                        className="flex items-center gap-4 rounded-lg border bg-muted/20 p-3"
                      >
                        <Avatar className="h-10 w-10">
                          <AvatarFallback className="bg-primary/10 text-sm font-bold text-primary">
                            {member.user.first_name[0]}
                            {member.user.last_name[0]}
                          </AvatarFallback>
                        </Avatar>
                        <div className="flex-1">
                          <div className="flex items-center gap-2">
                            <span className="font-medium">
                              {member.user.first_name} {member.user.last_name}
                            </span>
                            {member.is_owner && (
                              <Badge variant="secondary" className="text-xs">
                                Owner
                              </Badge>
                            )}
                          </div>
                          <p className="text-xs text-muted-foreground">
                            {member.roles.map((r) => ROLE_LABELS[r]).join(', ')}
                          </p>
                        </div>
                        <Badge
                          variant={
                            member.status === 'active' ? 'default' : 'secondary'
                          }
                        >
                          {member.status}
                        </Badge>
                      </div>
                    ))}
                  </div>
                </CardContent>
              </Card>
            )}
          </div>

          {/* Right sidebar */}
          <div className="lg:col-span-4">
            <div className="sticky top-24 space-y-6">
              {/* CTA */}
              <Card className="rounded-2xl">
                <CardContent className="p-6">
                  <Button
                    className="w-full gap-2 py-6 text-base font-bold"
                    disabled={!project.can_accept_members}
                  >
                    {project.can_accept_members
                      ? 'Apply for this Project'
                      : 'Applications Closed'}
                  </Button>

                  {project.applications_count > 0 && (
                    <p className="mt-4 text-center text-xs text-muted-foreground">
                      {project.applications_count} application
                      {project.applications_count !== 1 ? 's' : ''} received
                      {project.pending_applications_count > 0 &&
                        ` (${project.pending_applications_count} pending)`}
                    </p>
                  )}

                  <div className="mt-6 space-y-3">
                    <div className="flex items-center justify-between rounded-lg border bg-muted/20 p-3">
                      <div className="flex items-center gap-3">
                        <Calendar className="h-4 w-4 text-muted-foreground" />
                        <span className="text-sm font-medium text-muted-foreground">
                          Deadline
                        </span>
                      </div>
                      <span className="text-sm font-bold">
                        {formatDeadline(project.deadline)}
                      </span>
                    </div>

                    <div className="flex items-center justify-between rounded-lg border bg-muted/20 p-3">
                      <div className="flex items-center gap-3">
                        <Users className="h-4 w-4 text-muted-foreground" />
                        <span className="text-sm font-medium text-muted-foreground">
                          Team Size
                        </span>
                      </div>
                      <span className="text-sm font-bold">
                        {project.active_members_count}/{project.total_slots}
                      </span>
                    </div>

                    <div className="flex items-center justify-between rounded-lg border bg-muted/20 p-3">
                      <div className="flex items-center gap-3">
                        <Timer className="h-4 w-4 text-muted-foreground" />
                        <span className="text-sm font-medium text-muted-foreground">
                          Status
                        </span>
                      </div>
                      <span className="text-sm font-bold">
                        {STATUS_CONFIG[project.status].label}
                      </span>
                    </div>
                  </div>
                </CardContent>
              </Card>

              {/* Links */}
              {(project.links.repository || project.links.live) && (
                <Card>
                  <CardContent className="p-6">
                    <div className="mb-4 text-sm font-bold uppercase tracking-wider">
                      Project Links
                    </div>

                    <div className="space-y-2">
                      {project.links.repository && (
                        <a
                          href={project.links.repository}
                          target="_blank"
                          rel="noopener noreferrer"
                          className="flex items-center gap-2 rounded-lg border bg-muted/20 p-3 text-sm font-medium hover:bg-muted/40"
                        >
                          <FaGithub className="h-4 w-4" />
                          Repository
                          <ExternalLink className="ml-auto h-4 w-4" />
                        </a>
                      )}
                      {project.links.live && (
                        <a
                          href={project.links.live}
                          target="_blank"
                          rel="noopener noreferrer"
                          className="flex items-center gap-2 rounded-lg border bg-muted/20 p-3 text-sm font-medium hover:bg-muted/40"
                        >
                          <Globe className="h-4 w-4" />
                          Live Demo
                          <ExternalLink className="ml-auto h-4 w-4" />
                        </a>
                      )}
                    </div>
                  </CardContent>
                </Card>
              )}

              {/* Owner Skills */}
              {owner.skills.length > 0 && (
                <Card>
                  <CardContent className="p-6">
                    <div className="mb-4 text-sm font-bold uppercase tracking-wider">
                      Owner Skills
                    </div>

                    <div className="flex flex-wrap gap-2">
                      {owner.skills.map((skill) => (
                        <Badge key={skill} variant="secondary">
                          {skill}
                        </Badge>
                      ))}
                    </div>
                  </CardContent>
                </Card>
              )}
            </div>
          </div>
        </div>
      </main>
    </div>
  )
}
