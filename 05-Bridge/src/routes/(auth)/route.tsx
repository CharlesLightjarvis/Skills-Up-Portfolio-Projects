import { createFileRoute, Outlet, redirect } from '@tanstack/react-router'
import bridge from '../../assets/bridge.png'
import { GalleryVerticalEnd } from 'lucide-react'
export const Route = createFileRoute('/(auth)')({
  component: RouteComponent,
  beforeLoad: async ({ context }) => {
    const { isAuthenticated } = context
    if (isAuthenticated) {
      throw redirect({
        to: '/projects',
      })
    }
  },
})

function RouteComponent() {
  return (
    <div className="grid min-h-svh lg:grid-cols-2">
      <div className="flex flex-col gap-4 p-6 md:p-10">
        <div className="flex justify-center gap-2 md:justify-start">
          <a href="#" className="flex items-center gap-2 font-medium">
            <div className="bg-primary text-primary-foreground flex size-6 items-center justify-center rounded-md">
              <GalleryVerticalEnd className="size-4" />
            </div>
            Acme Inc.
          </a>
        </div>
        <div className="flex flex-1 items-center justify-center">
          <div className="w-full max-w-md">
            <Outlet />
          </div>
        </div>
      </div>
      <div className="bg-muted relative hidden lg:block">
        <img
          src={bridge}
          alt="Image"
          className="absolute inset-0 h-full w-full object-contain transition-opacity duration-300"
        />
      </div>
    </div>
  )
}
