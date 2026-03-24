import { createFileRoute } from '@tanstack/react-router'
import { ProjectsList } from '../../-root-components/projects/projects-list'

export const Route = createFileRoute('/_layout/projects/')({
  component: ProjectsPage,
})

function ProjectsPage() {
  return <ProjectsList />
}
