import { createFileRoute, Outlet } from '@tanstack/react-router'
import { Header } from '../-root-components/ui/header'
import { Footer } from '../-root-components/ui/footer'

export const Route = createFileRoute('/_layout')({
  component: RouteComponent,
})

function RouteComponent() {
  return (
    <>
      <Header />
      <Outlet />
      <Footer />
    </>
  )
}
