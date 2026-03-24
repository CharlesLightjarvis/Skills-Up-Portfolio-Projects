import { createFileRoute } from '@tanstack/react-router'
import { ProjectDetail } from '../../-root-components/projects/project-detail'

export const Route = createFileRoute('/_layout/projects/$projectId')({
  component: ProjectDetailPage,
})

function ProjectDetailPage() {
  return (
    <div className="min-h-screen pb-20 ">
      <ProjectDetail />
    </div>
  )
}
