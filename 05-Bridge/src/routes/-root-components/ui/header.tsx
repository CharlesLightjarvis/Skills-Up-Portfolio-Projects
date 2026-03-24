import React from 'react'
import { Button, buttonVariants } from '@/components/ui/button'
import { cn } from '@/lib/utils'
import { useScroll } from '../use-scroll'
import { MenuToggleIcon } from '../menu-toggle-icon'
import bridgeImage from '../../../assets/bridge.png' // chemin vers ton PNG
import { ModeToggle } from '../../../components/mode-toggle'
import { Link } from '@tanstack/react-router'
import { useAuthStore } from '@/stores/auth-store'
import { authService } from '@/services/auth-service'
import { toast } from 'sonner'

export function Header() {
  const [open, setOpen] = React.useState(false)
  const { isAuthenticated } = useAuthStore()
  const scrolled = useScroll(10)
  const [loadingSso, setLoadingSso] = React.useState(false)

  const handleGoToDashboard = async () => {
    setLoadingSso(true)
    try {
      const { sso_url } = await authService.generateSsoToken()
      console.log('SSO URL:', sso_url)
      // Redirect to backend SSO endpoint (will handle auth and redirect to dashboard)
      window.location.href = sso_url
    } catch (error: any) {
      console.error('SSO error:', error)
      toast.error('Erreur lors de la génération du token SSO')
      setLoadingSso(false)
    }
  }

  const links = [
    {
      label: 'Features',
      href: '#',
    },
    {
      label: 'Pricing',
      href: '#',
    },
    {
      label: 'About',
      href: '#',
    },
  ]

  React.useEffect(() => {
    if (open) {
      // Disable scroll
      document.body.style.overflow = 'hidden'
    } else {
      // Re-enable scroll
      document.body.style.overflow = ''
    }

    // Cleanup when component unmounts (important for Next.js)
    return () => {
      document.body.style.overflow = ''
    }
  }, [open])

  return (
    <header
      className={cn(
        'sticky top-3 z-50 mx-auto w-full max-w-7xl border-b border-transparent md:rounded-md md:border md:transition-all md:ease-out',
        {
          'bg-background/95 supports-backdrop-filter:bg-background/50 border-border backdrop-blur-lg md:top-4 md:max-w-6xl md:shadow':
            scrolled && !open,
          'bg-background/90': open,
        },
      )}
    >
      <nav
        className={cn(
          'flex h-14 w-full items-center justify-between px-4 md:h-12 md:transition-all md:ease-out',
          {
            'md:px-2': scrolled,
          },
        )}
      >
        {/* BridgeIcon qui change de taille au scroll */}
        <Link to="/">
          <BridgeIcon
            className={cn(
              'transition-all duration-300 ease-out', // animation douce
              {
                'h-24': !scrolled, // taille par défaut
                'h-20': scrolled, // taille quand on scroll
              },
            )}
          />
        </Link>
        <div className="hidden items-center gap-2 md:flex">
          {links.map((link, i) => (
            <a
              key={i}
              className={buttonVariants({ variant: 'ghost' })}
              href={link.href}
            >
              {link.label}
            </a>
          ))}

          {isAuthenticated ? (
            <Button onClick={handleGoToDashboard} disabled={loadingSso}>
              {loadingSso ? (
                <>
                  <span className="mr-2 h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent" />
                  Loading...
                </>
              ) : (
                <span>Dashboard</span>
              )}
            </Button>
          ) : (
            <>
              <Button variant="outline" asChild>
                <Link to="/register">Sign In</Link>
              </Button>

              <Button asChild>
                <Link to="/login">Get Started</Link>
              </Button>
            </>
          )}
          <ModeToggle />
        </div>
        <Button
          size="icon"
          variant="outline"
          onClick={() => setOpen(!open)}
          className="md:hidden"
        >
          <MenuToggleIcon open={open} className="size-5" duration={300} />
        </Button>
      </nav>

      <div
        className={cn(
          'bg-background/90 fixed top-14 right-0 bottom-0 left-0 z-50 flex flex-col overflow-hidden border-y md:hidden',
          open ? 'block' : 'hidden',
        )}
      >
        <div
          data-slot={open ? 'open' : 'closed'}
          className={cn(
            'data-[slot=open]:animate-in data-[slot=open]:zoom-in-95 data-[slot=closed]:animate-out data-[slot=closed]:zoom-out-95 ease-out',
            'flex h-full w-full flex-col justify-between gap-y-2 p-4',
          )}
        >
          <div className="grid gap-y-2">
            {links.map((link) => (
              <a
                key={link.label}
                className={buttonVariants({
                  variant: 'ghost',
                  className: 'justify-start',
                })}
                href={link.href}
              >
                {link.label}
              </a>
            ))}
          </div>
          <div className="flex flex-col gap-2">
            <Button variant="outline" className="w-full">
              Sign In
            </Button>
            <Button className="w-full">Get Started</Button>
          </div>
        </div>
      </div>
    </header>
  )
}

export const BridgeIcon = (props: React.ComponentProps<'img'>) => (
  <img
    src={bridgeImage}
    alt="Bridge icon"
    className="h-8 w-auto md:h-10" // ajustable selon la taille du header
    style={{ display: 'block', objectFit: 'contain' }}
    {...props}
  />
)
