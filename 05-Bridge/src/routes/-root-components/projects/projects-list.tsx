import * as React from 'react'
import { Link } from '@tanstack/react-router'
import {
  Search,
  ChevronDown,
  ArrowLeft,
  ArrowRight,
  Calendar,
  Clock,
  Users,
} from 'lucide-react'

import { cn } from '@/lib/utils'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Input } from '@/components/ui/input'
import { Checkbox } from '@/components/ui/checkbox'
import { Separator } from '@/components/ui/separator'
import { ScrollArea } from '@/components/ui/scroll-area'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import {
  Sheet,
  SheetContent,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from '@/components/ui/sheet'
import {
  projectService,
  type Project,
  type RoleName,
} from '@/services/project-service'

/** ----------------------------
 * Configuration
 * ---------------------------- */
const STATUS_CONFIG: Record<
  Project['status'],
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
  frontend_developer: 'Frontend Dev',
  backend_developer: 'Backend Dev',
  fullstack_developer: 'Fullstack Dev',
  devops: 'DevOps',
  qa: 'QA',
  security_specialist: 'Security',
  project_manager: 'PM',
}

/** ----------------------------
 * UI helpers
 * ---------------------------- */
function SkillPill({ children }: { children: React.ReactNode }) {
  return (
    <span className="rounded-md border border-primary/20 bg-primary/10 px-2.5 py-1 text-xs font-medium text-primary">
      {children}
    </span>
  )
}

/** ----------------------------
 * Filters state
 * ---------------------------- */
type StatusFilter = Project['status']

function toggleInList<T extends string>(list: T[], value: T) {
  return list.includes(value)
    ? list.filter((x) => x !== value)
    : [...list, value]
}

function FiltersPanel(props: {
  statuses: StatusFilter[]
  setStatuses: React.Dispatch<React.SetStateAction<StatusFilter[]>>
  roles: RoleName[]
  setRoles: React.Dispatch<React.SetStateAction<RoleName[]>>
  techStack: string[]
  setTechStack: React.Dispatch<React.SetStateAction<string[]>>
  availableTechStack: string[]
  onReset: () => void
}) {
  const {
    statuses,
    setStatuses,
    roles,
    setRoles,
    techStack,
    setTechStack,
    availableTechStack,
    onReset,
  } = props

  const statusOptions: StatusFilter[] = [
    'recruiting',
    'in_progress',
    'completed',
    'abandoned',
  ]
  const roleOptions: RoleName[] = [
    'frontend_developer',
    'backend_developer',
    'fullstack_developer',
    'devops',
    'qa',
    'security_specialist',
    'project_manager',
  ]

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div className="text-lg font-bold">Filters</div>
        <Button variant="ghost" size="sm" onClick={onReset}>
          Reset All
        </Button>
      </div>

      <div className="space-y-3">
        <div className="text-xs font-bold uppercase tracking-wider text-muted-foreground">
          Status
        </div>
        <div className="space-y-2">
          {statusOptions.map((s) => (
            <label
              key={s}
              className="flex cursor-pointer items-center gap-3 text-sm"
            >
              <Checkbox
                checked={statuses.includes(s)}
                onCheckedChange={() => setStatuses(toggleInList(statuses, s))}
              />
              <span>{STATUS_CONFIG[s].label}</span>
            </label>
          ))}
        </div>
      </div>

      <Separator />

      <div className="space-y-3">
        <div className="text-xs font-bold uppercase tracking-wider text-muted-foreground">
          Roles Needed
        </div>
        <div className="space-y-2">
          {roleOptions.map((r) => (
            <label
              key={r}
              className="flex cursor-pointer items-center gap-3 text-sm"
            >
              <Checkbox
                checked={roles.includes(r)}
                onCheckedChange={() => setRoles(toggleInList(roles, r))}
              />
              <span>{ROLE_LABELS[r]}</span>
            </label>
          ))}
        </div>
      </div>

      {availableTechStack.length > 0 && (
        <>
          <Separator />
          <div className="space-y-3">
            <div className="text-xs font-bold uppercase tracking-wider text-muted-foreground">
              Tech Stack
            </div>
            <div className="max-h-48 space-y-2 overflow-y-auto">
              {availableTechStack.slice(0, 20).map((tech) => (
                <label
                  key={tech}
                  className="flex cursor-pointer items-center gap-3 text-sm"
                >
                  <Checkbox
                    checked={techStack.includes(tech)}
                    onCheckedChange={() =>
                      setTechStack(toggleInList(techStack, tech))
                    }
                  />
                  <span>{tech}</span>
                </label>
              ))}
            </div>
          </div>
        </>
      )}
    </div>
  )
}

/** ----------------------------
 * Main
 * ---------------------------- */
export function ProjectsList() {
  const [projects, setProjects] = React.useState<Project[]>([])
  const [loading, setLoading] = React.useState(true)
  const [error, setError] = React.useState<string | null>(null)
  const [searchQuery, setSearchQuery] = React.useState('')
  const [sort, setSort] = React.useState<'newest' | 'deadline'>('newest')
  const [statuses, setStatuses] = React.useState<StatusFilter[]>(['recruiting'])
  const [roles, setRoles] = React.useState<RoleName[]>([])
  const [techStack, setTechStack] = React.useState<string[]>([])

  // Fetch projects from API
  React.useEffect(() => {
    const fetchProjects = async () => {
      try {
        setLoading(true)
        const data = await projectService.getProjects()
        setProjects(data)
        setError(null)
      } catch (err: any) {
        console.error('Failed to fetch projects:', err)
        setError(err.message || 'Failed to load projects')
      } finally {
        setLoading(false)
      }
    }

    fetchProjects()
  }, [])

  // Get all unique tech stack items
  const availableTechStack = React.useMemo(() => {
    const allTech = projects.flatMap((p) => p.tech_stack)
    return Array.from(new Set(allTech)).sort()
  }, [projects])

  const reset = () => {
    setSearchQuery('')
    setSort('newest')
    setStatuses(['recruiting'])
    setRoles([])
    setTechStack([])
  }

  const filtered = React.useMemo(() => {
    const q = searchQuery.trim().toLowerCase()

    let items = projects.filter((project) => {
      // Search filter
      if (q) {
        const searchable = [
          project.title,
          project.description,
          ...project.tech_stack,
          ...project.required_roles.map((r) => ROLE_LABELS[r.name]),
        ]
          .join(' ')
          .toLowerCase()
        if (!searchable.includes(q)) return false
      }

      // Status filter
      if (statuses.length && !statuses.includes(project.status)) return false

      // Roles filter
      if (roles.length) {
        const projectRoles = project.required_roles.map((r) => r.name)
        const hasRole = roles.some((r) => projectRoles.includes(r))
        if (!hasRole) return false
      }

      // Tech stack filter
      if (techStack.length) {
        const hasTech = techStack.some((tech) =>
          project.tech_stack.includes(tech),
        )
        if (!hasTech) return false
      }

      return true
    })

    // Sorting
    if (sort === 'deadline') {
      items = [...items].sort((a, b) => a.deadline.localeCompare(b.deadline))
    } else {
      // newest first - assuming ID has timestamp
      items = [...items].reverse()
    }

    return items
  }, [searchQuery, statuses, roles, techStack, sort, projects])

  const formatDeadline = (deadline: string) => {
    const date = new Date(deadline)
    const now = new Date()
    const diffTime = date.getTime() - now.getTime()
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))

    if (diffDays < 0) return 'Expired'
    if (diffDays === 0) return 'Today'
    if (diffDays === 1) return 'Tomorrow'
    if (diffDays < 7) return `${diffDays} days left`
    if (diffDays < 30) return `${Math.ceil(diffDays / 7)} weeks left`
    return `${Math.ceil(diffDays / 30)} months left`
  }

  // Loading state
  if (loading) {
    return (
      <div className="flex min-h-screen items-center justify-center">
        <div className="text-center">
          <div className="mb-4 h-12 w-12 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
          <p className="text-muted-foreground">Loading projects...</p>
        </div>
      </div>
    )
  }

  // Error state
  if (error) {
    return (
      <div className="flex min-h-screen items-center justify-center">
        <Card className="mx-4 max-w-md">
          <CardContent className="p-6 text-center">
            <p className="mb-4 text-lg font-bold text-destructive">
              Error loading projects
            </p>
            <p className="text-sm text-muted-foreground">{error}</p>
            <Button
              onClick={() => window.location.reload()}
              className="mt-4"
              variant="outline"
            >
              Retry
            </Button>
          </CardContent>
        </Card>
      </div>
    )
  }

  return (
    <div className="min-h-screen text-foreground">
      <div className="mx-auto flex w-full max-w-7xl gap-8 p-4 lg:p-6">
        {/* Sidebar Filters (Desktop) */}
        <aside className="hidden w-72 shrink-0 lg:block">
          <div className="sticky top-24">
            <Card>
              <CardContent className="p-4">
                <FiltersPanel
                  statuses={statuses}
                  setStatuses={setStatuses}
                  roles={roles}
                  setRoles={setRoles}
                  techStack={techStack}
                  setTechStack={setTechStack}
                  availableTechStack={availableTechStack}
                  onReset={reset}
                />
              </CardContent>
            </Card>

            <Card className="mt-4">
              <CardContent className="p-4">
                <p className="text-sm font-bold">New to Bridge?</p>
                <p className="mt-1 text-xs text-muted-foreground">
                  Complete your profile to get a +10 initial reliability score
                  boost.
                </p>
                <Button variant="link" className="mt-2 h-auto p-0 text-primary">
                  Complete Profile →
                </Button>
              </CardContent>
            </Card>
          </div>
        </aside>

        {/* Main */}
        <main className="min-w-0 flex-1">
          {/* Heading */}
          <div className="mb-6">
            <h1 className="text-3xl font-bold tracking-tight">
              Find a Project to Bridge
            </h1>
            <p className="mt-2 max-w-2xl text-base text-muted-foreground">
              Exchange your time for experience. Collaborate with peers, build
              real products, and grow your Bridge Score.
            </p>
          </div>

          {/* Search + Sort + Mobile Filters */}
          <div className="mb-6 flex flex-col gap-3 md:flex-row md:items-center">
            <div className="relative flex-1">
              <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
              <Input
                className="pl-9"
                placeholder="Search by keyword, role, or tech stack..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
              />
            </div>

            <DropdownMenu>
              <DropdownMenuTrigger asChild>
                <Button
                  variant="outline"
                  className="w-full justify-between md:w-45"
                >
                  <span className="truncate">
                    Sort: {sort === 'newest' ? 'Newest' : 'Deadline'}
                  </span>
                  <ChevronDown className="ml-2 h-4 w-4" />
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="end">
                <DropdownMenuItem onClick={() => setSort('newest')}>
                  Newest
                </DropdownMenuItem>
                <DropdownMenuItem onClick={() => setSort('deadline')}>
                  Deadline
                </DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu>

            {/* Mobile: Filters Sheet */}
            <div className="lg:hidden">
              <Sheet>
                <SheetTrigger asChild>
                  <Button variant="outline" className="w-full">
                    Filters
                  </Button>
                </SheetTrigger>
                <SheetContent side="bottom" className="h-[85vh] p-0">
                  <SheetHeader className="p-4 pb-2">
                    <SheetTitle>Filters</SheetTitle>
                  </SheetHeader>
                  <Separator />
                  <ScrollArea className="h-[calc(85vh-64px)] px-4 pb-6">
                    <div className="pt-4">
                      <FiltersPanel
                        statuses={statuses}
                        setStatuses={setStatuses}
                        roles={roles}
                        setRoles={setRoles}
                        techStack={techStack}
                        setTechStack={setTechStack}
                        availableTechStack={availableTechStack}
                        onReset={reset}
                      />
                    </div>
                  </ScrollArea>
                </SheetContent>
              </Sheet>
            </div>
          </div>

          {/* Quick status chips */}
          <div className="mb-8 flex gap-3 overflow-x-auto pb-2">
            <Button
              className="shrink-0 rounded-full"
              size="sm"
              variant={statuses.length === 0 ? 'default' : 'outline'}
              onClick={() => setStatuses([])}
            >
              All Projects
            </Button>
            {(['recruiting', 'in_progress', 'completed'] as StatusFilter[]).map(
              (status) => (
                <Button
                  key={status}
                  variant={
                    statuses.includes(status) ? 'default' : 'outline'
                  }
                  size="sm"
                  className="shrink-0 rounded-full"
                  onClick={() =>
                    setStatuses(
                      statuses.includes(status)
                        ? statuses.filter((s) => s !== status)
                        : [status],
                    )
                  }
                >
                  {STATUS_CONFIG[status].label}
                </Button>
              ),
            )}
          </div>

          {/* Cards */}
          <div className="flex flex-col gap-4">
            {filtered.length === 0 && (
              <Card>
                <CardContent className="p-12 text-center">
                  <p className="text-muted-foreground">
                    No projects found matching your filters.
                  </p>
                  <Button variant="link" onClick={reset} className="mt-2">
                    Reset filters
                  </Button>
                </CardContent>
              </Card>
            )}

            {filtered.map((project) => (
              <Card key={project.id} className="overflow-hidden">
                <CardHeader className="p-5">
                  <div className="flex items-start justify-between gap-4">
                    <div className="min-w-0 flex-1">
                      <h3 className="text-lg font-bold leading-tight">
                        {project.title}
                      </h3>

                      <div className="mt-2 flex flex-wrap items-center gap-2">
                        <Badge
                          variant="secondary"
                          className={cn(
                            'rounded-md px-2 py-1 text-xs font-bold',
                            STATUS_CONFIG[project.status].className,
                          )}
                        >
                          {STATUS_CONFIG[project.status].label}
                        </Badge>

                        <span className="flex items-center gap-1 text-xs text-muted-foreground">
                          <Users className="h-3.5 w-3.5" />
                          {project.remaining_slots}/{project.total_slots} slots
                        </span>
                      </div>
                    </div>
                  </div>

                  <p className="mt-3 line-clamp-2 text-sm leading-relaxed text-muted-foreground">
                    {project.description}
                  </p>
                </CardHeader>

                <CardContent className="p-5 pt-0">
                  {/* Roles needed */}
                  <div className="mb-4">
                    <p className="mb-2 text-[10px] font-bold uppercase tracking-wider text-muted-foreground">
                      Roles Needed
                    </p>
                    <div className="flex flex-wrap gap-2">
                      {project.required_roles.map((role) => (
                        <SkillPill key={role.name}>
                          {ROLE_LABELS[role.name]} ({role.count})
                        </SkillPill>
                      ))}
                    </div>
                  </div>

                  {/* Tech Stack */}
                  {project.tech_stack.length > 0 && (
                    <div className="mb-4">
                      <p className="mb-2 text-[10px] font-bold uppercase tracking-wider text-muted-foreground">
                        Tech Stack
                      </p>
                      <div className="flex flex-wrap gap-2">
                        {project.tech_stack.map((tech) => (
                          <Badge
                            key={tech}
                            variant="outline"
                            className="text-xs"
                          >
                            {tech}
                          </Badge>
                        ))}
                      </div>
                    </div>
                  )}

                  <div className="mt-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div className="flex flex-wrap items-center gap-4 text-xs font-medium text-muted-foreground">
                      <div className="flex items-center gap-1.5">
                        <Calendar className="h-4 w-4" />
                        <span>{formatDeadline(project.deadline)}</span>
                      </div>
                      {(project.links.repository || project.links.live) && (
                        <div className="flex items-center gap-1.5">
                          <Clock className="h-4 w-4" />
                          <span>
                            {project.links.repository && 'Repo'}
                            {project.links.repository &&
                              project.links.live &&
                              ' + '}
                            {project.links.live && 'Live'}
                          </span>
                        </div>
                      )}
                    </div>

                    <div className="flex items-center justify-between gap-3 md:justify-end">
                      <Link
                        to="/projects/$projectId"
                        params={{ projectId: project.id }}
                      >
                        <Button className="w-full md:w-auto">View</Button>
                      </Link>
                    </div>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>

          {/* Pagination */}
          {filtered.length > 0 && (
            <div className="mt-8 flex items-center justify-center gap-3 pb-8">
              <Button variant="outline" size="icon" disabled>
                <ArrowLeft className="h-4 w-4" />
              </Button>
              <Button size="icon">1</Button>
              <Button variant="ghost" size="icon">
                2
              </Button>
              <Button variant="ghost" size="icon">
                3
              </Button>
              <span className="text-muted-foreground">…</span>
              <Button variant="ghost" size="icon">
                8
              </Button>
              <Button variant="outline" size="icon">
                <ArrowRight className="h-4 w-4" />
              </Button>
            </div>
          )}
        </main>
      </div>
    </div>
  )
}
